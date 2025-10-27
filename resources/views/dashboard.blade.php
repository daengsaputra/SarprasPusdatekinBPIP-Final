@php
  $title = 'Dashboard';
  $activeLoansCollection = \Illuminate\Support\Collection::make($activeLoans ?? []);
@endphp
@extends('layouts.app')

@push('styles')
<style>
  .dashboard-page {
    display: flex;
    flex-direction: column;
    gap: 2.5rem;
  }
  .dashboard-hero {
    position: relative;
    overflow: hidden;
    padding: 2.6rem;
    border-radius: 32px;
    background: linear-gradient(125deg, rgba(37, 99, 235, 0.35), rgba(15, 23, 42, 0.92));
    border: 1px solid var(--app-border);
    box-shadow: 0 32px 55px rgba(2, 6, 23, 0.55);
  }
  body[data-theme="light"] .dashboard-hero {
    background: linear-gradient(125deg, rgba(59, 130, 246, 0.18), rgba(255, 255, 255, 0.98));
    box-shadow: 0 28px 50px rgba(15, 23, 42, 0.16);
  }
  .dashboard-hero::before,
  .dashboard-hero::after {
    content: '';
    position: absolute;
    border-radius: 50%;
    opacity: .7;
    filter: blur(80px);
    pointer-events: none;
  }
  .dashboard-hero::before {
    width: 320px;
    height: 320px;
    background: rgba(56, 189, 248, 0.35);
    top: -160px;
    right: -120px;
  }
  .dashboard-hero::after {
    width: 260px;
    height: 260px;
    background: rgba(99, 102, 241, 0.4);
    bottom: -140px;
    left: -100px;
  }
  .dashboard-hero__badge {
    display: inline-flex;
    align-items: center;
    gap: .6rem;
    padding: .5rem 1.3rem;
    border-radius: 999px;
    background: rgba(15, 23, 42, 0.5);
    border: 1px solid rgba(59, 130, 246, 0.35);
    font-size: .8rem;
    letter-spacing: .26em;
    text-transform: uppercase;
    color: rgba(191, 219, 254, 0.92);
    position: relative;
    margin-bottom: 1.5rem;
  }
  body[data-theme="light"] .dashboard-hero__badge {
    background: rgba(255, 255, 255, 0.9);
    color: rgba(30, 41, 59, 0.7);
    border-color: rgba(148, 163, 184, 0.35);
  }
  .dashboard-hero__heading {
    font-size: clamp(2.2rem, 4vw, 3rem);
    font-weight: 700;
    letter-spacing: .015em;
    color: var(--app-text);
    margin-bottom: .5rem;
  }
  .dashboard-hero__subtitle {
    color: var(--app-text-muted);
    max-width: 560px;
    font-size: 1.05rem;
  }
  .dashboard-hero__meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1.8rem;
    margin-top: 1.8rem;
  }
  .dashboard-hero__meta-item {
    min-width: 140px;
  }
  .dashboard-hero__meta-label {
    display: block;
    text-transform: uppercase;
    letter-spacing: .14em;
    font-size: .75rem;
    color: rgba(191, 219, 254, 0.72);
  }
  body[data-theme="light"] .dashboard-hero__meta-label {
    color: rgba(30, 41, 59, 0.6);
  }
  .dashboard-hero__meta-value {
    font-size: 1.9rem;
    font-weight: 600;
    color: var(--app-text);
  }
  .dashboard-hero__cta {
    margin-top: 2rem;
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
  }
  .dashboard-page-section {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
  }
  .stat-card-link {
    text-decoration: none;
    color: inherit;
    display: block;
  }
  .stat-card {
    position: relative;
    border-radius: 26px;
    padding: 1.9rem;
    overflow: hidden;
    color: #fff;
    display: flex;
    flex-direction: column;
    gap: .45rem;
    border: 1px solid transparent;
    box-shadow: 0 26px 46px rgba(15, 23, 42, 0.55);
    transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
  }
  .stat-card__label {
    font-size: .82rem;
    letter-spacing: .18em;
    text-transform: uppercase;
    opacity: .85;
  }
  .stat-card__value {
    font-size: clamp(2.4rem, 4vw, 3rem);
    font-weight: 700;
    line-height: 1;
  }
  .stat-card__meta {
    font-size: .95rem;
    opacity: .85;
  }
  .stat-card__icon {
    position: absolute;
    right: 1rem;
    bottom: 1rem;
    width: 76px;
    height: 76px;
    opacity: .16;
    pointer-events: none;
  }
  .stat-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 34px 60px rgba(15, 23, 42, 0.6);
    border-color: rgba(255, 255, 255, 0.18);
  }
  .stat-card--cyan {
    background: linear-gradient(140deg,#0ea5e9,#2563eb);
    border-color: rgba(59, 130, 246, 0.45);
  }
  .stat-card--indigo {
    background: linear-gradient(140deg,#4338ca,#312e81);
    border-color: rgba(99, 102, 241, 0.4);
  }
  .stat-card--emerald {
    background: linear-gradient(140deg,#10b981,#059669);
    border-color: rgba(16, 185, 129, 0.45);
  }
  .stat-card--amber {
    background: linear-gradient(140deg,#f97316,#f59e0b);
    border-color: rgba(234, 179, 8, 0.4);
  }
  .stat-card--slate {
    background: linear-gradient(140deg,#475569,#1f2937);
    border-color: rgba(100, 116, 139, 0.4);
  }
  body[data-theme="light"] .stat-card {
    box-shadow: 0 24px 44px rgba(148, 163, 184, 0.22);
  }
  .quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
    gap: 1.2rem;
  }
  .quick-action-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.2rem;
    border-radius: 20px;
    border: 1px solid var(--app-border);
    background: var(--app-surface);
    color: var(--app-text);
    text-decoration: none;
    box-shadow: 0 18px 38px rgba(2, 6, 23, 0.42);
    transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
  }
  body[data-theme="light"] .quick-action-card {
    background: rgba(255, 255, 255, 0.95);
    box-shadow: 0 16px 32px rgba(15, 23, 42, 0.14);
  }
  .quick-action-card:hover {
    transform: translateY(-6px);
    border-color: rgba(59, 130, 246, 0.35);
    box-shadow: 0 22px 44px rgba(37, 99, 235, 0.28);
    text-decoration: none;
  }
  .quick-action-icon {
    width: 46px;
    height: 46px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(59, 130, 246, 0.18);
    color: var(--brand-cyan);
  }
  .quick-action-body .title {
    font-weight: 600;
    font-size: 1rem;
  }
  .quick-action-body .desc {
    font-size: .85rem;
    color: var(--app-text-muted);
    margin-bottom: 0;
  }
  .section-card {
    background: var(--app-surface);
    border: 1px solid var(--app-border);
    border-radius: 24px;
    padding: 1.8rem;
    box-shadow: 0 24px 44px rgba(2, 6, 23, 0.48);
    height: 100%;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
  }
  body[data-theme="light"] .section-card {
    background: rgba(255, 255, 255, 0.97);
    box-shadow: 0 20px 40px rgba(148, 163, 184, 0.18);
  }
  .section-card__header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
  }
  .section-card__subtitle {
    color: var(--app-text-muted);
    font-size: .9rem;
    margin-bottom: 0;
  }
  .chart-wrapper {
    height: 260px;
    position: relative;
  }
  .chart-toolbar {
    display: flex;
    align-items: center;
    gap: .75rem;
    flex-wrap: wrap;
  }
  .chart-toolbar .btn-group .btn {
    border-radius: 999px;
    padding: .35rem .9rem;
    font-weight: 600;
  }
  .chart-toolbar .btn-group .btn.active {
    background: var(--brand-blue);
    border-color: var(--brand-blue);
    color: #fff;
  }
  .loan-highlight-list {
    margin: 0;
    padding: 0;
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: .4rem;
  }
  .loan-highlight-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.2rem;
    padding: .9rem 0;
    border-bottom: 1px solid rgba(148, 163, 184, 0.18);
  }
  .loan-highlight-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
  }
  .loan-highlight-asset {
    font-weight: 600;
    color: var(--app-text);
    margin-bottom: .2rem;
  }
  .loan-highlight-meta {
    font-size: .85rem;
    color: var(--app-text-muted);
  }
  .loan-highlight-qty {
    border-radius: 999px;
    font-size: .8rem;
  }
  .loan-highlight-status {
    font-size: .68rem;
    letter-spacing: .08em;
    text-transform: uppercase;
  }
  .user-grid {
    display: grid;
    gap: 1.1rem;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  }
  .user-card {
    display: flex;
    align-items: center;
    gap: .9rem;
    padding: .6rem .4rem;
    border-radius: 16px;
    transition: transform .2s ease, background .2s ease;
  }
  .user-card:hover {
    transform: translateY(-3px);
    background: rgba(59, 130, 246, 0.08);
  }
  .user-avatar {
    width: 44px;
    height: 44px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.25), rgba(37, 99, 235, 0.6));
    color: rgba(224, 231, 255, 0.95);
    font-weight: 700;
    letter-spacing: .04em;
  }
  body[data-theme="light"] .user-avatar {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.18), rgba(37, 99, 235, 0.45));
    color: rgba(30, 41, 59, 0.75);
  }
  .user-meta .email {
    font-size: .85rem;
    color: var(--app-text-muted);
    margin-bottom: 0;
  }
  @media (max-width: 991.98px) {
    .dashboard-hero {
      padding: 2.2rem;
    }
    .dashboard-hero__meta {
      gap: 1.2rem;
    }
  }
