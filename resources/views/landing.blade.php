@extends('layouts.landing')

@php
    $title = 'SARPRAS PUSDATEKIN - Sarana Prasarana BPIP';
    $summaryData = $summaryData ?? ($summary ?? []);
    $availableAssets = $availableAssets ?? [];
    $activeLoans = $activeLoans ?? [];
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
    background: rgba(59, 130, 246, 0.18);
    padding: 0.6rem 1.2rem;
    border-radius: 999px;
    color: #1d4ed8;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    font-size: 0.75rem;
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
    border: 1px solid rgba(59, 130, 246, 0.25);
  }
  .hero-image::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(15, 23, 42, 0.05), rgba(2, 6, 23, 0.35));
    pointer-events: none;
  }

  .metrics-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 1.5rem;
  }
  .metric-card {
    background: #f8fafc;
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-radius: 18px;
    padding: 1.6rem;
    box-shadow: 0 12px 30px rgba(15, 23, 42, 0.12);
  }
  [data-theme="dark"] .metric-card,
  body[data-theme="dark"] .metric-card {
    background: #f8fafc;
    border-color: rgba(15, 23, 42, 0.12);
    color: #0f172a;
  }
  .metric-label {
    text-transform: uppercase;
    letter-spacing: 0.12em;
    font-size: 0.85rem;
    font-weight: 600;
    color: #475569;
  }
  .metric-value {
    font-size: clamp(2.2rem, 4vw, 2.8rem);
    font-weight: 700;
    color: #0f172a;
  }
  .metric-desc {
    color: #64748b;
  }

  .section-panel {
    background: #f8fafc;
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-radius: 24px;
    padding: 1.6rem;
    box-shadow: 0 16px 40px rgba(15, 23, 42, 0.12);
    height: 100%;
  }
  [data-theme="dark"] .section-panel,
  body[data-theme="dark"] .section-panel {
    background: #f8fafc;
    border-color: rgba(15, 23, 42, 0.12);
    color: #0f172a;
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
    background: rgba(59, 130, 246, 0.35);
    border-radius: 8px;
  }
  .asset-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid rgba(148, 163, 184, 0.18);
  }
  .asset-item:last-child {
    border-bottom: none;
  }
  .asset-thumb {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    border: 1px solid rgba(148, 163, 184, 0.2);
    background: rgba(15, 23, 42, 0.85);
    color: #38bdf8;
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
    color: #0f172a;
  }
  .asset-meta {
    color: #64748b;
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
    background: linear-gradient(145deg, rgba(255, 255, 255, 0.96), rgba(226, 244, 255, 0.9));
    border: 1px solid rgba(99, 102, 241, 0.18);
    border-radius: 28px;
    padding: 1.8rem;
    box-shadow: 0 30px 60px rgba(15, 23, 42, 0.16);
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
    background: radial-gradient(190px circle at top right, rgba(14, 165, 233, 0.18), transparent 60%);
    pointer-events: none;
  }
  [data-theme="dark"] .loan-card,
  body[data-theme="dark"] .loan-card {
    background: linear-gradient(145deg, rgba(255, 255, 255, 0.95), rgba(226, 244, 255, 0.92));
    border-color: rgba(79, 70, 229, 0.28);
    color: #0f172a;
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
    color: rgba(71, 85, 105, 0.9);
    font-weight: 700;
    margin-bottom: 0.4rem;
    display: inline-block;
  }
  .loan-title {
    font-size: clamp(1.35rem, 2vw, 1.65rem);
    font-weight: 700;
    color: #0f172a;
  }
  .loan-context-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.45rem 1rem;
    background: rgba(37, 99, 235, 0.12);
    color: #1d4ed8;
    border-radius: 999px;
    font-size: 0.85rem;
    font-weight: 600;
    max-width: 100%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .loan-unit {
    background: rgba(59, 130, 246, 0.15);
    color: #2563eb;
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
    background: rgba(14, 165, 233, 0.1);
    color: #0369a1;
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
    color: rgba(100, 116, 139, 0.82);
    font-weight: 700;
    display: block;
    margin-bottom: 0.2rem;
  }
  .loan-value {
    color: #0f172a;
    font-size: 1rem;
    font-weight: 700;
  }
  .loan-value--compact {
    font-size: 0.9rem;
    font-weight: 600;
  }
  .loan-muted {
    color: rgba(71, 85, 105, 0.75);
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
    color: #0f172a;
  }
  .loan-meta-inline__dates span {
    display: inline-flex;
    align-items: baseline;
  }
  .loan-meta-inline__dates .loan-meta-sep {
    opacity: 0.45;
  }
  [data-theme="dark"] .loan-label,
  body[data-theme="dark"] .loan-label,
  [data-theme="dark"] .loan-label-inline,
  body[data-theme="dark"] .loan-label-inline {
    color: rgba(51, 65, 85, 0.9);
  }

  .feature-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.5rem;
  }
  .feature-card {
    background: #f8fafc;
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-radius: 18px;
    padding: 1.6rem;
    height: 100%;
    box-shadow: 0 16px 40px rgba(15, 23, 42, 0.12);
    display: flex;
    flex-direction: column;
    gap: 0.8rem;
  }
  [data-theme="dark"] .feature-card,
  body[data-theme="dark"] .feature-card {
    background: #f8fafc;
    border-color: rgba(15, 23, 42, 0.12);
    color: #0f172a;
  }
  .feature-title {
    font-weight: 700;
    color: #0f172a;
  }
  .feature-desc {
    color: #475569;
  }

  .marquee-wrapper {
    position: sticky;
    bottom: 0;
    z-index: 100;
    overflow: hidden;
    background: rgba(248, 250, 252, 0.96);
    border: 1px solid rgba(15, 23, 42, 0.1);
    border-radius: 999px;
    padding: 0.75rem 0;
    box-shadow: 0 12px 25px rgba(15, 23, 42, 0.12);
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
    color: #0f172a;
  }
  .marquee-label {
    font-weight: 600;
  }
  .marquee-borrower {
    color: #1d4ed8;
    font-weight: 700;
  }
  .marquee-dot {
    margin: 0 1rem;
    opacity: 0.4;
  }
  [data-theme="dark"] .marquee-wrapper,
  body[data-theme="dark"] .marquee-wrapper {
    background: rgba(248, 250, 252, 0.96);
    border-color: rgba(15, 23, 42, 0.12);
    color: #0f172a;
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
    <div class="hero-image">
      <img src="{{ asset('images/hero-sarpras.jpg') }}" alt="Ilustrasi sarpras" onerror="this.style.display='none';" />
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
      <div class="metric-value" style="color:#1d4ed8;">{{ number_format(data_get($summaryData, 'available', 0)) }}</div>
      <p class="metric-desc mb-0">Perangkat yang tersedia saat ini.</p>
    </div>
    <div class="metric-card">
      <div class="metric-label">Sedang Digunakan</div>
      <div class="metric-value" style="color:#f59e0b;">{{ number_format(data_get($summaryData, 'in_use', max(data_get($summaryData, 'total', 0) - data_get($summaryData, 'available', 0), 0))) }}</div>
      <p class="metric-desc mb-0">Unit dalam status peminjaman aktif.</p>
    </div>
  </div>

  <div class="row g-4 mt-3">
    <div class="col-lg-6">
      <div class="section-panel">
        <div class="section-header">
          <h5 class="mb-0">Sarpras Tersedia</h5>
          <span class="badge rounded-pill text-bg-primary">{{ number_format(data_get($summaryData, 'available', 0)) }} unit</span>
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

