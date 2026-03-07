<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('root');
    }

    /**
     * Menampilkan halaman utama
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function root()
    {
        return view('landing');  // Ganti dengan nama view yang sesuai, misalnya 'home'
    }

    /**
     * Menampilkan halaman dashboard
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $weekStart = now()->startOfWeek(Carbon::SUNDAY)->startOfDay();
        $weekEnd = (clone $weekStart)->addDays(6)->endOfDay();

        $totalBarang = (int) Asset::sum('quantity_total');
        $totalBarangAset = (int) Asset::where('kind', Asset::KIND_INVENTORY)->sum('quantity_total');
        $totalPeminjaman = (int) Loan::count();
        $totalPengembalian = (int) Loan::where('quantity_returned', '>', 0)->count();
        $totalDipinjamHistoris = (int) Loan::sum('quantity');
        $totalBarangKembali = (int) Loan::sum('quantity_returned');
        $totalBarangDipinjam = max($totalDipinjamHistoris - $totalBarangKembali, 0);

        $loanByDay = Loan::query()
            ->whereBetween('loan_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->selectRaw('loan_date as day, COUNT(*) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        $returnByDay = Loan::query()
            ->whereNotNull('return_date_actual')
            ->whereBetween('return_date_actual', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->selectRaw('return_date_actual as day, COUNT(*) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        $weeklyLabels = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        $weeklyLoanSeries = [];
        $weeklyReturnSeries = [];
        for ($i = 0; $i < 7; $i++) {
            $date = (clone $weekStart)->addDays($i)->toDateString();
            $weeklyLoanSeries[] = (int) ($loanByDay[$date] ?? 0);
            $weeklyReturnSeries[] = (int) ($returnByDay[$date] ?? 0);
        }

        $weeklyLoanTotal = array_sum($weeklyLoanSeries);
        $weeklyReturnTotal = array_sum($weeklyReturnSeries);

        $asetPercent = $totalBarang > 0 ? (int) round(($totalBarangAset / $totalBarang) * 100) : 0;
        $dipinjamPercent = $totalBarang > 0 ? (int) round(($totalBarangDipinjam / $totalBarang) * 100) : 0;
        $kembaliPercent = $totalDipinjamHistoris > 0
            ? (int) round(($totalBarangKembali / $totalDipinjamHistoris) * 100)
            : 0;

        $dashboardMembers = User::query()
            ->orderByDesc('created_at')
            ->limit(8)
            ->get(['id', 'name', 'email', 'role', 'photo', 'created_at']);

        $totalMembers = (int) User::count();

        return view('home', compact(
            'totalBarang',
            'totalBarangAset',
            'totalPeminjaman',
            'totalPengembalian',
            'totalBarangDipinjam',
            'totalBarangKembali',
            'weeklyLabels',
            'weeklyLoanSeries',
            'weeklyReturnSeries',
            'weeklyLoanTotal',
            'weeklyReturnTotal',
            'asetPercent',
            'dipinjamPercent',
            'kembaliPercent',
            'dashboardMembers',
            'totalMembers'
        ));
    }
}
