@php($title = 'Daftar Peminjaman')
@extends('layouts.app')

@push('styles')
<style>
  body[data-theme="light"] {
    background: #edf2ff;
  }
  .loan-shell {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    font-size: 0.75rem;
  }
  .loan-hero {
    display: flex;
    justify-content: space-between;
    gap: 2rem;
    padding: 1.8rem 2rem;
    border-radius: 30px;
    background: linear-gradient(120deg, rgba(59,130,246,0.16), rgba(255,255,255,0.95));
    border: 1px solid rgba(148,163,184,0.18);
    box-shadow: 0 25px 50px rgba(15,23,42,0.12);
  }
  .loan-hero__title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 0.4rem;
  }
  .loan-summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
  }
  .loan-summary-card {
    background: #fff;
    border-radius: 22px;
    border: 1px solid rgba(148,163,184,0.16);
    padding: 1.2rem 1.4rem;
    box-shadow: 0 16px 35px rgba(15,23,42,0.08);
  }
  .loan-summary-label {
    text-transform: uppercase;
    letter-spacing: 0.12em;
    font-size: 0.75rem;
    color: #94a3b8;
  }
  .loan-summary-value {
    font-size: 0.75rem;
    font-weight: 700;
    color: #0f172a;
  }
  .loan-summary-card .loan-summary-desc {
    font-size: 0.75rem;
  }
  .loan-filter-card {
    background: #fff;
    border-radius: 28px;
    border: 1px solid rgba(148,163,184,0.16);
    box-shadow: 0 18px 40px rgba(15,23,42,0.08);
    padding: 1.5rem 1.7rem;
  }
  .loan-filter-card .form-label,
  .loan-filter-card .form-control,
  .loan-filter-card .form-select,
  .loan-filter-card .btn,
  .loan-filter-card .form-check-label {
    font-size: 0.75rem;
  }
  .loan-table-card {
    background: #fff;
    border-radius: 28px;
    border: 1px solid rgba(148,163,184,0.16);
    box-shadow: 0 25px 45px rgba(15,23,42,0.08);
    padding: 1.2rem;
  }
  .loan-table-card table th {
    text-transform: uppercase;
    letter-spacing: 0.08em;
    font-size: 0.75rem;
    color: #64748b;
  }
  .loan-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.35rem;
  }
  .loan-alert {
    border-radius: 18px;
    border: 1px solid rgba(59,130,246,0.25);
    background: rgba(59,130,246,0.08);
    padding: 0.9rem 1.2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: #0f172a;
  }
  .loan-alert.alert-info {
    border-color: rgba(14,165,233,0.35);
    background: rgba(224,242,254,0.9);
  }
  .loan-alert.alert-success {
    border-color: rgba(16,185,129,0.35);
    background: rgba(209,250,229,0.85);
  }
  .letter-spacing-wide {
    letter-spacing: 0.25em;
  }
  .loan-table-card .table {
    margin-bottom: 0;
  }
  .loan-table-card .table tbody tr td {
    vertical-align: middle;
  }
  .loan-table-card .badge {
    font-size: 0.8125rem;
  }
  .loan-table-pagination {
    margin-top: 1rem;
    display: flex;
    justify-content: flex-end;
  }
  @media (max-width: 992px) {
    .loan-hero { flex-direction: column; }
    body[data-theme="light"] .app-main { margin-left: 0 !important; }
  }
</style>
@endpush

