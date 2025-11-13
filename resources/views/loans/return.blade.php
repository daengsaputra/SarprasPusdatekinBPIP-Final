@php($title = 'Pengembalian Aset')
@extends('layouts.app')

@push('styles')
<style>
  .file-drop {
    border: 1px dashed rgba(148, 163, 184, 0.8);
    border-radius: 16px;
    padding: 1rem;
    text-align: center;
    background: rgba(248, 250, 252, 0.85);
    transition: border-color .2s ease, background .2s ease, box-shadow .2s ease;
    cursor: pointer;
  }
  .file-drop:hover { border-color: rgba(59, 130, 246, 0.8); }
  .file-drop.is-dragover {
    border-color: #2563eb;
    background: rgba(37, 99, 235, 0.06);
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.08) inset;
  }
  .file-drop.is-invalid { border-color: #dc3545; }
  .file-drop__input { display: none; }
  .file-drop__icon {
    width: 44px; height: 44px; border-radius: 12px;
    margin: 0 auto 0.5rem;
    display: flex; align-items:center; justify-content:center;
    background: rgba(16, 185, 129, 0.12); color:#047857;
  }
  .file-drop__filename { display:block; margin-top:0.3rem; color:#475569; font-size:0.9rem; }
</style>
@endpush

@section('content')
<h1 class="h4 mb-3">Pengembalian Aset</h1>

<div class="card mb-3">
  <div class="card-body">
    <div><strong>Aset:</strong> {{ $loan->asset->code }} - {{ $loan->asset->name }}</div>
    <div><strong>Peminjam:</strong> {{ $loan->borrower_name }} ({{ $loan->borrower_contact ?? '-' }})</div>
    <div><strong>Jumlah Dipinjam:</strong> {{ $loan->quantity }}</div>
    <div><strong>Sisa Belum Kembali:</strong> {{ $loan->quantity_remaining }}</div>
    <div><strong>Tanggal Pinjam:</strong> {{ $loan->loan_date?->format('Y-m-d') }}</div>
    <div><strong>Rencana Kembali:</strong> {{ $loan->return_date_planned?->format('Y-m-d') ?? '-' }}</div>
  </div>
  </div>

<form method="POST" action="{{ route('loans.return.update', $loan) }}" class="row g-3" enctype="multipart/form-data">
  @csrf
  <div class="col-md-4">
    <label class="form-label">Jumlah Dikembalikan</label>
    <input type="number" name="return_quantity" min="1" max="{{ $loan->quantity_remaining }}" value="{{ old('return_quantity', $loan->quantity_remaining) }}" class="form-control @error('return_quantity') is-invalid @enderror" required>
    @error('return_quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
    <div class="form-text">Maksimal {{ $loan->quantity_remaining }} unit.</div>
  </div>
  <div class="col-md-4">
    <label class="form-label">Tanggal Kembali</label>
    <input type="date" name="return_date_actual" value="{{ old('return_date_actual', now()->format('Y-m-d')) }}" class="form-control @error('return_date_actual') is-invalid @enderror" required>
    @error('return_date_actual')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-12">
    <label class="form-label">Foto Pengembalian</label>
    <div class="file-drop @error('return_photo') is-invalid @enderror" data-file-drop data-preview-label="Foto Pengembalian">
      <input type="file" name="return_photo" accept=".jpg,.jpeg,.png,.webp,image/*" class="file-drop__input" required>
      <div class="file-drop__body">
        <div class="file-drop__icon">📷</div>
        <strong>Tarik & lepaskan foto bukti pengembalian</strong>
        <div>atau klik untuk memilih dari komputer</div>
        <small class="file-drop__filename" data-file-drop-name>Belum ada file</small>
      </div>
    </div>
    <div class="form-text">Tambahkan foto saat barang dikembalikan (wajib, JPG/PNG/WebP).</div>
    @error('return_photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-12">
    <label class="form-label">Catatan</label>
    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $loan->notes) }}</textarea>
    @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-12 d-flex gap-2">
    <button class="btn btn-success" type="submit">Proses Pengembalian</button>
    <a href="{{ route('loans.index') }}" class="btn btn-secondary">Batal</a>
  </div>
</form>
@endsection

@push('scripts')
<script>
  (function initFileDropzones() {
    const zones = document.querySelectorAll('[data-file-drop]');
    if (!zones.length) return;
    zones.forEach((zone) => {
      const input = zone.querySelector('input[type="file"]');
      const nameEl = zone.querySelector('[data-file-drop-name]');
      const setFileName = () => {
        if (!input?.files?.length) {
          if (nameEl) nameEl.textContent = 'Belum ada file';
          return;
        }
        if (nameEl) nameEl.textContent = input.files[0].name;
      };
      zone.addEventListener('click', () => input?.click());
      zone.addEventListener('dragover', (e) => {
        e.preventDefault();
        zone.classList.add('is-dragover');
      });
      zone.addEventListener('dragleave', (e) => {
        if (!zone.contains(e.relatedTarget)) {
          zone.classList.remove('is-dragover');
        }
      });
      zone.addEventListener('drop', (e) => {
        e.preventDefault();
        zone.classList.remove('is-dragover');
        if (!input) return;
        const dt = new DataTransfer();
        if (e.dataTransfer?.files?.length) {
          dt.items.add(e.dataTransfer.files[0]);
        }
        input.files = dt.files;
        input.dispatchEvent(new Event('change', { bubbles: true }));
      });
      input?.addEventListener('change', setFileName);
      setFileName();
    });
  })();
</script>
@endpush
