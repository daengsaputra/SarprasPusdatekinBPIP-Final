@php($title = 'Laporan Kehilangan')
@extends('layouts.app')

@push('styles')
<style>
  body[data-theme="light"] { background: #eef2ff; }
  .report-shell { display:flex; flex-direction:column; gap:1.5rem; padding-bottom:3rem; }
  .report-hero { display:flex; justify-content:space-between; gap:1.5rem; flex-wrap:wrap; padding:1.8rem 2rem; border-radius:32px; background:linear-gradient(120deg, rgba(248,113,113,0.18), #ffffff 65%); border:1px solid rgba(248,113,113,0.35); box-shadow:0 25px 45px rgba(15,23,42,0.12); }
  .report-hero__title { font-size:clamp(1.8rem,3vw,2.4rem); font-weight:700; color:#0f172a; }
  .report-range-buttons { display:flex; gap:0.5rem; flex-wrap:wrap; }
  .report-summary-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:1rem; }
  .report-summary-card { background:#fff; border-radius:24px; border:1px solid rgba(148,163,184,0.18); box-shadow:0 18px 38px rgba(15,23,42,0.08); padding:1.2rem 1.4rem; }
  .report-summary-label { text-transform:uppercase; letter-spacing:0.12em; font-size:0.72rem; color:#94a3b8; }
  .report-summary-value { font-size:1.8rem; font-weight:700; color:#0f172a; }
  .report-filter-card,
  .report-table-card { background:#fff; border-radius:28px; border:1px solid rgba(148,163,184,0.16); box-shadow:0 20px 45px rgba(15,23,42,0.08); padding:1.5rem 1.7rem; }
  .report-table-card table thead th { text-transform:uppercase; letter-spacing:0.08em; font-size:0.78rem; color:#64748b; }
  .report-download { border-radius:999px; padding:0.35rem 1rem; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:0.4rem; color:#b45309; border:1px solid rgba(245,158,11,0.4); }
  .chip-warning { display:inline-flex; align-items:center; gap:0.25rem; padding:0.2rem 0.55rem; border-radius:999px; font-size:0.75rem; background:rgba(248,113,113,0.15); color:#b91c1c; font-weight:600; }
  @media (max-width: 992px) { body[data-theme="light"] .app-main { margin-left:0!important; } .report-hero { flex-direction:column; } }
</style>
@endpush

@section('content')
@php($rangeActive = $range ?? request('range','week'))
<div class="report-shell">
  @include('reports.partials.subnav')

  <section class="report-hero">
    <div>
      <p class="text-uppercase text-muted small mb-1" style="letter-spacing:0.25em;">Sub Laporan</p>
      <h1 class="report-hero__title">Laporan Kehilangan</h1>
      <p class="text-muted mb-0">Pantau daftar pinjaman yang belum kembali tuntas dan segera tindak lanjuti barang hilang.</p>
    </div>
    <div class="d-flex flex-column gap-2">
      <div class="report-range-buttons d-flex gap-2 flex-wrap">
        @foreach(['week'=>'7 Hari','month'=>'30 Hari','year'=>'Tahun Ini'] as $key => $labelRange)
          <a href="{{ route('reports.losses', array_merge(request()->except('page'), ['range'=>$key])) }}" class="pill-btn {{ $rangeActive === $key ? 'pill-btn--primary' : 'pill-btn--outline' }}">{{ $labelRange }}</a>
        @endforeach
      </div>
      <span class="chip-warning">Barang belum kembali otomatis tercatat berdasarkan selisih jumlah</span>
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
      <div class="report-summary-label">Total Kasus</div>
      <div class="report-summary-value">{{ $summary['total_transaksi'] }}</div>
      <div class="text-muted small">Pinjaman belum kembali tuntas.</div>
    </div>
    <div class="report-summary-card">
      <div class="report-summary-label">Total Barang Hilang</div>
      <div class="report-summary-value">{{ $summary['total_hilang'] }}</div>
      <div class="text-muted small">Unit belum kembali.</div>
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
              $arrow = $sort===$key ? ($dir==='asc'?'▲':'▼') : '◦';
              return '<a href="'.route('reports.losses',$q).'" class="text-decoration-none text-muted">'.$label.' <span class="small">'.$arrow.'</span></a>';
            })
            <th>{!! $link('loan_date','Tanggal Pinjam') !!}</th>
            <th>{!! $link('asset','Aset') !!}</th>
            <th>{!! $link('borrower_name','Peminjam') !!}</th>
            <th>{!! $link('unit','Unit') !!}</th>
            <th>Jumlah Pinjam</th>
            <th>{!! $link('missing','Belum Kembali') !!}</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
        @forelse($losses as $row)
          @php($assetCode = $row->asset->code ?? '-')
          @php($assetName = $row->asset->name ?? '-')
          @php($missingQty = $row->quantity_remaining)
          <tr>
            <td>{{ $row->loan_date?->format('Y-m-d') }}</td>
            <td>{{ $assetCode }} &ndash; {{ $assetName }}</td>
            <td>{{ $row->borrower_name }}</td>
            <td>{{ $row->unit ?? 'Tidak diisi' }}</td>
            <td>{{ $row->quantity }}</td>
            <td><span class="badge text-bg-warning">{{ $missingQty }}</span></td>
            @php($statusLabel = match($row->status){
              'borrowed' => 'Belum kembali',
              'partial' => 'Kembali sebagian',
              'returned' => 'Selesai',
              default => ucfirst($row->status)
            })
            <td>{{ $statusLabel }}</td>
          </tr>
        @empty
          <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada data kehilangan dalam periode ini.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
    <div class="d-flex justify-content-end mt-3">
      {{ $losses->links() }}
    </div>
  </section>
</div>
@endsection
