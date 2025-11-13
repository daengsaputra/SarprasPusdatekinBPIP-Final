@extends('layouts.landing')

@php
    $title = 'SARPRAS PUSDATEKIN - Sarana Prasarana BPIP';
    $summaryData = $summaryData ?? ($summary ?? []);
    $availableAssets = $availableAssets ?? [];
    $activeLoans = $activeLoans ?? [];
    $landingVideoUrl = $landingVideoUrl ?? null;
    $landingVideoMime = $landingVideoMime ?? null;
    $hasHeroVideo = filled($landingVideoUrl);
    $loanGroups = collect($activeLoans)->groupBy(function ($loan) {
        return $loan->batch_code ?: ('loan-'.$loan->id);
    })->map(function ($group) {
        $first = $group->first();
        $loanDate = $group->min('loan_date');
        $plannedReturn = $group->filter(fn($loan) => $loan->return_date_planned)->min('return_date_planned');
        $lateDays = $plannedReturn && now()->isAfter($plannedReturn)
            ? now()->diffInDays($plannedReturn)
            : 0;
        $assetsLabels = $group->map(function ($loan) {
            $name = $loan->asset->name ?? 'Sarana tidak ditemukan';
            $code = $loan->asset->code ?? null;
            $quantity = (int) ($loan->quantity ?? 0);
            $label = trim($name . ($code ? " ({$code})" : ''));
            if ($quantity > 1) {
                $label .= ' x' . $quantity;
            }
            return $label;
        })->filter();
        $assetsCount = $assetsLabels->count();
        $assetsPreview = $assetsLabels->take(2)->implode(' • ');
        if ($assetsCount > 2) {
            $assetsPreview .= ' +' . ($assetsCount - 2) . ' lainnya';
        }
        $activity = trim((string) ($first->activity_name ?? ''));
        if ($activity === '') {
            $activity = trim((string) ($first->notes ?? ''));
        }

        return (object) [
            'borrower_name' => $first->borrower_name,
            'unit' => $first->unit,
            'activity' => $activity,
            'total_quantity' => (int) $group->sum('quantity'),
            'loan_date' => $loanDate,
            'return_date_planned' => $plannedReturn,
            'late_days' => $lateDays,
            'assets_preview' => $assetsPreview ?: 'Teks aset belum tersedia',
            'assets_full' => $assetsLabels->implode(', '),
            'batch_code' => $first->batch_code,
        ];
    })->values();
@endphp