</style>
@endpush

@section('content')
@php
    $latestUsersCollection = \Illuminate\Support\Collection::make($latestUsers ?? []);
    $highlightLoans = $activeLoansCollection->take(4);
@endphp

<div class="dashboard-page">
  <section class="dashboard-hero">
    <span class="dashboard-hero__badge">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path d="M4 5a1 1 0 0 1 1-1h4V2h6v2h4a1 1 0 0 1 1 1v3h-2V6H6v3H4zM4 9h2v2H4zm14 0h2v2h-2z"/><path d="M5 11h14a1 1 0 0 1 1 1v7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-7a1 1 0 0 1 1-1zm6 6v2h2v-2zm-4 0v2h2v-2zm8 0v2h2v-2z"/></svg>
      Dashboard
    </span>
    <h1 class="dashboard-hero__heading">
      Selamat datang,
      {{ trim(auth()->user()->name ?? auth()->user()->username ?? 'Admin') }}.
    </h1>
    <p class="dashboard-hero__subtitle">
      Pantau sarana prasarana, aktivitas peminjaman, dan keanggotaan dalam satu tampilan yang selalu terbaru.
    </p>
    <div class="dashboard-hero__meta">
      <div class="dashboard-hero__meta-item">
        <span class="dashboard-hero__meta-label">Barang Peminjaman</span>
        <span class="dashboard-hero__meta-value">{{ number_format(data_get($dashboardCounts, 'assets_loanable', 0)) }}</span>
      </div>
      <div class="dashboard-hero__meta-item">
        <span class="dashboard-hero__meta-label">Barang Aset</span>
        <span class="dashboard-hero__meta-value">{{ number_format(data_get($dashboardCounts, 'assets', 0)) }}</span>
      </div>
      <div class="dashboard-hero__meta-item">
        <span class="dashboard-hero__meta-label">Sedang Dipinjam</span>
        <span class="dashboard-hero__meta-value">{{ number_format(data_get($dashboardCounts, 'loans_active', 0)) }}</span>
      </div>
      <div class="dashboard-hero__meta-item">
        <span class="dashboard-hero__meta-label">Telah Dikembalikan</span>
        <span class="dashboard-hero__meta-value">{{ number_format(data_get($dashboardCounts, 'loans_returned', 0)) }}</span>
      </div>
    </div>
  </section>

  <section class="dashboard-page-section">
    <div class="row g-4">
      <div class="col-sm-6 col-xl-3 col-xxl-2">
        <a class="stat-card-link" href="{{ route('assets.loanable') }}">
          <div class="stat-card stat-card--cyan">
            <span class="stat-card__label">Barang Peminjaman</span>
            <span class="stat-card__value">{{ number_format(data_get($dashboardCounts, 'assets_loanable', 0)) }}</span>
            <span class="stat-card__meta">Unit sarpras siap dipinjam.</span>
            <svg class="stat-card__icon" viewBox="0 0 24 24" fill="currentColor">
              <path d="M3 7l9-4 9 4-9 4-9-4zm0 4l9 4 9-4v6l-9 4-9-4v-6z"/>
            </svg>
          </div>
        </a>
      </div>
      <div class="col-sm-6 col-xl-3 col-xxl-2">
        <a class="stat-card-link" href="{{ route('assets.index') }}">
          <div class="stat-card stat-card--indigo">
            <span class="stat-card__label">Barang Aset</span>
            <span class="stat-card__value">{{ number_format(data_get($dashboardCounts, 'assets', 0)) }}</span>
            <span class="stat-card__meta">Inventaris terdaftar lengkap.</span>
            <svg class="stat-card__icon" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 3l9 5v8l-9 5-9-5V8l9-5zm0 2.18L5 9v6l7 3.82L19 15V9l-7-3.82z"/>
            </svg>
          </div>
        </a>
      </div>
      <div class="col-sm-6 col-xl-3 col-xxl-2">
        <a class="stat-card-link" href="{{ route('users.index') }}">
          <div class="stat-card stat-card--emerald">
            <span class="stat-card__label">Anggota Aktif</span>
            <span class="stat-card__value">{{ number_format(data_get($dashboardCounts, 'users', 0)) }}</span>
            <span class="stat-card__meta">Tim yang terhubung dengan sistem.</span>
            <svg class="stat-card__icon" viewBox="0 0 24 24" fill="currentColor">
              <path d="M16 11c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3zM8 11c1.66 0 3-1.34 3-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zM8 13c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
            </svg>
          </div>
        </a>
      </div>
      <div class="col-sm-6 col-xl-3 col-xxl-2">
        <a class="stat-card-link" href="{{ route('loans.index') }}">
          <div class="stat-card stat-card--amber">
            <span class="stat-card__label">Sedang Dipinjam</span>
            <span class="stat-card__value">{{ number_format(data_get($dashboardCounts, 'loans_active', 0)) }}</span>
            <span class="stat-card__meta">Pantau jadwal pengembalian.</span>
            <svg class="stat-card__icon" viewBox="0 0 24 24" fill="currentColor">
              <path d="M3 6h13l3 5h2v7h-2a3 3 0 11-6 0H9a3 3 0 11-6 0H1V6h2zm3 12a1 1 0 100 2 1 1 0 000-2zm10 0a1 1 0 100 2 1 1 0 000-2z"/>
            </svg>
          </div>
        </a>
      </div>
      <div class="col-sm-6 col-xl-3 col-xxl-2">
        <a class="stat-card-link" href="{{ route('reports.returns') }}">
          <div class="stat-card stat-card--slate">
            <span class="stat-card__label">Telah Dikembalikan</span>
            <span class="stat-card__value">{{ number_format(data_get($dashboardCounts, 'loans_returned', 0)) }}</span>
            <span class="stat-card__meta">Riwayat peminjaman selesai.</span>
            <svg class="stat-card__icon" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 22a10 10 0 100-20 10 10 0 000 20zm-1-6l7-7-1.4-1.4L11 13.2 8.4 10.6 7 12l4 4z"/>
            </svg>
          </div>
        </a>
      </div>
    </div>
  </section>

  <section class="dashboard-page-section">
    <h6 class="text-uppercase small fw-semibold text-muted mb-2">Akses cepat</h6>
    <div class="quick-actions-grid">
      <a href="{{ route('assets.index') }}" class="quick-action-card">
        <span class="quick-action-icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M3 7l9-4 9 4-9 4-9-4zm0 8l9 4 9-4-9-4-9 4z"/></svg>
        </span>
        <div class="quick-action-body">
          <div class="title">Kelola Aset</div>
          <p class="desc mb-0">Perbaharui data inventaris lembaga.</p>
        </div>
      </a>
      <a href="{{ route('loans.index') }}" class="quick-action-card">
        <span class="quick-action-icon" style="background: rgba(16, 185, 129, 0.18); color:#34d399;">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M3 6h13l3 5h2v7h-2a3 3 0 11-6 0H9a3 3 0 11-6 0H1V6h2zM7 20a2 2 0 100-4 2 2 0 000 4zm10 0a2 2 0 100-4 2 2 0 000 4z"/></svg>
        </span>
        <div class="quick-action-body">
          <div class="title">Kelola Peminjaman</div>
          <p class="desc mb-0">Permintaan dan riwayat peminjaman.</p>
        </div>
      </a>
      <a href="{{ route('loans.create') }}" class="quick-action-card">
        <span class="quick-action-icon" style="background: rgba(245, 158, 11, 0.2); color:#fbbf24;">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M11 11V6h2v5h5v2h-5v5h-2v-5H6v-2h5z"/></svg>
        </span>
        <div class="quick-action-body">
          <div class="title">Catat Peminjaman</div>
          <p class="desc mb-0">Input transaksi baru dalam hitungan detik.</p>
        </div>
      </a>
      <a href="{{ route('assets.create') }}" class="quick-action-card">
        <span class="quick-action-icon" style="background: rgba(59, 130, 246, 0.22); color:#60a5fa;">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M11 11V6h2v5h5v2h-5v5h-2v-5H6v-2h5z"/></svg>
        </span>
        <div class="quick-action-body">
          <div class="title">Tambah Aset Baru</div>
          <p class="desc mb-0">Simpan detail sarpras beserta stoknya.</p>
        </div>
      </a>
    </div>
  </section>

  <section class="dashboard-page-section">
    <div class="row g-4 align-items-stretch">
      <div class="col-lg-7">
        <div class="section-card h-100">
          <div class="section-card__header">
            <div>
              <h5 class="mb-1">Grafik Peminjaman per Unit Kerja</h5>
              <p class="section-card__subtitle mb-0">Bandingkan jumlah barang dan transaksi di setiap unit.</p>
            </div>
            <div class="chart-toolbar">
              <div class="btn-group btn-group-sm" role="group" aria-label="Mode grafik">
                <button class="btn btn-outline-primary active" id="btnModeQty">Barang</button>
                <button class="btn btn-outline-primary" id="btnModeTx">Transaksi</button>
              </div>
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="togglePct">
                <label class="form-check-label small" for="togglePct">Persentase</label>
              </div>
            </div>
          </div>
          <div class="chart-wrapper">
            <canvas id="chartLoansByUnit"></canvas>
          </div>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="section-card h-100">
          <div class="section-card__header">
            <div>
              <h6 class="mb-1">Peminjaman Aktif</h6>
              <p class="section-card__subtitle mb-0">Ringkasan {{ $activeLoansCollection->count() }} catatan terakhir.</p>
            </div>
            <a href="{{ route('loans.index') }}" class="small text-decoration-none">Kelola</a>
          </div>
          @if($highlightLoans->isNotEmpty())
            <ul class="loan-highlight-list">
              @foreach($highlightLoans as $loan)
                @php($overdue = $loan->return_date_planned && now()->isAfter($loan->return_date_planned))
                <li class="loan-highlight-item">
                  <div>
                    <div class="loan-highlight-asset">{{ $loan->asset->name ?? 'Aset tidak diketahui' }}</div>
                    <div class="loan-highlight-meta">
                      {{ $loan->borrower_name }}
                      @if($loan->unit)
                        &middot; {{ $loan->unit }}
                      @endif
                    </div>
                    <div class="loan-highlight-meta">
                      Pinjam {{ optional($loan->loan_date)->format('d M Y') ?? '-' }}
                      @if($loan->return_date_planned)
                        &middot; Kembali {{ optional($loan->return_date_planned)->format('d M Y') }}
                      @endif
                    </div>
                  </div>
                  <div class="text-end d-flex flex-column align-items-end gap-2">
                    <span class="loan-highlight-qty badge text-bg-primary">{{ (int) $loan->quantity }} unit</span>
                    <span class="loan-highlight-status badge {{ $overdue ? 'bg-danger' : 'bg-success-subtle text-success' }}">{{ $overdue ? 'Terlambat' : 'On track' }}</span>
                  </div>
                </li>
              @endforeach
            </ul>
            @if($activeLoansCollection->count() > $highlightLoans->count())
              <div class="text-end pt-2">
                <a href="{{ route('loans.index') }}" class="small text-decoration-none">Lihat {{ $activeLoansCollection->count() - $highlightLoans->count() }} lainnya &rarr;</a>
              </div>
            @endif
          @else
            <p class="text-muted mb-0">Tidak ada peminjaman aktif saat ini.</p>
          @endif
        </div>
      </div>
    </div>
  </section>

  <section class="dashboard-page-section">
    <div class="section-card">
      <div class="section-card__header">
        <div>
          <h6 class="mb-1">Anggota Terbaru</h6>
          <p class="section-card__subtitle mb-0">Daftar anggota yang baru bergabung dengan sistem.</p>
        </div>
        <a href="{{ route('users.index') }}" class="small text-decoration-none">Lihat semua</a>
      </div>
      @if($latestUsersCollection->isNotEmpty())
        <div class="user-grid">
          @foreach($latestUsersCollection as $user)
            @php($initial = mb_strtoupper(mb_substr($user->name ?? '?', 0, 1)))
            <div class="user-card">
              <div class="user-avatar">{{ $initial }}</div>
              <div class="user-meta">
                <div class="fw-semibold">{{ $user->name }}</div>
                <p class="email mb-0">{{ $user->email }}</p>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <p class="text-muted mb-0">Belum ada anggota terbaru untuk ditampilkan.</p>
      @endif
    </div>
  </section>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
  const ctx = document.getElementById('chartLoansByUnit');
  const labels = @json($chart['labels'] ?? []);
  const dataQty = @json($chart['qty'] ?? []);
  const dataTx = @json($chart['tx'] ?? []);
  if (ctx && labels.length) {
    const sum = arr => arr.reduce((a,b)=>a+(+b||0),0);
    const toPct = arr => { const s = sum(arr)||1; return arr.map(v=> Math.round((v*1000)/s)/10 ); };
    const dataQtyPct = toPct(dataQty);
    const dataTxPct = toPct(dataTx);
    const chart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels,
        datasets: [{
          label: 'Jumlah barang dipinjam',
          data: dataQty,
          backgroundColor: ['#2563eb','#10b981','#f59e0b','#64748b','#0ea5e9','#a78bfa'],
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { 
          y: { beginAtZero: true, ticks: { precision:0 } },
          x: { ticks: { autoSkip: false, maxRotation: 0, minRotation: 0 } }
        }
      }
    });
    const btnQty = document.getElementById('btnModeQty');
    const btnTx = document.getElementById('btnModeTx');
    const togglePct = document.getElementById('togglePct');
    const setMode = (mode) => {
      const usePct = togglePct.checked;
      if (mode === 'tx') {
        chart.data.datasets[0].data = usePct ? dataTxPct : dataTx;
        chart.data.datasets[0].label = usePct ? 'Persentase transaksi (%)' : 'Jumlah transaksi';
        btnTx.classList.add('active'); btnQty.classList.remove('active');
      } else {
        chart.data.datasets[0].data = usePct ? dataQtyPct : dataQty;
        chart.data.datasets[0].label = usePct ? 'Persentase barang (%)' : 'Jumlah barang dipinjam';
        btnQty.classList.add('active'); btnTx.classList.remove('active');
      }
      // axis format
      chart.options.scales.y.max = usePct ? 100 : undefined;
      chart.options.scales.y.ticks = usePct ? { callback: v => v+'%', stepSize: 20 } : { precision:0 };
      chart.update();
    }
    btnModeQty?.addEventListener('click', (e)=>{ e.preventDefault(); setMode('qty'); });
    btnModeTx?.addEventListener('click', (e)=>{ e.preventDefault(); setMode('tx'); });
    togglePct?.addEventListener('change', ()=>{
      const mode = btnTx.classList.contains('active') ? 'tx' : 'qty';
      setMode(mode);
    });
  }
