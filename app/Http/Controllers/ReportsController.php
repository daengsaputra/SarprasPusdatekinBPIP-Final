<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ReportsController extends Controller
{
    private function resolveRange(Request $request): array
    {
        $range = $request->query('range', 'month');
        $now = Carbon::now();

        return match ($range) {
            'week' => [$range, $now->copy()->subDays(7)->startOfDay(), $now->copy()->endOfDay(), '7 Hari Terakhir'],
            'year' => [$range, $now->copy()->startOfYear(), $now->copy()->endOfDay(), 'Tahun Ini'],
            'custom' => [
                $range,
                Carbon::parse($request->query('start', $now->copy()->startOfMonth()))->startOfDay(),
                Carbon::parse($request->query('end', $now))->endOfDay(),
                'Rentang Kustom',
            ],
            default => ['month', $now->copy()->subDays(30)->startOfDay(), $now->copy()->endOfDay(), '30 Hari Terakhir'],
        };
    }

    private function buildLoanQuery(Request $request, Carbon $start, Carbon $end, bool $returnsOnly = false)
    {
        $search = trim((string) $request->query('q'));
        $unit = $request->query('unit');
        $status = $request->query('status');

        $dateColumn = $returnsOnly ? 'return_date_actual' : 'loan_date';

        return Loan::query()
            ->with('asset')
            ->when($returnsOnly, fn($q) => $q->where('status', 'returned'))
            ->when(!$returnsOnly && $status, fn($q) => $q->where('status', $status))
            ->when($unit, fn($q) => $q->where('unit', $unit))
            ->when($search, function ($query) use ($search) {
                $pattern = '%' . $search . '%';
                $query->where(function ($q) use ($pattern) {
                    $q->where('borrower_name', 'like', $pattern)
                        ->orWhereHas('asset', fn($asset) => $asset
                            ->where('name', 'like', $pattern)
                            ->orWhere('code', 'like', $pattern));
                });
            })
            ->whereBetween($dateColumn, [$start->toDateTimeString(), $end->toDateTimeString()]);
    }

    private function applySorting($query, string $sort, string $dir, bool $returnsOnly = false)
    {
        $map = [
            'loan_date' => 'loan_date',
            'return_date_actual' => 'return_date_actual',
            'return_date_planned' => 'return_date_planned',
            'quantity' => 'quantity',
            'borrower_name' => 'borrower_name',
            'unit' => 'unit',
        ];

        if ($sort === 'asset') {
            $query->leftJoin('assets as a', 'a.id', '=', 'loans.asset_id')
                ->select('loans.*')
                ->orderBy('a.name', $dir);
        } elseif (array_key_exists($sort, $map)) {
            $query->orderBy($map[$sort], $dir);
        } else {
            $query->orderBy($returnsOnly ? 'return_date_actual' : 'loan_date', 'desc');
        }

        return $query;
    }

    public function loans(Request $request)
    {
        [$rangeKey, $start, $end, $rangeLabel] = $this->resolveRange($request);

        $sort = $request->query('sort', 'loan_date');
        $dir = $request->query('dir', 'desc') === 'asc' ? 'asc' : 'desc';

        $baseQuery = $this->buildLoanQuery($request, $start, $end);
        $tableQuery = $this->applySorting(clone $baseQuery, $sort, $dir);

        $loans = $tableQuery->paginate(15)->withQueryString();

        $summary = [
            'periode' => $rangeLabel,
            'start' => $start->toDateString(),
            'end' => $end->toDateString(),
            'total_transaksi' => (clone $baseQuery)->count(),
            'total_jumlah' => (clone $baseQuery)->sum('quantity'),
        ];

        $units = config('bpip.units', []);

        return view('reports.loans', [
            'records' => $loans,
            'summary' => $summary,
            'rangeKey' => $rangeKey,
            'start' => $start,
            'end' => $end,
            'units' => $units,
            'filters' => $request->all(),
            'sort' => $sort,
            'dir' => $dir,
        ]);
    }

    public function returns(Request $request)
    {
        [$rangeKey, $start, $end, $rangeLabel] = $this->resolveRange($request);

        $sort = $request->query('sort', 'return_date_actual');
        $dir = $request->query('dir', 'desc') === 'asc' ? 'asc' : 'desc';

        $baseQuery = $this->buildLoanQuery($request, $start, $end, true);
        $tableQuery = $this->applySorting(clone $baseQuery, $sort, $dir, true);

        $returns = $tableQuery->paginate(15)->withQueryString();

        $summary = [
            'periode' => $rangeLabel,
            'start' => $start->toDateString(),
            'end' => $end->toDateString(),
            'total_transaksi' => (clone $baseQuery)->count(),
            'total_jumlah' => (clone $baseQuery)->sum('quantity'),
        ];

        $units = config('bpip.units', []);

        return view('reports.returns', [
            'records' => $returns,
            'summary' => $summary,
            'rangeKey' => $rangeKey,
            'start' => $start,
            'end' => $end,
            'units' => $units,
            'filters' => $request->all(),
            'sort' => $sort,
            'dir' => $dir,
        ]);
    }

    public function loansPdf(Request $request)
    {
        [$rangeKey, $start, $end] = $this->resolveRange($request);
        $rows = $this->buildLoanQuery($request, $start, $end)->orderBy('loan_date')->get();

        $pdf = Pdf::loadView('reports.pdf.loans', [
            'rows' => $rows,
            'title' => 'Laporan Peminjaman',
            'period' => [$start->toDateString(), $end->toDateString()],
        ])->setPaper('a4', 'portrait');

        return $pdf->download("laporan-peminjaman-{$start->format('Ymd')}-{$end->format('Ymd')}.pdf");
    }

    public function returnsPdf(Request $request)
    {
        [$rangeKey, $start, $end] = $this->resolveRange($request);
        $rows = $this->buildLoanQuery($request, $start, $end, true)->orderBy('return_date_actual')->get();

        $pdf = Pdf::loadView('reports.pdf.returns', [
            'rows' => $rows,
            'title' => 'Laporan Pengembalian',
            'period' => [$start->toDateString(), $end->toDateString()],
        ])->setPaper('a4', 'portrait');

        return $pdf->download("laporan-pengembalian-{$start->format('Ymd')}-{$end->format('Ymd')}.pdf");
    }

    public function loansExcel(Request $request)
    {
        [$rangeKey, $start, $end] = $this->resolveRange($request);
        $rows = $this->buildLoanQuery($request, $start, $end)->orderBy('loan_date')->get();

        return $this->streamCsv($rows, 'laporan-peminjaman', ['Tanggal', 'Aset', 'Peminjam', 'Unit', 'Status', 'Jumlah', 'Rencana Kembali', 'Kembali']);
    }

    public function returnsExcel(Request $request)
    {
        [$rangeKey, $start, $end] = $this->resolveRange($request);
        $rows = $this->buildLoanQuery($request, $start, $end, true)->orderBy('return_date_actual')->get();

        return $this->streamCsv($rows, 'laporan-pengembalian', ['Tanggal Peminjaman', 'Aset', 'Peminjam', 'Unit', 'Jumlah', 'Rencana Kembali', 'Kembali']);
    }

    private function streamCsv($rows, string $basename, array $headers)
    {
        $filename = $basename . '-' . now()->format('Ymd-His') . '.csv';

        $callback = static function () use ($rows, $headers) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);
            foreach ($rows as $row) {
                fputcsv($handle, [
                    optional($row->loan_date)->format('Y-m-d'),
                    $row->asset->name ?? '-',
                    $row->borrower_name,
                    $row->unit,
                    $row->status,
                    $row->quantity,
                    optional($row->return_date_planned)->format('Y-m-d'),
                    optional($row->return_date_actual)->format('Y-m-d'),
                ]);
            }
            fclose($handle);
        };

        return Response::streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
