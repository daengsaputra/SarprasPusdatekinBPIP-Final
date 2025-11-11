@php($title = 'Laporan Pengembalian')
@extends('layouts.app')

@push('styles')
<style>
  body[data-theme="light"] {
    background: #f1f5ff;
  }
  .report-shell {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    padding-bottom: 3rem;
  }
  .report-hero {
    display: flex;
    justify-content: space-between;
    gap: 1.5rem;
    padding: 1.8rem 2rem;
    border-radius: 30px;
    background: linear-gradient(120deg, rgba(14,165,233,0.18), #ffffff 60%);
    border: 1px solid rgba(148,163,184,0.2);
    box-shadow: 0 25px 50px rgba(15,23,42,0.12);
  }
  .report-hero__title {
    font-size: clamp(1.6rem, 3vw, 2.2rem);
    font-weight: 700;
    color: #0f172a;
    margin-bottom: .4rem;
  }
  .report-hero__desc {
    color: #475569;
    max-width: 520px;
  }
  .report-hero__actions {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    align-items: flex-end;
  }
  .report-range-buttons {
    display: inline-flex;
    gap: 0.5rem;
    flex-wrap: wrap;
  }
  .report-range-buttons a {
    border-radius: 999px;
    border: 1px solid rgba(148,163,184,0.4);
    padding: 0.35rem 0.95rem;
    font-size: 0.85rem;
    color: #475569;
    text-decoration: none;
  }
  .report-range-buttons a.active {
    background: #2563eb;
    color: #fff;
    border-color: #2563eb;
    box-shadow: 0 12px 20px rgba(37,99,235,0.25);
  }
  .report-summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1rem;
  }
  .report-summary-card {
    background: #fff;
    border-radius: 22px;
    border: 1px solid rgba(148,163,184,0.18);
    box-shadow: 0 16px 36px rgba(15,23,42,0.08);
    padding: 1.3rem 1.5rem;
  }
  .report-summary-label {
    text-transform: uppercase;
    letter-spacing: 0.12em;
    font-size: 0.72rem;
    color: #94a3b8;
  }
  .report-summary-value {
    font-size: 1.85rem;
    font-weight: 700;
    color: #0f172a;
  }
  .report-filter-card,
  .report-table-card {
    background: #fff;
    border-radius: 28px;
    border: 1px solid rgba(148,163,184,0.16);
    box-shadow: 0 20px 40px rgba(15,23,42,0.08);
    padding: 1.5rem 1.7rem;
  }
  .report-table-card table thead th {
    text-transform: uppercase;
    letter-spacing: 0.08em;
    font-size: 0.78rem;
    color: #64748b;
    border-bottom-width: 1px;
  }
  .report-table-card table tbody td {
    vertical-align: middle;
  }
  .report-download {
    display: inline-flex;
    gap: .5rem;
    align-items: center;
    font-size: 0.9rem;
    color: #2563eb;
    text-decoration: none;
  }
  @media (max-width: 992px) {
    .report-hero { flex-direction: column; }
    body[data-theme="light"] .app-main { margin-left: 0 !important; }
  }
</style>
@endpush