</script>
@if(session('show_loans_popup'))
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var modalEl = document.getElementById('modalActiveLoans');
    if (modalEl) {
      var m = new bootstrap.Modal(modalEl);
      m.show();
    }

    // Filtering and simple pagination for loans table
    var tbody = document.querySelector('#modalActiveLoans tbody');
    var cbOverdueOnly = document.getElementById('cbOverdueOnly');
    var btnShowAll = document.getElementById('btnShowAllLoans');
    var info = document.getElementById('loansShownInfo');
    if (tbody) {
      var DEFAULT_LIMIT = 15;
      var showAll = false;
      var applyFilter = function() {
        var rows = Array.from(tbody.querySelectorAll('tr'));
        var overdueOnly = cbOverdueOnly && cbOverdueOnly.checked;
        var shown = 0, total = 0;
        rows.forEach(function(r){
          var isOver = r.getAttribute('data-overdue') === '1';
          var pass = !overdueOnly || isOver;
          if (pass) {
            total++;
            if (!showAll && shown >= DEFAULT_LIMIT) { r.style.display = 'none'; }
            else { r.style.display = ''; shown++; }
          } else {
            r.style.display = 'none';
          }
        });
        if (info) info.textContent = (showAll ? 'Menampilkan semua' : ('Menampilkan ' + shown + ' dari ' + total));
        if (btnShowAll) btnShowAll.style.display = (showAll || total <= DEFAULT_LIMIT) ? 'none' : '';
      };
      cbOverdueOnly && cbOverdueOnly.addEventListener('change', function(){ showAll = false; applyFilter(); });
      btnShowAll && btnShowAll.addEventListener('click', function(e){ e.preventDefault(); showAll = true; applyFilter(); });
      applyFilter();
    }
  });
