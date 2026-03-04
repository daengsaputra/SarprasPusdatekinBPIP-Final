@php($title = 'Laporan')
@php($rows = $records ?? collect())
@php($type = $type ?? request('type', 'all'))
@php($range = request('range', 'month'))
@php($rangeLabel = $range === 'week' ? 'Seminggu' : ($range === 'year' ? 'Setahun' : 'Sebulan'))
@extends('layouts.app')

@section('content')
<main class="content-body">
<div class="container-fluid">
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h4 mb-0">Laporan Peminjaman & Pengembalian</h1>
  <div class="d-flex align-items-center gap-2">
    <div class="dropdown">
      <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        Periode: {{ $rangeLabel }}
      </button>
      <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item" href="{{ route('reports.index', array_merge(request()->all(), ['range' => 'week'])) }}">Seminggu</a></li>
        <li><a class="dropdown-item" href="{{ route('reports.index', array_merge(request()->all(), ['range' => 'month'])) }}">Sebulan</a></li>
        <li><a class="dropdown-item" href="{{ route('reports.index', array_merge(request()->all(), ['range' => 'year'])) }}">Setahun</a></li>
      </ul>
    </div>
    <a class="btn btn-outline-primary btn-sm" href="{{ route('reports.excel', request()->all()) }}">Export Excel</a>
    <a class="btn btn-primary btn-sm" href="{{ route('reports.pdf', request()->all()) }}">Download PDF</a>
  </div>
</div>

<form method="GET" class="row g-2 align-items-end mb-3">
  <input type="hidden" name="range" value="custom">
  <div class="col-md-2">
    <label class="form-label">Jenis Laporan</label>
    <select name="type" class="form-select">
      <option value="all" {{ $type==='all'?'selected':'' }}>Semua</option>
      <option value="loans" {{ $type==='loans'?'selected':'' }}>Peminjaman</option>
      <option value="returns" {{ $type==='returns'?'selected':'' }}>Pengembalian</option>
    </select>
  </div>
  <div class="col-md-2">
    <label class="form-label">Dari</label>
    <input type="date" name="start" value="{{ request('start', $start->toDateString()) }}" class="form-control">
  </div>
  <div class="col-md-2">
    <label class="form-label">Sampai</label>
    <input type="date" name="end" value="{{ request('end', $end->toDateString()) }}" class="form-control">
  </div>
  <div class="col-md-2">
    <label class="form-label">Cari</label>
    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="aset/peminjam">
  </div>
  <div class="col-md-2">
    <label class="form-label">Unit Kerja</label>
    <select name="unit" class="form-select">
      <option value="">Semua</option>
      @foreach(($units ?? config('bpip.units')) as $u)
        <option value="{{ $u }}" {{ request('unit')===$u?'selected':'' }}>{{ $u }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-2">
    <label class="form-label">Status</label>
    <select name="status" class="form-select">
      <option value="">Semua</option>
      <option value="borrowed" {{ request('status')==='borrowed'?'selected':'' }}>Dipinjam</option>
      <option value="partial" {{ request('status')==='partial'?'selected':'' }}>Sebagian</option>
      <option value="returned" {{ request('status')==='returned'?'selected':'' }}>Kembali</option>
    </select>
  </div>
  <div class="col-md-2">
    <button class="btn btn-primary w-100" type="submit">Terapkan</button>
  </div>
  <div class="col-md-2">
    <a class="btn btn-outline-secondary w-100" href="{{ route('reports.index') }}">Reset</a>
  </div>
</form>

<div class="row g-3 mb-3">
  <div class="col-md-3">
    <div class="card"><div class="card-body">
      <div class="text-muted small">Periode</div>
      <div class="fw-bold">{{ $summary['periode'] }}</div>
      <div class="small">{{ $summary['start'] }} s/d {{ $summary['end'] }}</div>
    </div></div>
  </div>
  <div class="col-md-3">
    <div class="card"><div class="card-body">
      <div class="text-muted small">Total Transaksi</div>
      <div class="fs-4 fw-bold">{{ $summary['total_transaksi'] }}</div>
    </div></div>
  </div>
  <div class="col-md-3">
    <div class="card"><div class="card-body">
      <div class="text-muted small">Total Jumlah Barang</div>
      <div class="fs-4 fw-bold">{{ $summary['total_jumlah'] }}</div>
    </div></div>
  </div>
  <div class="col-md-3">
    <div class="card"><div class="card-body">
      <div class="text-muted small">Total Sudah Kembali</div>
      <div class="fs-4 fw-bold">{{ $summary['total_dikembalikan'] }}</div>
    </div></div>
  </div>
</div>

<div class="table-responsive">
<table class="table table-striped align-middle">
  <thead>
  <tr>
    @php($sort=request('sort'))
    @php($dir=request('dir','desc'))
    @php($sortLink=function($key,$label) use($sort,$dir){
      $next = ($sort===$key && $dir==='asc') ? 'desc' : 'asc';
      $q = array_merge(request()->all(), ['sort'=>$key,'dir'=>$next]);
      $arrow = $sort===$key ? ($dir==='asc'?'↑':'↓') : '•';
      return '<a href="'.route('reports.index',$q).'" class="text-decoration-none">'.$label.' <span class="text-muted">'.$arrow.'</span></a>';
    })
    <th>{!! $sortLink('loan_date','Tanggal Pinjam') !!}</th>
    <th>{!! $sortLink('return_date_actual','Tanggal Kembali') !!}</th>
    <th>{!! $sortLink('asset','Aset') !!}</th>
    <th>{!! $sortLink('borrower_name','Peminjam') !!}</th>
    <th>{!! $sortLink('unit','Unit') !!}</th>
    <th>{!! $sortLink('quantity','Jumlah') !!}</th>
    <th>{!! $sortLink('status','Status') !!}</th>
  </tr>
  </thead>
  <tbody>
  @forelse($rows as $row)
    @php($statusMap = ['borrowed' => 'Dipinjam', 'partial' => 'Sebagian', 'returned' => 'Kembali'])
    <tr>
      <td>{{ $row->loan_date?->format('Y-m-d') ?? '-' }}</td>
      <td>{{ $row->return_date_actual?->format('Y-m-d') ?? '-' }}</td>
      <td>{{ ($row->asset->code ?? '-') }} - {{ ($row->asset->name ?? '-') }}</td>
      <td>{{ $row->borrower_name }}</td>
      <td>{{ $row->unit ?? '-' }}</td>
      <td>{{ $row->quantity }}</td>
      <td>
        @php($badge = $row->status === 'returned' ? 'text-bg-success' : ($row->status === 'partial' ? 'text-bg-info' : 'text-bg-warning'))
        <span class="badge {{ $badge }}">{{ $statusMap[$row->status] ?? $row->status }}</span>
      </td>
    </tr>
  @empty
    <tr><td colspan="7" class="text-center">Tidak ada data.</td></tr>
  @endforelse
  </tbody>
</table>
</div>

{{ $rows->links() }}
</div>
</main>
@endsection
