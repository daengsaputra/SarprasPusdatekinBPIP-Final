@php
    use Illuminate\Support\Str;
    $title = 'Dashboard';
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
            'icon' => '🗂️',
            'color' => '#0ea5e9',
        ],
        [
            'label' => 'Total Data Peminjaman',
            'value' => number_format($loanTotalCount ?? 0),
            'icon' => '🔁',
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
  .dash-shell { display:flex; flex-direction:column; gap:1.6rem; padding-bottom:2.5rem; }
  .stat-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(210px,1fr)); gap:1rem; }
  .stat-card {
    position:relative;
    overflow:hidden;
    background:#fff;
    border-radius:22px;
    border:1px solid rgba(148,163,184,0.16);
    box-shadow:0 30px 55px rgba(15,23,42,0.12);
    padding:1.1rem 1.35rem;
    display:flex;
    gap:0.9rem;
    align-items:center;
    transition:transform 0.25s ease, box-shadow 0.25s ease;
  }
  .stat-card::after {
    content:'';
    position:absolute;
    inset:0;
    background:radial-gradient(circle at top right, rgba(255,255,255,0.65), transparent 55%);
    pointer-events:none;
  }
  .stat-card:hover {
    transform:translateY(-4px);
    box-shadow:0 35px 60px rgba(15,23,42,0.18);
  }
  .stat-card__icon {
    flex-shrink:0;
    width:48px;
    height:48px;
    border-radius:16px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:1.5rem;
    background:color-mix(in srgb, var(--color, #94a3b8) 20%, #f8fafc);
    box-shadow:inset 0 0 0 1px rgba(255,255,255,0.8);
  }
  .stat-card__label {
    text-transform:uppercase;
    letter-spacing:0.35em;
    font-size:0.72rem;
    color:#94a3b8;
    margin-bottom:0.35rem;
  }
  .stat-card__meta {
    position:relative;
    z-index:1;
  }
  .stat-card__value {
    font-size:1.65rem;
    font-weight:700;
    color:#0f172a;
    margin:0;
  }
  .user-panel { background:#fff; border-radius:var(--card-radius,20px); border:1px solid rgba(148,163,184,0.16); box-shadow:0 18px 34px rgba(15,23,42,0.08); padding:1.4rem; display:flex; flex-direction:column; gap:1rem; }
  .user-panel__header { display:flex; justify-content:space-between; align-items:center; }
  .user-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); gap:1rem; }
  .user-card { border:1px solid rgba(226,232,240,0.8); border-radius:18px; padding:1rem; display:flex; gap:0.85rem; align-items:center; background:rgba(248,250,252,0.9); }
  .user-card img, .user-card .user-fallback { width:44px; height:44px; border-radius:14px; object-fit:cover; border:1px solid #e2e8f0; }
  .user-card .user-fallback { background:linear-gradient(135deg,#475569,#1e293b); color:#fff; font-weight:600; display:flex; align-items:center; justify-content:center; }
  @media (max-width: 992px) { body[data-theme="light"] .app-main { margin-left:0!important; } }
</style>
@endpush

@section('content')
<div class="dash-shell">
  <section class="stat-grid">
    @foreach($statCards as $card)
      <div class="stat-card" style="--color: {{ $card['color'] }};">
        <div class="stat-card__icon">{{ $card['icon'] }}</div>
        <div class="stat-card__meta">
          <p class="stat-card__label">{{ Str::upper($card['label']) }}</p>
          <p class="stat-card__value">{{ $card['value'] }}</p>
        </div>
      </div>
    @endforeach
  </section>

</div>
@endsection