</script>
@endif
@endpush

@if(($activeLoans ?? collect())->count())
  <div class="modal fade" id="modalActiveLoans" tabindex="-1" aria-labelledby="modalActiveLoansLabel" aria-hidden="true" data-show-on-load="{{ session('show_loans_popup') ? 'true' : 'false' }}">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalActiveLoansLabel">Pengingat: Aset Sedang Dipinjam</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-0">
          <div class="p-2 d-flex align-items-center justify-content-between border-bottom" id="loansToolbar">
            <div class="form-check form-check-sm m-0">
              <input class="form-check-input" type="checkbox" id="cbOverdueOnly">
              <label class="form-check-label small" for="cbOverdueOnly">Tampilkan yang terlambat saja</label>
            </div>
            <div class="d-flex align-items-center gap-2 small">
              <span id="loansShownInfo"></span>
              <button class="btn btn-sm btn-outline-secondary" id="btnShowAllLoans">Tampilkan semua</button>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-sm mb-0 align-middle">
              <thead class="table-secondary">
                <tr>
                  <th>Aset</th>
                  <th>Peminjam</th>
                  <th>Unit</th>
                  <th class="text-center">Qty</th>
                  <th>Pinjam</th>
                  <th>Rencana Kembali</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
              @foreach($activeLoans as $ln)
                @php($over = $ln->return_date_planned && now()->isAfter($ln->return_date_planned))
                <tr data-overdue="{{ $over ? 1 : 0 }}">
                  <td>{{ $ln->asset->name ?? '-' }}</td>
                  <td>{{ $ln->borrower_name }}</td>
                  <td class="text-nowrap">{{ $ln->unit ?? '-' }}</td>
                  <td class="text-center">{{ (int) $ln->quantity }}</td>
                  <td class="text-nowrap">{{ optional($ln->loan_date)->format('Y-m-d') }}</td>
                  <td class="text-nowrap">{{ optional($ln->return_date_planned)->format('Y-m-d') }}</td>
                  <td>
                    @if($over)
                      <span class="badge bg-danger">Terlambat</span>
                    @else
                      <span class="badge bg-warning text-dark">Dipinjam</span>
                    @endif
                  </td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <a href="{{ route('loans.index') }}" class="btn btn-primary">Kelola Peminjaman</a>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>

@endif
