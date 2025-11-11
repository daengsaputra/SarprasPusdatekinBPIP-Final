@php($context = $context ?? 'inventory')
@php($isLoanable = $context === 'loanable')
@php($title = $isLoanable ? 'Data Barang Peminjaman Peralatan' : 'Data Barang Aset')
@php($listRoute = $isLoanable ? 'assets.loanable' : 'assets.index')
@php($exportParams = request()->except('page'))
@php($exportParams = $isLoanable ? array_merge($exportParams, ['kind' => \App\Models\Asset::KIND_LOANABLE]) : $exportParams)
@php($exportUrl = route('assets.export', $exportParams))
@php($importUrl = $isLoanable ? route('assets.import.form', ['kind' => \App\Models\Asset::KIND_LOANABLE]) : route('assets.import.form'))
@php($createUrl = $isLoanable ? route('assets.create', ['kind' => \App\Models\Asset::KIND_LOANABLE]) : route('assets.create'))
@extends('layouts.app')

@push('styles')
<style>
  body[data-theme="light"] { background: #eef2ff; }
  .asset-shell { display:flex; flex-direction:column; gap:1.5rem; padding-bottom:3rem; }
  .asset-hero { display:flex; justify-content:space-between; flex-wrap:wrap; gap:1.5rem; padding:1.8rem 2rem; border-radius:32px; background:linear-gradient(120deg, rgba(59,130,246,0.18), #ffffff 65%); border:1px solid rgba(148,163,184,0.18); box-shadow:0 25px 50px rgba(15,23,42,0.12); }
  .asset-hero__title { font-size:clamp(1.7rem,3vw,2.3rem); font-weight:700; color:#0f172a; margin-bottom:.4rem; }
  .asset-summary-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:1rem; }
  .asset-summary-card { background:#fff; border-radius:22px; border:1px solid rgba(148,163,184,0.16); box-shadow:0 18px 38px rgba(15,23,42,0.08); padding:1.2rem 1.4rem; }
  .asset-summary-label { text-transform:uppercase; letter-spacing:0.12em; font-size:0.72rem; color:#94a3b8; }
  .asset-summary-value { font-size:1.85rem; font-weight:700; color:#0f172a; }
  .asset-filter-card, .asset-table-card { background:#fff; border-radius:28px; border:1px solid rgba(148,163,184,0.16); box-shadow:0 20px 45px rgba(15,23,42,0.08); padding:1.5rem 1.7rem; }
  .asset-table-card table thead th { text-transform:uppercase; letter-spacing:0.08em; font-size:0.78rem; color:#64748b; }
  .asset-table-card table tbody td { vertical-align:middle; }
  .asset-actions { display:flex; flex-wrap:wrap; gap:0.35rem; }
  .asset-actions .btn {
    border-radius: 12px;
    transition: transform 0.25s cubic-bezier(.17,.67,.45,1.32), box-shadow 0.2s ease;
  }
  .asset-actions .btn.is-animating {
    animation: assetActionPulse 0.35s cubic-bezier(.17,.67,.45,1.32);
  }
  @keyframes assetActionPulse {
    0% { transform: scale(0.9); }
    60% { transform: scale(1.05); }
    100% { transform: scale(1); }
  }
  .asset-photo-modal {
    position: fixed;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(2,6,23,0.6);
    backdrop-filter: blur(6px);
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.25s ease, visibility 0.25s ease;
    z-index: 1400;
    padding: 1.5rem;
  }
  .asset-photo-modal.is-visible {
    opacity: 1;
    visibility: visible;
  }
  .asset-photo-panel {
    position: relative;
    background: #fff;
    border-radius: 24px;
    padding: 1rem;
    box-shadow: 0 40px 90px rgba(15,23,42,0.45);
    transform: scale(0.85);
    transition: transform 0.32s cubic-bezier(.17,.67,.45,1.32);
    max-width: min(480px, 90vw);
  }
  .asset-photo-modal.is-visible .asset-photo-panel {
    transform: scale(1);
  }
  .asset-photo-panel img {
    width: 100%;
    border-radius: 18px;
    object-fit: cover;
    max-height: 70vh;
  }
  .asset-photo-close {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    border: none;
    border-radius: 999px;
    width: 34px;
    height: 34px;
    background: rgba(15,23,42,0.1);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    cursor: pointer;
  }
  .letter-wide { letter-spacing:0.12em; }
  @media (max-width: 992px) { .asset-hero{flex-direction:column;} body[data-theme="light"] main.container{margin-left:0!important;} }
</style>
@endpush

@section('content')
@php($statusValue = request('status', ''))
@php($availableChecked = request('available') === '1')
<div class="asset-shell">
  <section class="asset-hero">
    <div>
      <p class="text-uppercase text-muted small mb-1" style="letter-spacing:0.25em;">{{ $isLoanable ? 'Barang Peminjaman' : 'Barang Aset' }}</p>
      <h1 class="asset-hero__title">{{ $title }}</h1>
      <p class="text-muted mb-0">Kelola data sarpras dengan filter cepat, ekspor/impor Excel, serta aksi edit langsung di tabel.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
      @auth
        <a href="{{ $importUrl }}" class="pill-btn pill-btn--outline">Import Excel <span>&rsaquo;</span></a>
        <a href="{{ $createUrl }}" class="pill-btn pill-btn--primary">+ Tambah {{ $isLoanable ? 'Barang Peminjaman' : 'Aset' }} <span>&rsaquo;</span></a>
      @endauth
    </div>
  </section>

  <section class="asset-summary-grid">
    <div class="asset-summary-card">
      <div class="asset-summary-label">Total Item</div>
      <div class="asset-summary-value">{{ number_format($totalAssets ?? 0) }}</div>
      <div class="text-muted small">Unit tercatat untuk konteks ini.</div>
    </div>
    <div class="asset-summary-card">
      <div class="asset-summary-label">Aktif</div>
      <div class="asset-summary-value">{{ number_format($activeAssets ?? 0) }}</div>
      <div class="text-muted small">Status aktif/siap pakai.</div>
    </div>
    <div class="asset-summary-card">
      <div class="asset-summary-label">Unit Tersedia</div>
      <div class="asset-summary-value">{{ number_format($availableUnits ?? 0) }}</div>
      <div class="text-muted small">Total satuan yang bisa digunakan.</div>
    </div>
  </section>

  <section class="asset-filter-card">
    <form method="GET" action="{{ route($listRoute) }}" class="row g-3 align-items-end">
      <input type="hidden" name="filter" value="1">
      <div class="col-md-4">
        <label class="form-label text-uppercase small fw-semibold letter-wide">Cari</label>
        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="kode / nama / deskripsi">
      </div>
      <div class="col-md-3">
        <label class="form-label text-uppercase small fw-semibold letter-wide">Kategori</label>
        <select name="category" class="form-select">
          <option value="">-- semua --</option>
          @foreach(($categories ?? []) as $cat)
            <option value="{{ $cat }}" {{ request('category')===$cat?'selected':'' }}>{{ $cat }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label text-uppercase small fw-semibold letter-wide">Status</label>
        <select name="status" class="form-select">
          <option value="" {{ $statusValue === '' ? 'selected' : '' }}>Semua</option>
          <option value="active" {{ $statusValue === 'active' ? 'selected' : '' }}>Aktif</option>
          <option value="inactive" {{ $statusValue === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label text-uppercase small fw-semibold letter-wide d-block">Ketersediaan</label>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="available" value="1" id="chkAvail" {{ $availableChecked ? 'checked' : '' }}>
          <label class="form-check-label" for="chkAvail">Hanya stok tersedia</label>
        </div>
      </div>
      <div class="col-md-2">
        <button class="btn btn-primary w-100" type="submit">Terapkan</button>
      </div>
    </form>
  </section>

  <section class="asset-table-card">
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            @php($s=request('sort'))
            @php($d=request('dir','asc'))
            @php($next=function($key){ return (request('sort')===$key && request('dir','asc')==='asc')?'desc':'asc'; })
            <th>
              @php($q=array_merge(request()->all(),['sort'=>'code','dir'=>$next('code')]))
              @php($arrow=$s==='code' ? ($d==='asc'?'▲':'▼') : '•')
              <a href="{{ route($listRoute,$q) }}" class="text-decoration-none text-muted">Kode <span class="small">{{ $arrow }}</span></a>
            </th>
            <th>
              @php($q=array_merge(request()->all(),['sort'=>'name','dir'=>$next('name')]))
              @php($arrow=$s==='name' ? ($d==='asc'?'▲':'▼') : '•')
              <a href="{{ route($listRoute,$q) }}" class="text-decoration-none text-muted">Nama <span class="small">{{ $arrow }}</span></a>
            </th>
            <th>
              @php($q=array_merge(request()->all(),['sort'=>'category','dir'=>$next('category')]))
              @php($arrow=$s==='category' ? ($d==='asc'?'▲':'▼') : '•')
              <a href="{{ route($listRoute,$q) }}" class="text-decoration-none text-muted">Kategori <span class="small">{{ $arrow }}</span></a>
            </th>
            <th>
              @php($q=array_merge(request()->all(),['sort'=>'qty_available','dir'=>$next('qty_available')]))
              @php($arrow=$s==='qty_available' ? ($d==='asc'?'▲':'▼') : '•')
              <a href="{{ route($listRoute,$q) }}" class="text-decoration-none text-muted">Stok <span class="small">{{ $arrow }}</span></a>
            </th>
            <th>
              @php($q=array_merge(request()->all(),['sort'=>'status','dir'=>$next('status')]))
              @php($arrow=$s==='status' ? ($d==='asc'?'▲':'▼') : '•')
              <a href="{{ route($listRoute,$q) }}" class="text-decoration-none text-muted">Status <span class="small">{{ $arrow }}</span></a>
            </th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($assets as $asset)
            <tr>
              <td>{{ $asset->code }}</td>
              <td>{{ $asset->name }}</td>
              <td>{{ $asset->category ?? '-' }}</td>
              <td>{{ $asset->quantity_available }} / {{ $asset->quantity_total }}</td>
              <td>
                @php($statusLabel = $asset->status === 'active' ? 'Aktif' : ($asset->status === 'inactive' ? 'Tidak aktif' : $asset->status))
                <span class="badge {{ $asset->status === 'active' ? 'bg-success' : 'bg-secondary' }}">{{ $statusLabel }}</span>
              </td>
              <td>
                <div class="asset-actions">
                  @auth
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('assets.edit', $asset) }}">Edit</a>
                    <form method="POST" action="{{ route('assets.destroy', $asset) }}" onsubmit="return confirm('Hapus aset ini?')">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                    </form>
                    @if($asset->photo)
                      <button type="button" class="btn btn-sm btn-outline-secondary" data-photo-view="{{ asset('storage/'.$asset->photo) }}">Foto</button>
                    @endif
                  @endauth
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center text-muted py-4">
                {{ $isLoanable ? 'Belum ada peralatan peminjaman.' : 'Belum ada data aset.' }}
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="d-flex justify-content-end mt-3">
      {{ $assets->links() }}
    </div>
  </section>
</div>
<div class="asset-photo-modal" data-photo-modal aria-hidden="true" role="dialog">
  <div class="asset-photo-panel">
    <button type="button" class="asset-photo-close" data-photo-close>&times;</button>
    <img src="" alt="Foto aset">
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const modal = document.querySelector('[data-photo-modal]');
    const closeBtn = modal?.querySelector('[data-photo-close]');
    const showPhotoModal = (src) => {
      if (!modal) return;
      const img = modal.querySelector('img');
      img.src = src || '';
      modal.classList.add('is-visible');
      modal.setAttribute('aria-hidden', 'false');
    };
    const hidePhotoModal = () => {
      if (!modal) return;
      modal.classList.remove('is-visible');
      modal.setAttribute('aria-hidden', 'true');
      const img = modal.querySelector('img');
      img.src = '';
    };
    closeBtn?.addEventListener('click', hidePhotoModal);
    modal?.addEventListener('click', (event) => {
      if (event.target === modal) {
        hidePhotoModal();
      }
    });
    document.addEventListener('keyup', (event) => {
      if (event.key === 'Escape') {
        hidePhotoModal();
      }
    });

    const tableCard = document.querySelector('.asset-table-card');
    if (!tableCard) {
      return;
    }
    tableCard.addEventListener('click', (event) => {
      const btn = event.target.closest('.asset-actions .btn');
      if (!btn) {
        return;
      }
      const photoTarget = event.target.closest('[data-photo-view]');
      if (photoTarget) {
        event.preventDefault();
        showPhotoModal(photoTarget.getAttribute('data-photo-view'));
      }
      btn.classList.remove('is-animating');
      void btn.offsetWidth;
      btn.classList.add('is-animating');
    });
    tableCard.addEventListener('animationend', (event) => {
      if (event.target.classList.contains('is-animating')) {
        event.target.classList.remove('is-animating');
      }
    });
  });
</script>
@endpush
