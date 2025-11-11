@php
    use Illuminate\Support\Carbon;
    $title = 'Dashboard';
    $now = Carbon::now();
    $hour = (int) $now->format('H');
    $greeting = match (true) {
        $hour < 11 => 'Selamat Pagi',
        $hour < 15 => 'Selamat Siang',
        $hour < 19 => 'Selamat Sore',
        default => 'Selamat Malam',
    };
    $statCards = [
        [
            'label' => 'Total Data Barang',
            'value' => number_format(data_get($dashboardCounts, 'assets_loanable', 0)),
            'icon' => '📦',
            'color' => '#22c55e',
        ],
        [
            'label' => 'Total Data Aset',
            'value' => number_format(data_get($dashboardCounts, 'assets', 0)),
            'icon' => '📁',
            'color' => '#0ea5e9',
        ],
        [
            'label' => 'Total Data Peminjaman',
            'value' => number_format($loanTotalCount ?? 0),
            'icon' => '↔️',
            'color' => '#6366f1',
        ],
        [
            'label' => 'Total Data Barang Pengembalian',
            'value' => number_format($returnQuantityTotal ?? 0),
            'icon' => '📥',
            'color' => '#f97316',
        ],
        [
            'label' => 'Total Anggota',
            'value' => number_format(data_get($dashboardCounts, 'users', 0)),
            'icon' => '👥',
            'color' => '#a855f7',
        ],
    ];
@endphp
@extends('layouts.app')

@push('styles')
<style>
  body[data-theme="light"] { background: #eef2ff; }
  .dash-shell { display:flex; flex-direction:column; gap:1.5rem; padding-bottom:3rem; }
  .dash-hero {
    display:flex; justify-content:space-between; gap:1.5rem; flex-wrap:wrap;
    padding:2rem; border-radius:32px;
    background:linear-gradient(120deg, rgba(59,130,246,0.15), #ffffff 60%);
    border:1px solid rgba(148,163,184,0.18); box-shadow:0 25px 45px rgba(15,23,42,0.12);
  }
  .dash-hero__title { font-size:clamp(1.9rem,3vw,2.5rem); font-weight:700; color:#0f172a; margin-bottom:0.4rem; }
  .dash-hero__eyebrow { text-transform:uppercase; letter-spacing:0.25em; font-size:0.75rem; color:#94a3b8; }
  .dash-hero__date { font-size:0.95rem; color:#475569; }
  .stat-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:1rem; }
  .stat-card { background:#fff; border-radius:22px; border:1px solid rgba(148,163,184,0.16); box-shadow:0 18px 38px rgba(15,23,42,0.08); padding:1rem 1.4rem; display:flex; gap:0.9rem; align-items:center; }
  .stat-icon { font-size:1.55rem; width:44px; height:44px; border-radius:14px; display:flex; align-items:center; justify-content:center; background:rgba(148,163,184,0.18); }
  .stat-card[data-color] .stat-icon { background: color-mix(in srgb, var(--color) 25%, #fff); }
  .stat-card__label { text-transform:uppercase; letter-spacing:0.08em; font-size:0.72rem; color:#94a3b8; }
  .stat-card__value { font-size:1.65rem; font-weight:700; color:#0f172a; margin:0.2rem 0; }
  .user-panel { background:#fff; border-radius:28px; border:1px solid rgba(148,163,184,0.16); box-shadow:0 22px 40px rgba(15,23,42,0.08); padding:1.6rem; display:flex; flex-direction:column; gap:1rem; }
  .user-panel__header { display:flex; justify-content:space-between; align-items:center; }
  .user-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(260px,1fr)); gap:1rem; }
  .user-card { border:1px solid rgba(226,232,240,0.8); border-radius:20px; padding:1rem; display:flex; gap:0.9rem; align-items:center; background:rgba(248,250,252,0.9); }
  .user-card img, .user-card .user-fallback { width:48px; height:48px; border-radius:16px; object-fit:cover; border:1px solid #e2e8f0; }
  .user-card .user-fallback { background:linear-gradient(135deg,#475569,#1e293b); color:#fff; font-weight:600; display:flex; align-items:center; justify-content:center; }
  @media (max-width: 992px) { body[data-theme="light"] .app-main { margin-left:0!important; } .dash-hero { flex-direction:column; } }
</style>
@endpush

@section('content')
<div class="dash-shell">
  <section class="dash-hero">
    <div>
      <p class="dash-hero__eyebrow">Dashboard</p>
      <h1 class="dash-hero__title">{{ $greeting }}, {{ auth()->user()->name ?? 'Pengguna' }}!</h1>
      <p class="text-muted mb-0">Kelola data sarpras, pantau peminjaman, serta akses informasi anggota dalam satu tampilan.</p>
    </div>
    <div class="dash-hero__date">{{ $now->translatedFormat('l, d F Y') }}</div>
  </section>

  <section class="stat-grid">
    @foreach($statCards as $card)
      <div class="stat-card" style="--color: {{ $card['color'] }};" data-color>
        <div class="stat-icon">{{ $card['icon'] }}</div>
        <div>
          <div class="stat-card__label">{{ $card['label'] }}</div>
          <div class="stat-card__value">{{ $card['value'] }}</div>
        </div>
      </div>
    @endforeach
  </section>

</div>
@endsection