@push('styles')
<style>
  .hero-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2.5rem;
    align-items: center;
  }
  .hero-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    background: color-mix(in srgb, var(--brand-blue) 18%, transparent);
    padding: 0.6rem 1.2rem;
    border-radius: 999px;
    color: var(--brand-blue);
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    font-size: 0.75rem;
    border: 1px solid color-mix(in srgb, var(--brand-blue) 45%, transparent);
  }
  .hero-heading {
    font-size: clamp(2.4rem, 5vw, 3.4rem);
    font-weight: 700;
    color: var(--text-primary);
    letter-spacing: 0.02em;
  }
  .hero-subtext {
    color: var(--text-secondary);
    max-width: 520px;
    font-size: 1.05rem;
  }
  .hero-image {
    position: relative;
    border-radius: 28px;
    overflow: hidden;
    box-shadow: 0 25px 70px rgba(15, 23, 42, 0.35);
    border: 1px solid color-mix(in srgb, var(--brand-blue) 35%, transparent);
    min-height: 320px;
  }
  .hero-image::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(15, 23, 42, 0.05), rgba(2, 6, 23, 0.35));
    pointer-events: none;
  }
  .hero-image--has-video::after {
    background: linear-gradient(180deg, rgba(15, 23, 42, 0.08), rgba(2, 6, 23, 0.25));
  }
  .hero-video {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  .metrics-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 1.5rem;
  }
  .metric-card {
    background: var(--surface-2);
    border: 1px solid color-mix(in srgb, var(--text-primary) 12%, transparent);
    border-radius: 18px;
    padding: 1.6rem;
    box-shadow: 0 12px 30px color-mix(in srgb, var(--brand-blue) 14%, transparent);
  }
  .metric-label {
    text-transform: uppercase;
    letter-spacing: 0.12em;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-secondary);
  }
  .metric-value {
    font-size: clamp(2.2rem, 4vw, 2.8rem);
    font-weight: 700;
    color: var(--brand-blue);
  }
  .metric-value--warn {
    color: #f59e0b;
  }
  .metric-desc {
    color: var(--text-secondary);
  }
  .badge-accent {
    background: color-mix(in srgb, var(--brand-blue) 18%, transparent);
    color: var(--brand-blue);
    border: 1px solid color-mix(in srgb, var(--brand-blue) 35%, transparent);
  }

  .section-panel {
    background: var(--surface-2);
    border: 1px solid color-mix(in srgb, var(--text-primary) 12%, transparent);
    border-radius: 24px;
    padding: 1.6rem;
    box-shadow: 0 16px 40px color-mix(in srgb, var(--brand-blue) 14%, transparent);
    height: 100%;
    color: var(--text-primary);
  }
  .section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
  }
  .scroll-list {
    max-height: 340px;
    overflow-y: auto;
    padding-right: 0.5rem;
  }
  .scroll-list::-webkit-scrollbar {
    width: 6px;
  }
  .scroll-list::-webkit-scrollbar-thumb {
    background: color-mix(in srgb, var(--brand-blue) 45%, transparent);
    border-radius: 8px;
  }
  .asset-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid color-mix(in srgb, var(--text-secondary) 35%, transparent);
  }
  .asset-item:last-child {
    border-bottom: none;
  }
  .asset-thumb {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    border: 1px solid color-mix(in srgb, var(--text-secondary) 30%, transparent);
    background: var(--surface-3);
    color: var(--brand-cyan);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    margin-right: 0.75rem;
    overflow: hidden;
  }
  .asset-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .asset-info {
    flex: 1;
    min-width: 0;
  }
  .asset-name {
    font-weight: 600;
    color: var(--text-primary);
  }
  .asset-meta {
    color: var(--text-secondary);
    font-size: 0.9rem;
  }
  .asset-quantity {
    background: rgba(34, 197, 94, 0.18);
    color: #047857;
    border-radius: 999px;
    padding: 0.2rem 0.75rem;
    font-weight: 600;
    white-space: nowrap;
  }

  .loan-card {
    background: linear-gradient(
      145deg,
      color-mix(in srgb, var(--surface-2) 92%, transparent),
      color-mix(in srgb, var(--surface-3) 80%, transparent)
    );
    border: 1px solid color-mix(in srgb, var(--brand-blue) 25%, transparent);
    border-radius: 28px;
    padding: 1.8rem;
    box-shadow: 0 30px 60px color-mix(in srgb, var(--brand-blue) 16%, transparent);
    display: flex;
    flex-direction: column;
    gap: 1.2rem;
    position: relative;
    overflow: hidden;
  }
  .loan-card::after {
    content: '';
    position: absolute;
    inset: 18px;
    border-radius: 24px;
    background: radial-gradient(
      190px circle at top right,
      color-mix(in srgb, var(--brand-cyan) 35%, transparent),
      transparent 60%
    );
    pointer-events: none;
  }
  .loan-card__header {
    display: flex;
    justify-content: space-between;
    gap: 1.5rem;
    position: relative;
    z-index: 1;
  }
  .loan-card__borrower {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.65rem;
  }
  .loan-context {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
    margin-top: 0.4rem;
  }
  .loan-label-inline {
    font-size: 0.68rem;
    text-transform: uppercase;
    letter-spacing: 0.18em;
    color: color-mix(in srgb, var(--text-secondary) 85%, transparent);
    font-weight: 700;
    margin-bottom: 0.4rem;
    display: inline-block;
  }
  .loan-title {
    font-size: clamp(1.35rem, 2vw, 1.65rem);
    font-weight: 700;
    color: var(--text-primary);
  }
  .loan-context-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.45rem 1rem;
    background: color-mix(in srgb, var(--brand-blue) 14%, transparent);
    color: var(--brand-blue);
    border-radius: 999px;
    font-size: 0.85rem;
    font-weight: 600;
    max-width: 100%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .loan-unit {
    background: color-mix(in srgb, var(--brand-blue) 15%, transparent);
    color: var(--brand-blue);
    border-radius: 999px;
    padding: 0.2rem 0.8rem;
    font-size: 0.78rem;
    font-weight: 600;
  }
  .loan-head-stats {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.5rem;
  }
  .loan-quantity {
    background: color-mix(in srgb, var(--brand-cyan) 22%, transparent);
    color: color-mix(in srgb, var(--brand-blue) 75%, var(--text-primary));
    border-radius: 999px;
    padding: 0.35rem 1.2rem;
    font-weight: 700;
    font-size: 0.9rem;
    white-space: nowrap;
  }
  .loan-status-chip {
    font-size: 0.65rem;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    border-radius: 999px;
    padding: 0.25rem 1rem;
    font-weight: 700;
    background: rgba(16, 185, 129, 0.18);
    color: #047857;
    white-space: nowrap;
  }
  .loan-status-chip.is-overdue {
    background: rgba(248, 113, 113, 0.16);
    color: #b91c1c;
  }
  .loan-metadata-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1rem;
    position: relative;
    z-index: 1;
  }
  .loan-label {
    font-size: 0.74rem;
    text-transform: uppercase;
    letter-spacing: 0.16em;
    color: color-mix(in srgb, var(--text-secondary) 80%, transparent);
    font-weight: 700;
    display: block;
    margin-bottom: 0.2rem;
  }
  .loan-value {
    color: var(--text-primary);
    font-size: 1rem;
    font-weight: 700;
  }
  .loan-value--compact {
    font-size: 0.9rem;
    font-weight: 600;
  }
  .loan-muted {
    color: color-mix(in srgb, var(--text-secondary) 65%, transparent);
  }
  .loan-alert {
    align-self: flex-start;
    padding: 0.35rem 0.95rem;
    border-radius: 999px;
    background: rgba(248, 113, 113, 0.15);
    color: #b91c1c;
    font-weight: 600;
    font-size: 0.8rem;
    position: relative;
    z-index: 1;
  }
  .loan-meta-inline__dates {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    align-items: center;
    font-size: 0.82rem;
    color: var(--text-primary);
  }
  .loan-meta-inline__dates span {
    display: inline-flex;
    align-items: baseline;
  }
  .loan-meta-inline__dates .loan-meta-sep {
    opacity: 0.45;
  }
  .feature-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.5rem;
  }
  .feature-card {
    background: var(--surface-2);
    border: 1px solid color-mix(in srgb, var(--text-primary) 12%, transparent);
    border-radius: 18px;
    padding: 1.6rem;
    height: 100%;
    box-shadow: 0 16px 40px color-mix(in srgb, var(--brand-blue) 14%, transparent);
    display: flex;
    flex-direction: column;
    gap: 0.8rem;
  }
  .feature-title {
    font-weight: 700;
    color: var(--text-primary);
  }
  .feature-desc {
    color: var(--text-secondary);
  }

  .marquee-wrapper {
    position: sticky;
    bottom: 0;
    z-index: 100;
    overflow: hidden;
    background: color-mix(in srgb, var(--surface-2) 90%, transparent);
    border: 1px solid color-mix(in srgb, var(--text-primary) 15%, transparent);
    border-radius: 999px;
    padding: 0.75rem 0;
    box-shadow: 0 12px 25px color-mix(in srgb, var(--brand-blue) 12%, transparent);
    margin-top: 3rem;
  }
  .marquee-track {
    display: inline-flex;
    white-space: nowrap;
    gap: 2.5rem;
    animation: marquee-slide 55s linear infinite;
    padding-left: 100%;
  }
  .marquee-entry {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    color: var(--text-primary);
  }
  .marquee-label {
    font-weight: 600;
  }
  .marquee-borrower {
    color: var(--brand-blue);
    font-weight: 700;
  }
  .marquee-dot {
    margin: 0 1rem;
    opacity: 0.4;
  }
  @keyframes marquee-slide {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
  }
