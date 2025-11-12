@php
    use Illuminate\Support\Str;
    $title = 'Laporan Pengembalian';
@endphp
@extends('layouts.app')

@push('styles')
<style>
  .report-shell { display:flex; flex-direction:column; gap:1rem; }
  .report-hero {
    display:flex; justify-content:space-between; gap:1rem; flex-wrap:wrap;
    padding:1.3rem 1.5rem; border-radius:20px;
    background:linear-gradient(120deg, rgba(16,185,129,0.2), #fff);
    border:1px solid rgba(148,163,184,0.15); box-shadow:0 18px 40px rgba(15,23,42,0.1);
  }
  .report-filter { background:#fff; border:1px solid rgba(148,163,184,0.15); border-radius:18px; padding:1rem; box-shadow:0 10px 25px rgba(15,23,42,0.06); }
  .report-filter label { text-transform:uppercase; letter-spacing:.2em; font-size:.72rem; color:#94a3b8; }
  .report-actions { display:flex; flex-wrap:wrap; gap:.5rem; align-items:center; }
  .report-table-card { background:#fff; border:1px solid rgba(148,163,184,0.12); border-radius:20px; padding:1rem; box-shadow:0 12px 30px rgba(15,23,42,0.08); }
  .report-table-card table th { text-transform:uppercase; letter-spacing:.12em; font-size:.72rem; color:#64748b; }
  .summary-pill { padding:.4rem .9rem; border-radius:999px; background:rgba(16,185,129,.15); font-size:.8rem; }
</style>
@endpush

@section('content')
<div class="report-shell">
  <section class="report-hero">
    <div>
      <p class="text-uppercase text-muted mb-1" style="letter-spacing:.35em;">Laporan</p>
      <h1 class="h5 mb-1">Riwayat Pengembalian Barang</h1>
      <p class="mb-0 text-muted">Monitor seluruh barang yang sudah kembali dan eksport untuk dokumentasi.</p>
    </div>
    <div class="report-actions">
      <span class="summary-pill">Periode: {{ $summary['periode'] }}</span>
      <a href="{{ route('reports.returns.pdf', request()->all()) }}" class="btn btn-outline-success btn-sm">Download PDF</a>
      <a href="{{ route('reports.returns.excel', request()->all()) }}" class="btn btn-success btn-sm text-white">Download Excel</a>
    </div>
  </section>

  <section class="report-filter">
    <form method="GET" class="row g-2">
      <div class="col-md-3">
        <label>Rentang</label>
        <select name="range" class="form-select" id="rangeSelect">
          @foreach(['week' => '7 Hari','month' => '30 Hari','year' => 'Tahun Ini','custom' => 'Kustom'] as $key => $label)
            <option value="{{ $key }}" {{ $rangeKey === $key ? 'selected' : '' }}>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label>Mulai</label>
        <input type="date" name="start" class="form-control" value="{{ request('start', $start->toDateString()) }}" data-range-input>
      </div>
      <div class="col-md-3">
        <label>Sampai</label>
        <input type="date" name="end" class="form-control" value="{{ request('end', $end->toDateString()) }}" data-range-input>
      </div>
      <div class="col-md-3">
        <label>Cari</label>
        <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="aset / peminjam">
      </div>
      <div class="col-md-3">
        <label>Unit Kerja</label>
        <select name="unit" class="form-select">
          <option value="">Semua</option>
          @foreach($units as $unit)
            <option value="{{ $unit }}" {{ request('unit') === $unit ? 'selected' : '' }}>{{ $unit }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3 align-self-end">
        <button type="submit" class="btn btn-success w-100 text-white">Terapkan</button>
      </div>
      <div class="col-md-3 align-self-end">
        <a href="{{ route('reports.returns') }}" class="btn btn-outline-secondary w-100">Reset</a>
      </div>
    </form>
  </section>

  <section class="report-table-card">
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          @php($sort = $sort ?? 'return_date_actual')
          @php($dir = $dir ?? 'desc')
          @php($link = function($key,$label) use ($sort,$dir) {
            $next = ($sort === $key && $dir === 'asc') ? 'desc' : 'asc';
            $q = array_merge(request()->all(), ['sort' => $key, 'dir' => $next]);
            $arrow = $sort === $key ? ($dir === 'asc' ? '▲' : '▼') : '';
            return '<a href="'.route('reports.returns',$q).'" class="text-decoration-none text-muted">'.$label.' '.$arrow.'</a>';
          })
          <tr>
            <th>{!! $link('loan_date','Tanggal Pinjam') !!}</th>
            <th>{!! $link('asset','Aset') !!}</th>
            <th>{!! $link('borrower_name','Peminjam') !!}</th>
            <th>{!! $link('unit','Unit') !!}</th>
            <th class="text-center">{!! $link('quantity','Jumlah') !!}</th>
            <th>{!! $link('return_date_planned','Rencana Kembali') !!}</th>
            <th>{!! $link('return_date_actual','Tanggal Kembali') !!}</th>
          </tr>
        </thead>
        <tbody>
          @forelse($records as $row)
            <tr>
              <td>{{ optional($row->loan_date)->format('Y-m-d') ?? '-' }}</td>
              <td>{{ $row->asset->name ?? '-' }}</td>
              <td>{{ $row->borrower_name }}</td>
              <td>{{ $row->unit ?? '-' }}</td>
              <td class="text-center">{{ $row->quantity }}</td>
              <td>{{ optional($row->return_date_planned)->format('Y-m-d') ?? '-' }}</td>
              <td>{{ optional($row->return_date_actual)->format('Y-m-d') ?? '-' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center text-muted py-4">Belum ada data.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="mt-3 d-flex justify-content-end">
      {{ $records->links() }}
    </div>
  </section>
</div>
@endsection

@push('scripts')
<script>
  (function () {
    const range = document.getElementById('rangeSelect');
    const toggleInputs = () => {
      const isCustom = range.value === 'custom';
      document.querySelectorAll('[data-range-input]').forEach(el => el.disabled = !isCustom);
    };
    range?.addEventListener('change', () => {
      toggleInputs();
      if(range.value !== 'custom') {
        document.querySelector('[name=\"start\"]').value = '';
        document.querySelector('[name=\"end\"]').value = '';
      }
    });
    toggleInputs();
  })();
</script>
@endpush
