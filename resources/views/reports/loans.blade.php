@php($title = 'Laporan Peminjaman')
@extends('layouts.app')

@push('styles')
<style>
  body[data-theme="light"] { background: #f1f5ff; }
  .report-shell { display:flex; flex-direction:column; gap:1.5rem; padding-bottom:3rem; }
  .report-hero {
    display:flex; justify-content:space-between; flex-wrap:wrap; gap:1.5rem;
    padding:1.8rem 2rem; border-radius:32px;
    background:linear-gradient(120deg, rgba(59,130,246,0.15), #ffffff 60%);
    border:1px solid rgba(148,163,184,0.18); box-shadow:0 25px 45px rgba(15,23,42,0.12);
  }
  .report-hero__title { font-size:clamp(1.9rem,3vw,2.5rem); font-weight:700; color:#0f172a; }
  .report-range-buttons { display:flex; gap:0.5rem; flex-wrap:wrap; }
  .report-range-buttons a {
    border-radius:999px; border:1px solid rgba(148,163,184,0.4);
    padding:0.35rem 0.95rem; font-size:0.85rem; text-decoration:none; color:#475569;
  }
  .report-range-buttons a.active {
    background:#1d63ea; color:#fff; border-color:#1d63ea; box-shadow:0 12px 25px rgba(29,99,234,0.25);
  }
  .report-filter-card,
  .report-table-card {
    background:#fff; border-radius:28px; border:1px solid rgba(148,163,184,0.16);
    box-shadow:0 20px 45px rgba(15,23,42,0.08); padding:1.5rem 1.7rem;
  }
  .report-summary-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:1rem; }
  .report-summary-card { background:#fff; border-radius:22px; border:1px solid rgba(148,163,184,0.16); box-shadow:0 18px 38px rgba(15,23,42,0.08); padding:1.2rem 1.4rem; }
  .report-summary-label { text-transform:uppercase; letter-spacing:0.12em; font-size:0.72rem; color:#94a3b8; }
  .report-summary-value { font-size:1.8rem; font-weight:700; color:#0f172a; }
  .report-table-card table thead th { text-transform:uppercase; letter-spacing:0.08em; font-size:0.78rem; color:#64748b; }
  .report-table-card table tbody td { vertical-align:middle; }
  .report-download { border-radius:999px; padding:0.35rem 1rem; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:0.4rem; }
  .report-download span { background:rgba(255,255,255,0.35); width:1.4rem; height:1.4rem; border-radius:999px; display:inline-flex; align-items:center; justify-content:center; }
  @media (max-width: 992px) { body[data-theme="light"] main.container { margin-left:0!important; } .report-hero { flex-direction:column; } }
</style>
@endpush

@php($rangeActive = $range ?? request('range','week'))

@section('content')
<div class="report-shell">
  <section class="report-hero">
    <div>
      <p class="text-uppercase text-muted small mb-1" style="letter-spacing:0.25em;">Barang Peminjaman</p>
      <h1 class="report-hero__title">Laporan Peminjaman</h1>
      <p class="text-muted mb-0">Kelola rangkuman peminjaman dengan filter tanggal, unit kerja, dan ekspor PDF instan.</p>
    </div>
    <div class="report-hero__actions d-flex flex-column gap-2">
      <div class="report-range-buttons">
        @foreach(['week'=>'7 Hari','month'=>'30 Hari','year'=>'Tahun Ini'] as $key => $label)
          <a href="{{ route('reports.loans', array_merge(request()->except('page'), ['range'=>$key])) }}" class="{{ $rangeActive === $key ? 'active' : '' }}">{{ $label }}</a>
        @endforeach
      </div>
      <a class="pill-btn pill-btn--primary report-download" href="{{ route('reports.loans.pdf', request()->all()) }}">Download PDF <span>&rsaquo;</span></a>
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
      <div class="text-muted small">Peminjaman tercatat.</div>
    </div>
    <div class="report-summary-card">
      <div class="report-summary-label">Total Jumlah</div>
      <div class="report-summary-value">{{ $summary['total_jumlah'] }}</div>
      <div class="text-muted small">Unit barang dipinjam.</div>
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
            return '<a href="'.route('reports.loans',$q).'" class="text-decoration-none text-muted">'.$label.' <span class="small">'.$arrow.'</span></a>';
          })
          <th>{!! $link('loan_date','Tanggal Pinjam') !!}</th>
          <th>{!! $link('asset','Aset') !!}</th>
          <th>{!! $link('borrower_name','Peminjam') !!}</th>
          <th>{!! $link('quantity','Jumlah') !!}</th>
          <th>{!! $link('status','Status') !!}</th>
        </tr>
        </thead>
        <tbody>
        @forelse($loans as $row)
          <tr>
            <td>{{ $row->loan_date?->format('Y-m-d') }}</td>
            <td>{{ $row->asset->code }} - {{ $row->asset->name }}</td>
            <td>{{ $row->borrower_name }}</td>
            <td>
              {{ $row->quantity }}
              @if($row->quantity_remaining > 0 && $row->status !== 'returned')
                <span class="text-muted small d-block">Sisa {{ $row->quantity_remaining }}</span>
              @endif
            </td>
            @php($statusLabel = match($row->status) {
              'borrowed' => 'Dipinjam',
              'partial' => 'Dikembalikan sebagian',
              'returned' => 'Sudah kembali',
              default => $row->status
            })
            @php($badgeClass = match($row->status) {
              'borrowed' => 'text-bg-warning',
              'partial' => 'text-bg-info',
              default => 'text-bg-success'
            })
            <td><span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span></td>
          </tr>
        @empty
          <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada data.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
    <div class="d-flex justify-content-end mt-3">
      {{ $loans->links() }}
    </div>
  </section>
</div>
@endsection