@section('content')
@php($rangeActive = $range ?? request('range','week'))
<div class="report-shell">
  @include('reports.partials.subnav')
  <section class="report-hero">
    <div>
      <p class="text-uppercase text-muted small mb-1" style="letter-spacing:0.25em;">Laporan</p>
      <h1 class="report-hero__title">Pengembalian Sarpras</h1>
      <p class="report-hero__desc">Pantau rangkuman pengembalian sarpras lengkap dengan filter rentang waktu, kata kunci, dan unit kerja.</p>
    </div>
    <div class="report-hero__actions">
      <div class="report-range-buttons">
        @foreach(['week'=>'7 Hari','month'=>'30 Hari','year'=>'Tahun Ini'] as $key => $label)
          <a href="{{ route('reports.returns', array_merge(request()->except('page'), ['range' => $key])) }}" class="{{ $rangeActive === $key ? 'active' : '' }}">{{ $label }}</a>
        @endforeach
      </div>
      <a class="report-download" href="{{ route('reports.returns.pdf', request()->all()) }}">Download PDF ▾</a>
    </div>
  </section>

  <section class="report-filter-card">
    <form method="GET" class="row g-3 align-items-end">
      <input type="hidden" name="range" value="custom">
      <div class="col-md-3">
        <label class="form-label text-uppercase small fw-semibold" style="letter-spacing:0.12em;">Dari</label>
        <input type="date" name="start" value="{{ request('start', $start->toDateString()) }}" class="form-control">
      </div>
      <div class="col-md-3">
        <label class="form-label text-uppercase small fw-semibold" style="letter-spacing:0.12em;">Sampai</label>
        <input type="date" name="end" value="{{ request('end', $end->toDateString()) }}" class="form-control">
      </div>
      <div class="col-md-3">
        <label class="form-label text-uppercase small fw-semibold" style="letter-spacing:0.12em;">Cari</label>
        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="aset / peminjam">
      </div>
      <div class="col-md-3">
        <label class="form-label text-uppercase small fw-semibold" style="letter-spacing:0.12em;">Unit Kerja</label>
        <select name="unit" class="form-select">
          <option value="">Semua</option>
          @foreach(($units ?? config('bpip.units')) as $u)
            <option value="{{ $u }}" {{ request('unit')===$u?'selected':'' }}>{{ $u }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <button class="btn btn-primary w-100" type="submit">Terapkan</button>
      </div>
    </form>
  </section>

  <section class="report-summary-grid">
    <div class="report-summary-card">
      <div class="report-summary-label">Periode</div>
      <div class="report-summary-value" style="font-size:1.3rem;">{{ $summary['periode'] }}</div>
      <div class="text-muted small">{{ $summary['start'] }} s/d {{ $summary['end'] }}</div>
    </div>
    <div class="report-summary-card">
      <div class="report-summary-label">Total Transaksi</div>
      <div class="report-summary-value">{{ $summary['total_transaksi'] }}</div>
      <div class="text-muted small">Pengembalian tercatat</div>
    </div>
    <div class="report-summary-card">
      <div class="report-summary-label">Total Jumlah</div>
      <div class="report-summary-value">{{ $summary['total_jumlah'] }}</div>
      <div class="text-muted small">Unit barang kembali</div>
    </div>
  </section>

  <section class="report-table-card">
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            @php($sort=request('sort'))
            @php($dir=request('dir','desc'))
            @php($link=function($key,$label) use($sort,$dir){
              $next = ($sort===$key && $dir==='asc') ? 'desc' : 'asc';
              $q = array_merge(request()->all(), ['sort'=>$key,'dir'=>$next]);
              $arrow = $sort===$key ? ($dir==='asc'?'▲':'▼') : '•';
              return '<a href="'.route('reports.returns',$q).'" class="text-decoration-none text-muted">'.$label.' <span class="small">'.$arrow.'</span></a>';
            })
            <th>{!! $link('return_date_actual','Tanggal Kembali') !!}</th>
            <th>{!! $link('asset','Aset') !!}</th>
            <th>{!! $link('borrower_name','Peminjam') !!}</th>
            <th>{!! $link('quantity','Jumlah') !!}</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
        @forelse($returns as $row)
          @php($assetCode = $row->asset->code ?? '-')
          @php($assetName = $row->asset->name ?? '-')
          <tr>
            <td>{{ $row->return_date_actual?->format('Y-m-d') }}</td>
            <td>{{ $assetCode }} &ndash; {{ $assetName }}</td>
            <td>{{ $row->borrower_name }}</td>
            <td>{{ $row->quantity }}</td>
            <td><a class="btn btn-sm btn-outline-secondary" target="_blank" href="{{ route('loans.return.receipt', ['loan' => $row->id, 'preview' => 1]) }}">Bukti Kembali</a></td>
          </tr>
        @empty
          <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada data.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
    <div class="d-flex justify-content-end mt-3">
      {{ $returns->links() }}
    </div>
  </section>
</div>
@endsection
