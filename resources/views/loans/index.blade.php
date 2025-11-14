@php($title = 'Daftar Peminjaman')
@extends('layouts.app')

@push('styles')
<style>
  body[data-theme="light"] { background:#eef2ff; }
  .loan-shell { display:flex; flex-direction:column; gap:1.5rem; padding-bottom:3rem; }
  .loan-hero { display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.9rem; padding:1.35rem 1.6rem; border-radius:24px; background:linear-gradient(120deg, rgba(59,130,246,0.12), #ffffff 70%); border:1px solid rgba(148,163,184,0.1); box-shadow:0 12px 35px rgba(15,23,42,0.08); }
  .loan-hero__title { font-size:clamp(1.15rem,2.2vw,1.65rem); font-weight:700; color:#0f172a; margin-bottom:0.2rem; }
  .loan-hero__subtitle { color:#475569; font-size:0.9rem; }
  .loan-hero__cta { display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap; margin-top:0.85rem; }
  .loan-hero__cta small { color:#64748b; font-weight:600; letter-spacing:0.08em; text-transform:uppercase; }
  .loan-summary-card { background:#fff; border-radius:18px; border:1px solid rgba(148,163,184,0.16); box-shadow:0 14px 32px rgba(15,23,42,0.08); padding:0.9rem 1.2rem; min-width:160px; }
  .loan-summary-label { text-transform:uppercase; letter-spacing:0.15em; font-size:0.62rem; color:#94a3b8; }
  .loan-summary-value { font-size:1.35rem; font-weight:700; color:#0f172a; }
  .loan-filter-card, .loan-table-card { background:#fff; border-radius:28px; border:1px solid rgba(148,163,184,0.16); box-shadow:0 20px 45px rgba(15,23,42,0.08); padding:1.5rem 1.7rem; }
  .loan-table-card { padding:1.2rem 1.4rem; }
  .loan-group { border:1px solid rgba(226,232,240,0.9); border-radius:24px; padding:1rem 1.3rem; margin-bottom:1rem; background:#fafbff; box-shadow:0 18px 35px rgba(15,23,42,0.06); }
  .loan-group__meta { display:grid; grid-template-columns:repeat(auto-fit,minmax(170px,1fr)); gap:0.5rem 1.5rem; padding-bottom:1rem; border-bottom:1px solid rgba(148,163,184,0.25); }
  .loan-group__meta-item { display:flex; flex-direction:column; gap:0.12rem; }
  .loan-group__meta-label { text-transform:uppercase; font-size:0.68rem; letter-spacing:0.12em; color:#94a3b8; }
  .loan-group__meta-value { font-size:0.95rem; font-weight:700; color:#0f172a; white-space:nowrap; }
  .loan-group__meta-value.is-wrap { white-space:normal; font-weight:600; line-height:1.4; }
  .loan-attachments { display:flex; flex-wrap:wrap; gap:0.4rem; }
  .loan-attachment-chip { display:inline-flex; align-items:center; gap:0.25rem; padding:0.22rem 0.75rem; border-radius:999px; border:1px solid rgba(59,130,246,0.35); font-size:0.72rem; text-decoration:none; color:#2563eb; background:rgba(59,130,246,0.08); transition:transform .25s cubic-bezier(0.34,1.56,0.64,1); transform:scale(0.96); }
  .loan-attachment-chip:hover { transform:scale(1); border-color:rgba(59,130,246,0.55); box-shadow:0 8px 18px rgba(59,130,246,0.2); }
  .loan-actions { display:flex; flex-wrap:wrap; gap:0.35rem; }
  .loan-alert { border-radius:18px; border:1px solid rgba(59,130,246,0.25); background:rgba(59,130,246,0.08); padding:0.9rem 1.2rem; display:flex; justify-content:space-between; align-items:center; color:#0f172a; }
  .loan-alert.alert-success { border-color:rgba(16,185,129,0.35); background:rgba(209,250,229,0.9); }
  .letter-spacing-wide { letter-spacing:0.22em; }
  .loan-table-card table { width:100%; border-collapse:separate; border-spacing:0; table-layout:auto; }
  .loan-table-card table thead th { text-transform:uppercase; letter-spacing:0.08em; color:#64748b; font-size:0.76rem; white-space:nowrap; }
  .loan-table-card table th,
  .loan-table-card table td { padding:0.65rem 0.75rem; }
  .loan-table-card table tbody td { vertical-align:top; }
  .loan-table-card table td[rowspan] { border-bottom:none; }
  .loan-asset-cell { min-width:200px; }
  .loan-asset-name { display:block; font-weight:600; color:#0f172a; white-space:normal; word-break:break-word; }
  .loan-asset-code { display:block; font-size:0.78rem; color:#64748b; }
  .loan-table-pagination { margin-top:1.2rem; display:flex; justify-content:flex-end; }
  .loan-attachment-modal { position:fixed; inset:0; background:rgba(15,23,42,0.65); display:flex; justify-content:center; align-items:center; padding:1.5rem; opacity:0; pointer-events:none; transition:opacity .25s ease; z-index:2000; }
  .loan-attachment-modal.is-visible { opacity:1; pointer-events:auto; }
  .loan-attachment-modal__body { background:#fff; border-radius:24px; padding:1rem; box-shadow:0 30px 70px rgba(15,23,42,0.25); max-width:640px; width:100%; transform:scale(0.88); transition:transform .32s cubic-bezier(0.34,1.56,0.64,1); }
  .loan-attachment-modal.is-visible .loan-attachment-modal__body { transform:scale(1); }
  .loan-attachment-modal__label { text-align:center; font-size:16px; font-weight:700; color:#0f172a; margin-bottom:0.5rem; }
  .loan-attachment-modal__body img { width:100%; height:auto; border-radius:16px; display:block; }
  .loan-attachment-modal__close { border:none; background:transparent; font-size:1.6rem; line-height:1; color:#475569; margin-bottom:0.5rem; cursor:pointer; display:inline-flex; align-items:center; justify-content:flex-end; width:100%; }
  @media (max-width: 768px) {
    .loan-hero { flex-direction:column; }
    .loan-group__header { flex-direction:column; }
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

  <section class="loan-hero">
    <div>
      <div class="loan-hero__title">Daftar Peminjaman</div>
      <div class="loan-hero__subtitle">Pantau setiap aset yang keluar-masuk beserta bukti fotonya.</div>
      <div class="loan-hero__cta">
        <a href="{{ route('loans.create') }}" class="btn btn-primary px-4 d-flex align-items-center gap-2">
          <span class="fs-5">+</span>
          <span>Tambah Peminjaman</span>
        </a>
        <small>Proses batch baru & cetak bukti langsung</small>
      </div>
    </div>
    <div class="loan-actions">
      <div class="loan-summary-card">
        <div class="loan-summary-label">Total peminjaman</div>
        <div class="loan-summary-value">{{ number_format($totalLoanCount) }}</div>
      </div>
      <div class="loan-summary-card">
        <div class="loan-summary-label">Sedang berjalan</div>
        <div class="loan-summary-value">{{ number_format($activeLoanCount) }}</div>
      </div>
      <div class="loan-summary-card">
        <div class="loan-summary-label">Terlambat</div>
        <div class="loan-summary-value text-danger">{{ number_format($overdueCount) }}</div>
      </div>
    </div>
  </section>

  <section class="loan-filter-card">
    <form action="{{ route('loans.index') }}" method="GET" class="row g-3 align-items-end">
      <div class="col-md-3">
        <label class="form-label text-uppercase small fw-semibold letter-spacing-wide">Pencarian</label>
        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Nama peminjam / aset">
      </div>
      <div class="col-md-2">
        <label class="form-label text-uppercase small fw-semibold letter-spacing-wide">Status</label>
        <select name="status" class="form-select">
          <option value="">Semua</option>
          @foreach(['borrowed' => 'Dipinjam', 'partial' => 'Sebagian', 'returned' => 'Kembali'] as $key => $label)
            <option value="{{ $key }}" {{ request('status')===$key?'selected':'' }}>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label text-uppercase small fw-semibold letter-spacing-wide">Unit</label>
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
      <div class="col-md-1 d-grid">
        <button class="btn btn-primary" type="submit">Cari</button>
      </div>
      <div class="col-md-1 d-grid">
        <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary">Reset</a>
      </div>
    </form>
  </section>

  <section class="loan-table-card">
    @php($groupedLoans = $loans->groupBy(fn($loan) => $loan->batch_code ?? 'loan-'.$loan->id))
    <div class="loan-groups">
      @forelse($groupedLoans as $batchCode => $batchLoans)
        @php($firstLoan = $batchLoans->first())
        @php($attachments = [
          'ND / Helpdesk' => $firstLoan->request_photo_path,
          'Serah Terima' => $firstLoan->loan_photo_path,
          'Pengembalian' => $firstLoan->return_photo_path,
        ])
        @php($sequenceNumber = 'P' . str_pad($loop->iteration, 4, '0', STR_PAD_LEFT))
        <article class="loan-group">
          <div class="loan-group__meta">
            <div class="loan-group__meta-item">
              <span class="loan-group__meta-label">ID Peminjaman</span>
              <span class="loan-group__meta-value">{{ $sequenceNumber }}</span>
            </div>
            <div class="loan-group__meta-item">
              <span class="loan-group__meta-label">Nama Peminjam</span>
              <span class="loan-group__meta-value">{{ $firstLoan->borrower_name }}</span>
            </div>
            <div class="loan-group__meta-item">
              <span class="loan-group__meta-label">Nama Kegiatan</span>
              <span class="loan-group__meta-value is-wrap">{{ $firstLoan->activity_name ?: '-' }}</span>
            </div>
            <div class="loan-group__meta-item">
              <span class="loan-group__meta-label">Tanggal Pinjam</span>
              <span class="loan-group__meta-value">{{ $firstLoan->loan_date?->format('d M Y') ?? '-' }}</span>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table align-middle mb-0">
              <thead>
                <tr>
                  <th>Aset</th>
                  <th>Status</th>
                  <th>Rencana Kembali</th>
                  <th>Lampiran</th>
                  <th>Pengembalian</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($batchLoans as $loan)
                  @php($statusLabel = match($loan->status) {
                    'borrowed' => 'Dipinjam',
                    'partial' => 'Sebagian',
                    'returned' => 'Kembali',
                    default => ucfirst($loan->status)
                  })
                  @php($badgeClass = match($loan->status) {
                    'borrowed' => 'bg-warning text-dark',
                    'partial' => 'bg-info text-dark',
                    'returned' => 'bg-success',
                    default => 'bg-secondary'
                  })
                  <tr>
                    <td class="loan-asset-cell">
                      <span class="loan-asset-name" title="{{ $loan->asset->name ?? '-' }}">{{ $loan->asset->name ?? '-' }}</span>
                      @if($loan->asset->code)
                        <span class="loan-asset-code">{{ $loan->asset->code }}</span>
                      @endif
                    </td>
                    <td><span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span></td>
                    <td>{{ $loan->return_date_planned?->format('d M Y') ?? '-' }}</td>
                    @if($loop->first)
                      <td rowspan="{{ $batchLoans->count() }}">
                        <div class="loan-attachments">
                          @foreach($attachments as $label => $path)
                            @if($path)
                              <button type="button" class="loan-attachment-chip" data-attachment="{{ asset('storage/'.$path) }}" data-label="{{ $label }}">Foto {{ $label }}</button>
                            @endif
                          @endforeach
                        </div>
                      </td>
                    @endif
                    <td>{{ $loan->return_date_actual?->format('d M Y') ?? '-' }}</td>
                    <td>
                      <div class="loan-actions">
                        @if($loan->status!=='returned')
                          <a href="{{ route('loans.return.form', $loan) }}" class="btn btn-sm btn-outline-primary">Kembalikan</a>
                        @else
                          <a href="{{ route('loans.return.receipt', ['loan' => $loan, 'preview' => 1]) }}" class="btn btn-sm btn-outline-secondary">Bukti Kembali</a>
                        @endif
                        @if($loan->batch_code)
                          <a href="{{ route('loans.receipt', ['batch' => $loan->batch_code, 'preview' => 1]) }}" class="btn btn-sm btn-outline-secondary">Bukti Pinjam</a>
                        @else
                          <a href="{{ route('loans.receipt', ['batch' => $loan->batch_code ?? $loan->id, 'preview' => 1]) }}" class="btn btn-sm btn-outline-secondary">Cetak</a>
                        @endif
                        @if($loan->status==='returned')
                          <form method="POST" action="{{ route('loans.destroy', $loan) }}" onsubmit="return confirm('Hapus data peminjaman ini?')" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                          </form>
                        @endif
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </article>
      @empty
        <div class="text-center text-muted py-4">Belum ada data peminjaman.</div>
      @endforelse
    </div>
    <div class="loan-table-pagination">
      {{ $loans->links() }}
    </div>
  </section>
</div>

<div class="loan-attachment-modal" id="loanAttachmentModal" aria-hidden="true">
  <div class="loan-attachment-modal__body">
    <button type="button" class="loan-attachment-modal__close" data-attachment-close>&times;</button>
    <div class="loan-attachment-modal__label" id="attachmentModalLabel">Lampiran</div>
    <img src="" alt="Lampiran" id="attachmentModalImage">
  </div>
</div>
@endsection

@push('scripts')
@if(session('receipt_batch'))
<script>
  (function(){
    var url = @json(route('loans.receipt', ['batch' => session('receipt_batch'), 'preview' => 1]));
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
<script>
  (function(){
    const modal = document.getElementById('loanAttachmentModal');
    if(!modal) return;
    const labelEl = document.getElementById('attachmentModalLabel');
    const imgEl = document.getElementById('attachmentModalImage');

    function openModal(src, label){
      imgEl.src = src;
      imgEl.alt = label;
      labelEl.textContent = label;
      modal.classList.add('is-visible');
    }

    function closeModal(){
      modal.classList.remove('is-visible');
      imgEl.removeAttribute('src');
      imgEl.alt = '';
    }

    document.addEventListener('click', function(e){
      const chip = e.target.closest('[data-attachment]');
      if(chip){
        e.preventDefault();
        openModal(chip.dataset.attachment, chip.dataset.label || 'Lampiran');
        return;
      }
      if(e.target.closest('[data-attachment-close]') || e.target === modal){
        closeModal();
      }
    });

    document.addEventListener('keyup', function(e){
      if(e.key === 'Escape'){
        closeModal();
      }
    });
  })();
</script>
@endpush