</style>
@endpush

@section('content')
  <div class="hero-grid mb-5">
    <div>
      <span class="hero-chip">SARPRAS PUSDATEKIN</span>
      <h1 class="hero-heading mt-3">Sarana Prasarana Pusdatekin BPIP</h1>
      <p class="hero-subtext mt-3">
        Kelola kebutuhan sarana prasarana dengan cepat dan terarah. Pantau ketersediaan, ajukan peminjaman,
        dan dukung setiap kegiatan dengan fasilitas yang selalu siap digunakan.
      </p>
      <div class="d-flex flex-wrap gap-3 mt-4">
        <a class="btn btn-lg btn-primary px-4" href="{{ route('assets.loanable') }}">Lihat Koleksi Sarpras</a>
        <a class="btn btn-lg btn-outline-primary px-4" href="{{ route('login') }}">Masuk Dashboard</a>
      </div>
    </div>
  <div class="hero-image {{ $hasHeroVideo ? 'hero-image--has-video' : '' }}">
    @if($hasHeroVideo)
      <video class="hero-video" autoplay muted loop playsinline preload="metadata" poster="{{ asset('images/hero-sarpras.jpg') }}">
        <source src="{{ $landingVideoUrl }}" @if($landingVideoMime) type="{{ $landingVideoMime }}" @endif>
        Browser Anda tidak mendukung pemutaran video.
      </video>
    @else
      <img src="{{ asset('images/hero-sarpras.jpg') }}" alt="Ilustrasi sarpras" onerror="this.style.display='none';" />
    @endif
  </div>
  </div>

  <div id="stok" class="metrics-row mb-4">
    <div class="metric-card">
      <div class="metric-label">Total Sarpras</div>
      <div class="metric-value">{{ number_format(data_get($summaryData, 'total', 0)) }}</div>
      <p class="metric-desc mb-0">Unit sarana prasarana terdaftar.</p>
    </div>
    <div class="metric-card">
      <div class="metric-label">Siap Dipinjam</div>
      <div class="metric-value">{{ number_format(data_get($summaryData, 'available', 0)) }}</div>
      <p class="metric-desc mb-0">Perangkat yang tersedia saat ini.</p>
    </div>
    <div class="metric-card">
      <div class="metric-label">Sedang Digunakan</div>
      <div class="metric-value metric-value--warn">{{ number_format(data_get($summaryData, 'in_use', max(data_get($summaryData, 'total', 0) - data_get($summaryData, 'available', 0), 0))) }}</div>
      <p class="metric-desc mb-0">Unit dalam status peminjaman aktif.</p>
    </div>
  </div>

  <div class="row g-4 mt-3">
    <div class="col-lg-6">
      <div class="section-panel">
        <div class="section-header">
          <h5 class="mb-0">Sarpras Tersedia</h5>
          <span class="badge badge-accent rounded-pill">{{ number_format(data_get($summaryData, 'available', 0)) }} unit</span>
        </div>
        <div class="scroll-list">
          @forelse(($availableAssets ?? []) as $asset)
            <div class="asset-item">
              <div class="d-flex align-items-center flex-grow-1 min-w-0">
                <div class="asset-thumb">
                  @if($asset->photo)
                    <img src="{{ asset('storage/'.$asset->photo) }}" alt="Foto {{ $asset->name }}">
                  @else
                    {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($asset->name ?? '?', 0, 1)) }}
                  @endif
                </div>
                <div class="asset-info">
                  <div class="asset-name text-truncate">{{ $asset->name }}</div>
                  <div class="asset-meta text-truncate">{{ $asset->category ?? 'Kategori belum diatur' }}</div>
                </div>
              </div>
              <span class="asset-quantity">{{ $asset->quantity_available }} unit</span>
            </div>
          @empty
            <p class="text-muted mb-0">Belum ada sarpras siap pinjam untuk ditampilkan.</p>
          @endforelse
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="section-panel">
        <div class="section-header">
          <h5 class="mb-0">Peminjaman Aktif</h5>
          <span class="badge rounded-pill text-bg-warning">{{ number_format(data_get($summaryData, 'in_use', 0)) }} unit</span>
        </div>
        <div class="scroll-list">
          @forelse($loanGroups as $loan)
            @php
                $loanDate = optional($loan->loan_date)->format('d M Y');
                $plannedReturn = optional($loan->return_date_planned)->format('d M Y');
                $overdue = $loan->late_days > 0;
            @endphp
            <article class="loan-card mb-4 {{ $overdue ? 'is-overdue' : '' }}">
              <div class="loan-card__header">
                <div>
                  <span class="loan-label-inline">Nama Peminjam</span>
                  <div class="loan-card__borrower">
                    <span class="loan-title text-truncate">{{ $loan->borrower_name ?? 'Peminjam' }}</span>
                    @if($loan->unit)
                      <span class="loan-unit">{{ $loan->unit }}</span>
                    @endif
                  </div>
                  @if(!empty($loan->activity))
                    <div class="loan-context">
                      <span class="loan-label-inline">Nama Kegiatan</span>
                      <span class="loan-context-pill">{{ \Illuminate\Support\Str::limit($loan->activity, 140) }}</span>
                    </div>
                  @endif
                </div>
                <div class="loan-head-stats">
                  <span class="loan-quantity">{{ (int) ($loan->total_quantity ?? 0) }} unit</span>
                  <span class="loan-status-chip {{ $overdue ? 'is-overdue' : '' }}">{{ $overdue ? 'Perlu perhatian' : 'On track' }}</span>
                </div>
              </div>
              <div class="loan-metadata-grid">
                <div>
                  <span class="loan-label">Alat yang Dipinjam</span>
                  <span class="loan-value loan-value--compact" title="{{ $loan->assets_full }}"><strong>{{ \Illuminate\Support\Str::limit($loan->assets_preview, 120) }}</strong></span>
                </div>
                <div>
                  <span class="loan-label">Pinjam & Target Kembali</span>
                  <div class="loan-meta-inline__dates">
                    <span>Pinjam :&nbsp;<strong>{{ $loanDate ?? '-' }}</strong></span>
                    <span class="{{ $overdue ? 'text-danger' : '' }}">Target :&nbsp;<strong>{{ $plannedReturn ?? 'Tanpa batas' }}</strong></span>
                  </div>
                </div>
              </div>
              @if($loan->batch_code)
                <div class="d-flex justify-content-end mt-3">
                  <a target="_blank" rel="noopener" href="{{ route('loans.receipt', ['batch' => $loan->batch_code, 'preview' => 1]) }}" class="btn btn-sm btn-outline-primary">
                    Lihat Bukti Peminjaman (PDF)
                  </a>
                </div>
              @endif
              @if($loan->late_days > 0)
                <div class="loan-alert">Terlambat {{ $loan->late_days }} hari</div>
              @endif
            </article>
          @empty
            <p class="text-muted mb-0">Belum ada catatan peminjaman aktif.</p>
          @endforelse
        </div>
      </div>
    </div>
  </div>

  <div id="fitur" class="feature-row mt-5">
    <div class="feature-card">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="36" height="36"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 3c-3.866 0-7 3.134-7 7v4.5A2.5 2.5 0 0 0 7.5 17H9a3 3 0 0 0 6 0h1.5A2.5 2.5 0 0 0 19 14.5V10c0-3.866-3.134-7-7-7zm0 16a1 1 0 0 1-1-1h2a1 1 0 0 1-1 1z"/></svg>
      <h5 class="feature-title mb-1">Inventaris Sarpras Terpusat</h5>
      <p class="feature-desc mb-0">Seluruh perangkat tercatat rapi dan mudah dipantau dari satu sistem.</p>
    </div>
    <div class="feature-card">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="36" height="36"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 6v6l3 3"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M21 12A9 9 0 1 1 3 12a9 9 0 0 1 18 0z"/></svg>
      <h5 class="feature-title mb-1">Peminjaman Transparan</h5>
      <p class="feature-desc mb-0">Proses peminjaman jelas dengan pengingat otomatis jadwal pengembalian.</p>
    </div>
    <div class="feature-card">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="36" height="36"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M4 6h16M7 12h10M10 18h4"/></svg>
      <h5 class="feature-title mb-1">Analitik Real-time</h5>
      <p class="feature-desc mb-0">Laporan pemakaian sarpras membantu pengambilan keputusan yang cepat.</p>
    </div>
  </div>

  @php
      $tickerEntries = [];
      foreach ($loanGroups as $loan) {
          $borrower = e($loan->borrower_name ?? 'Peminjam');
          $activityRaw = trim((string) ($loan->activity ?? ''));
          $activity = e($activityRaw !== '' ? \Illuminate\Support\Str::limit($activityRaw, 80) : 'Tidak ada keterangan kegiatan');
          $assets = e($loan->assets_preview ?? 'Sarana tidak ditemukan');
          $quantity = e((int) ($loan->total_quantity ?? 0));
          $dueDate = e(optional($loan->return_date_planned)->format('d M Y') ?: 'Tanpa batas');
          $tickerEntries[] = "<span class=\"marquee-entry\"><span class=\"marquee-label\">Nama Peminjam:</span> <span class=\"marquee-borrower\">{$borrower}</span> <span class=\"marquee-sep\">|</span> <span class=\"marquee-label\">Nama Kegiatan:</span> {$activity} <span class=\"marquee-sep\">|</span> <span class=\"marquee-label\">Alat:</span> {$assets} ({$quantity} unit) <span class=\"marquee-sep\">|</span> <span class=\"marquee-label\">Target Kembali:</span> {$dueDate}</span>";
      }
      if (empty($tickerEntries)) {
          $tickerEntries[] = '<span class="marquee-entry">Belum ada peminjaman aktif.</span>';
      }
      $tickerHtml = '<span class="marquee-entry"><span class="marquee-label">Monitoring SARPRAS PUSDATEKIN :</span></span> ' . implode('<span class="marquee-dot">&bull;</span>', $tickerEntries);
  @endphp
  <div class="marquee-wrapper" aria-live="polite">
    <div class="marquee-track">
      <span>{!! $tickerHtml !!}</span>
      <span>{!! $tickerHtml !!}</span>
    </div>
  </div>
@endsection