@section('content')
<div class="loan-shell">
  @if(session('receipt_batch'))
    @php($batch = session('receipt_batch'))
    <div class="loan-alert alert-success">
      <div>Peminjaman berhasil. Bukti peminjaman siap dicetak.</div>
      <div class="loan-actions">
        <a class="btn btn-sm btn-primary" target="_blank" href="{{ route('loans.receipt', ['batch' => $batch, 'preview' => 1]) }}">Cetak Bukti</a>
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="this.closest('.loan-alert').remove()">Tutup</button>
      </div>
    </div>
  @endif
  @if(session('return_receipt_id'))
    @php($rid = session('return_receipt_id'))
    <div class="loan-alert alert-info">
      <div>Pengembalian tercatat. Bukti pengembalian siap dicetak.</div>
      <div class="loan-actions">
        <a class="btn btn-sm btn-primary" target="_blank" href="{{ route('loans.return.receipt', ['loan' => $rid, 'preview' => 1]) }}">Cetak Bukti</a>
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="this.closest('.loan-alert').remove()">Tutup</button>
      </div>
    </div>
  @endif

  <section class="loan-hero">
    <div>
      <p class="text-uppercase text-muted small mb-1 letter-spacing-wide">Peminjaman</p>
      <h1 class="loan-hero__title">Kelola Daftar Peminjaman Sarpras</h1>
      <p class="mb-0 text-muted">Pantau status terbaru peminjaman, filter cepat berdasarkan unit, dan akses bukti dalam satu tampilan modern.</p>
    </div>
    <div class="d-flex flex-column align-items-end gap-2">
      <a href="{{ route('loans.create') }}" class="btn btn-primary px-4">+ Peminjaman Baru</a>
      <a href="{{ route('assets.loanable') }}" class="btn btn-outline-primary px-4">Lihat Sarpras</a>
    </div>
  </section>

  <section class="loan-filter-card">
    <form method="GET" class="row g-3 align-items-end">
      <div class="col-md-3">
        <label class="form-label text-uppercase small fw-semibold letter-spacing-wide">Cari</label>
        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="aset / peminjam">
      </div>
      <div class="col-md-2">
        <label class="form-label text-uppercase small fw-semibold letter-spacing-wide">Status</label>
        <select name="status" class="form-select">
          <option value="">Semua</option>
          <option value="borrowed" {{ request('status')==='borrowed'?'selected':'' }}>Sedang dipinjam</option>
          <option value="partial" {{ request('status')==='partial'?'selected':'' }}>Dikembalikan sebagian</option>
          <option value="returned" {{ request('status')==='returned'?'selected':'' }}>Sudah dikembalikan</option>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label text-uppercase small fw-semibold letter-spacing-wide">Unit Kerja</label>
        <select name="unit" class="form-select">
          <option value="">Semua</option>
          @foreach(($units ?? config('bpip.units')) as $u)
            <option value="{{ $u }}" {{ request('unit')===$u?'selected':'' }}>{{ $u }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label text-uppercase small fw-semibold letter-spacing-wide">Dari</label>
        <input type="date" name="from" value="{{ request('from') }}" class="form-control">
      </div>
      <div class="col-md-2">
        <label class="form-label text-uppercase small fw-semibold letter-spacing-wide">Sampai</label>
        <input type="date" name="to" value="{{ request('to') }}" class="form-control">
      </div>
      <div class="col-md-2">
        <button class="btn btn-primary w-100" type="submit">Terapkan</button>
      </div>
      <div class="col-md-2">
        <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
      </div>
    </form>
  </section>

  <section class="loan-table-card">
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
              return '<a href="'.route('loans.index',$q).'" class="text-decoration-none text-muted">'.$label.' <span class="small">'.$arrow.'</span></a>';
            })
            <th>{!! $link('loan_date','Tanggal') !!}</th>
            <th>{!! $link('asset','Aset') !!}</th>
            <th>{!! $link('borrower_name','Peminjam') !!}</th>
            <th class="text-center">{!! $link('quantity','Jumlah') !!}</th>
            <th>{!! $link('status','Status') !!}</th>
            <th>{!! $link('return_date_planned','Rencana Kembali') !!}</th>
            <th>{!! $link('return_date_actual','Kembali') !!}</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($loans as $loan)
            <tr>
              <td>{{ $loan->loan_date?->format('Y-m-d') }}</td>
              <td>{{ $loan->asset->name }}</td>
              <td>{{ $loan->borrower_name }}</td>
              <td class="text-center">
                <strong>{{ $loan->quantity }}</strong>
              </td>
              <td>
                @php($statusLabel = match($loan->status) {
                  'borrowed' => 'Dipinjam',
                  'partial' => 'Dikembalikan sebagian',
                  'returned' => 'Sudah kembali',
                  default => $loan->status
                })
                @php($badgeClass = match($loan->status) {
                  'borrowed' => 'bg-warning text-dark',
                  'partial' => 'bg-info text-dark',
                  default => 'bg-success'
                })
                <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
              </td>
              <td>{{ $loan->return_date_planned?->format('Y-m-d') ?? '-' }}</td>
              <td>{{ $loan->return_date_actual?->format('Y-m-d') ?? '-' }}</td>
              <td>
                <div class="loan-actions">
                  @if($loan->status==='returned')
                    <form method="POST" action="{{ route('loans.destroy', $loan) }}" onsubmit="return confirm('Hapus data peminjaman ini?')" class="d-inline">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                    </form>
                    <a href="{{ route('loans.receipt', ['batch' => $loan->batch_code, 'preview' => 1]) }}" class="btn btn-sm btn-outline-secondary">Bukti Pinjam</a>
                    <a href="{{ route('loans.return.receipt', ['loan' => $loan, 'preview' => 1]) }}" class="btn btn-sm btn-outline-secondary">Bukti Kembali</a>
                  @else
                    @if(in_array($loan->status,['borrowed','partial']))
                      <a href="{{ route('loans.return.form', $loan) }}" class="btn btn-sm btn-outline-primary">Kembalikan</a>
                    @endif
                    @if($loan->batch_code)
                      <a href="{{ route('loans.receipt', ['batch' => $loan->batch_code, 'preview' => 1]) }}" class="btn btn-sm btn-outline-secondary">Bukti Pinjam</a>
                    @endif
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data peminjaman.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="loan-table-pagination">
      {{ $loans->links() }}
    </div>
  </section>
</div>
@endsection

@push('scripts')
@if(session('receipt_batch'))
<script>
  // Auto open print-ready PDF in a new tab
  (function(){
    var url = @json(route('loans.receipt', ['batch' => session('receipt_batch'), 'preview' => 1]));
    // slight delay to ensure page renders before opening
    setTimeout(function(){ window.open(url, '_blank'); }, 300);
  })();
  </script>
@endif
@if(session('return_receipt_id'))
<script>
  (function(){
    var url = @json(route('loans.return.receipt', ['loan' => session('return_receipt_id'), 'preview' => 1]));
    setTimeout(function(){ window.open(url, '_blank'); }, 300);
  })();
  </script>
@endif
@endpush
