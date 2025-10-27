<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SARPRAS PUSDATEKIN – Sarana Prasarana Aset Pusdatekin' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --brand-blue: #2563eb;
            --brand-blue-dark: #1d4ed8;
            --brand-cyan: #38bdf8;
            --app-bg: linear-gradient(140deg, #0b1220 0%, #05060a 55%, #020205 100%);
            --app-surface: rgba(15, 23, 42, 0.85);
            --app-surface-soft: rgba(15, 23, 42, 0.7);
            --app-border: rgba(148, 163, 184, 0.2);
            --app-border-strong: rgba(59, 130, 246, 0.35);
            --app-text: #e2e8f0;
            --app-text-muted: rgba(148, 163, 184, 0.72);
            --app-shadow: 0 24px 50px rgba(2, 6, 23, 0.45);
            --app-card-shadow: 0 22px 48px rgba(2, 6, 23, 0.45);
            --app-nav-bg: rgba(2, 6, 23, 0.9);
            --app-nav-border: rgba(59, 130, 246, 0.28);
            --app-input-bg: rgba(15, 23, 42, 0.65);
            --app-input-border: rgba(148, 163, 184, 0.22);
            --app-input-focus: rgba(59, 130, 246, 0.5);
            --app-scroll-thumb: rgba(59, 130, 246, 0.45);
            --app-highlight: rgba(59, 130, 246, 0.18);
        }
        [data-theme="light"] {
            --app-bg: linear-gradient(160deg, #ffffff 0%, #eef2ff 40%, #e2e8f0 100%);
            --app-surface: rgba(255, 255, 255, 0.96);
            --app-surface-soft: rgba(241, 245, 249, 0.92);
            --app-border: rgba(15, 23, 42, 0.08);
            --app-border-strong: rgba(148, 163, 184, 0.25);
            --app-text: #0f172a;
            --app-text-muted: rgba(71, 85, 105, 0.72);
            --app-shadow: 0 22px 40px rgba(15, 23, 42, 0.14);
            --app-card-shadow: 0 18px 32px rgba(148, 163, 184, 0.16);
            --app-nav-bg: rgba(255, 255, 255, 0.95);
            --app-nav-border: rgba(15, 23, 42, 0.12);
            --app-input-bg: rgba(255, 255, 255, 0.95);
            --app-input-border: rgba(148, 163, 184, 0.35);
            --app-input-focus: rgba(37, 99, 235, 0.45);
            --app-scroll-thumb: rgba(37, 99, 235, 0.35);
            --app-highlight: rgba(37, 99, 235, 0.12);
        }
        body {
            padding-top: 4.5rem;
            padding-bottom: 3.25rem;
            min-height: 100vh;
            background: var(--app-bg);
            color: var(--app-text);
            transition: background .3s ease, color .3s ease;
        }
        a {
            color: var(--brand-cyan);
            transition: color .2s ease;
        }
        a:hover,
        a:focus {
            color: #7dd3fc;
        }
        .navbar {
            background: var(--app-nav-bg) !important;
            border-bottom: 1px solid var(--app-nav-border);
            backdrop-filter: blur(10px);
            box-shadow: var(--app-shadow);
        }
        .navbar .navbar-brand {
            color: var(--app-text) !important;
            letter-spacing: 0.12em;
        }
        .navbar .nav-link {
            color: var(--app-text-muted) !important;
        }
        .navbar .nav-link:hover,
        .navbar .nav-link:focus {
            color: var(--app-text) !important;
        }
        .navbar .btn-outline-light {
            border-color: var(--app-border);
            color: var(--app-text);
        }
        .navbar .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--app-text);
        }
        .navbar .btn-outline-primary {
            border-color: var(--brand-blue);
            color: var(--brand-cyan);
        }
        .navbar .btn-outline-primary:hover {
            background: var(--brand-blue);
            color: #fff;
        }
        .theme-toggle-slot {
            display: flex;
            align-items: center;
        }
        .app-theme-toggle__switch {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: space-between;
            width: 62px;
            height: 30px;
            border-radius: 999px;
            padding: 0 10px;
            background: var(--app-surface-soft);
            border: 1px solid var(--app-border);
            cursor: pointer;
            transition: background .3s ease, border-color .3s ease;
        }
        .app-theme-toggle__icon {
            font-size: 0.95rem;
            line-height: 1;
            color: var(--app-text-muted);
            transition: opacity .25s ease, color .25s ease;
            display: flex;
        }
        .app-theme-toggle__icon svg {
            width: 16px;
            height: 16px;
            display: block;
            pointer-events: none;
        }
        .app-theme-toggle__icon--sun {
            opacity: 0;
        }
        .app-theme-toggle__thumb {
            position: absolute;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            top: 50%;
            left: 4px;
            transform: translateY(-50%);
            background: var(--brand-cyan);
            box-shadow: 0 10px 22px rgba(56, 189, 248, 0.4);
            transition: transform .3s cubic-bezier(0.22, 1, 0.36, 1), background .3s ease, box-shadow .3s ease;
        }
        .app-theme-toggle__input:checked + .app-theme-toggle__switch .app-theme-toggle__thumb {
            transform: translate(28px, -50%);
            background: #fbbf24;
            box-shadow: 0 10px 20px rgba(251, 191, 36, 0.35);
        }
        .app-theme-toggle__input:checked + .app-theme-toggle__switch .app-theme-toggle__icon--sun {
            opacity: 1;
            color: #fbbf24;
        }
        .app-theme-toggle__input:checked + .app-theme-toggle__switch .app-theme-toggle__icon--moon {
            opacity: 0;
        }
        .card {
            background: var(--app-surface);
            border: 1px solid var(--app-border);
            box-shadow: var(--app-card-shadow);
            color: var(--app-text);
        }
        .card .form-label,
        .card .form-text {
            color: var(--app-text-muted);
        }
        .form-control {
            background: var(--app-input-bg);
            border-color: var(--app-input-border);
            color: var(--app-text);
        }
        .form-control::placeholder {
            color: rgba(148, 163, 184, 0.55);
        }
        .form-control:focus {
            border-color: var(--app-input-focus);
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
            background: var(--app-input-bg);
            color: var(--app-text);
        }
        .form-check-label {
            color: var(--app-text-muted);
        }
        .form-check-input {
            background: var(--app-input-bg);
            border-color: var(--app-input-border);
        }
        .form-check-input:checked {
            background-color: var(--brand-blue);
            border-color: var(--brand-blue);
        }
        .btn-primary {
            background: linear-gradient(120deg, var(--brand-blue), var(--brand-blue-dark));
            border: none;
            box-shadow: 0 14px 30px rgba(37, 99, 235, 0.35);
        }
        .btn-primary:hover,
        .btn-primary:focus {
            background: linear-gradient(120deg, var(--brand-blue-dark), var(--brand-blue));
            box-shadow: 0 18px 35px rgba(30, 64, 175, 0.35);
        }
        .btn-outline-light {
            border-color: var(--app-border);
            color: var(--app-text);
        }
        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--app-text);
        }
        .bg-dark {
            background: var(--app-surface) !important;
        }
        .text-white-50 {
            color: var(--app-text-muted) !important;
        }
        aside.bg-dark {
            border-right: 1px solid var(--app-border);
            box-shadow: inset -1px 0 0 rgba(148, 163, 184, 0.08);
        }
        aside .nav-link {
            color: var(--app-text-muted) !important;
        }
        aside .nav-link.bg-secondary {
            background: var(--app-highlight) !important;
            color: var(--app-text) !important;
        }
        aside .nav-link:hover {
            color: var(--app-text) !important;
            background: rgba(148, 163, 184, 0.12);
        }
        main.container {
            color: inherit;
        }
        .running-info {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, rgba(37, 99, 235, 0.3) 0%, rgba(15, 23, 42, 0.88) 45%, rgba(10, 12, 24, 0.95) 100%);
            color: rgba(241, 245, 249, 0.92);
            z-index: 1035;
            overflow: hidden;
            font-size: 0.85rem;
            border-top: 1px solid var(--app-border-strong);
            backdrop-filter: blur(8px);
            box-shadow: 0 -12px 30px rgba(2, 6, 23, 0.42);
            text-shadow: 0 1px 2px rgba(2, 6, 23, 0.6);
        }
        .running-info::before,
        .running-info::after {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 80px;
            pointer-events: none;
        }
        .running-info::before {
            left: 0;
            background: linear-gradient(90deg, rgba(15, 23, 42, 0.95), transparent);
        }
        .running-info::after {
            right: 0;
            background: linear-gradient(270deg, rgba(15, 23, 42, 0.95), transparent);
        }
        .running-track {
            display: inline-flex;
            white-space: nowrap;
            gap: 3rem;
            padding: 0.6rem 0;
            animation: ticker 20s linear infinite;
            align-items: center;
        }
        .running-track span {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: rgba(241, 245, 249, 0.92);
        }
        .running-track span::before {
            content: '•';
            font-size: 1.1rem;
            color: rgba(56, 189, 248, 0.75);
        }
        @keyframes ticker {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        body::-webkit-scrollbar {
            width: 10px;
        }
        body::-webkit-scrollbar-thumb {
            background: var(--app-scroll-thumb);
            border-radius: 999px;
        }
    </style>
    @stack('styles')
</head>
<body data-theme="dark">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="{{ route('landing') }}">SARPRAS PUSDATEKIN</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-lg-center">
        @auth
          <li class="nav-item d-lg-none"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
          <li class="nav-item d-lg-none"><a class="nav-link" href="{{ route('assets.loanable') }}">Data Barang Peminjaman</a></li>
          <li class="nav-item d-lg-none"><a class="nav-link" href="{{ route('assets.index') }}">Data Barang Aset</a></li>
          <li class="nav-item d-lg-none"><a class="nav-link" href="{{ route('loans.index') }}">Peminjaman</a></li>
        @endauth
      </ul>
      <div class="ms-auto d-flex align-items-center gap-3 text-white-50">
        <span class="d-none d-md-inline">{{ now()->format('Y-m-d') }}</span>
        <div class="theme-toggle-slot d-flex align-items-center">
          @include('components.theme-toggle')
        </div>
        @guest
          <a href="{{ route('assets.loanable') }}" class="btn btn-outline-primary px-4">Data Barang</a>
          <a href="{{ route('login') }}" class="btn btn-outline-light px-4">Login</a>
        @else
          <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <button class="btn btn-outline-light px-4" type="submit">Logout ({{ auth()->user()->name }}) – {{ strtoupper(auth()->user()->role ?? '') }}</button>
          </form>
        @endguest
      </div>
    </div>
  </div>
</nav>

@auth
  @php
      $assetRouteModel = request()->route('asset');
      if ($assetRouteModel && ! $assetRouteModel instanceof \App\Models\Asset) {
          $assetRouteModel = null;
      }
      $kindParam = request()->input('kind');
      $assetKindFromRoute = $assetRouteModel->kind ?? null;
      $loanableMenuActive = request()->routeIs('assets.loanable')
          || (request()->routeIs('assets.import*') && $kindParam === \App\Models\Asset::KIND_LOANABLE)
          || (request()->routeIs('assets.create') && $kindParam === \App\Models\Asset::KIND_LOANABLE)
          || (request()->routeIs(['assets.edit','assets.update','assets.destroy','assets.photo.destroy']) && $assetKindFromRoute === \App\Models\Asset::KIND_LOANABLE);
      $inventoryMenuActive = !$loanableMenuActive && request()->routeIs(['assets.index','assets.create','assets.edit','assets.import','assets.import.*','assets.export','assets.template','assets.photo.destroy','assets.store','assets.update','assets.destroy']);
  @endphp
  <div class="d-flex" style="min-height: calc(100vh - 56px);">
    <aside class="bg-dark text-white" style="width: 240px; position: fixed; top:56px; bottom:0; left:0;">
      <div class="p-2 small text-uppercase text-white-50">Menu</div>
      <ul class="nav flex-column mb-auto">
        <li class="nav-item my-1"><a class="nav-link text-white {{ request()->routeIs('dashboard') ? 'bg-secondary rounded-1' : 'text-white-50' }}" href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="nav-item my-1"><a class="nav-link text-white {{ $loanableMenuActive ? 'bg-secondary rounded-1' : 'text-white-50' }}" href="{{ route('assets.loanable') }}">Data Barang - Peminjaman</a></li>
        <li class="my-1"><a class="nav-link text-white {{ $inventoryMenuActive ? 'bg-secondary rounded-1' : 'text-white-50' }}" href="{{ route('assets.index') }}">Data Barang - Aset</a></li>
        @if(auth()->user() && auth()->user()->role==='admin')
          <li class="my-1"><a class="nav-link text-white {{ request()->routeIs('users.*') ? 'bg-secondary rounded-1' : 'text-white-50' }}" href="{{ route('users.index') }}">Anggota</a></li>
        @endif
        <li class="my-1"><a class="nav-link text-white {{ request()->routeIs('loans.*') ? 'bg-secondary rounded-1' : 'text-white-50' }}" href="{{ route('loans.index') }}">Peminjaman</a></li>
        <li class="my-1"><a class="nav-link text-white {{ request()->routeIs('reports.returns') ? 'bg-secondary rounded-1' : 'text-white-50' }}" href="{{ route('reports.returns') }}">Pengembalian</a></li>
        <li class="my-1"><a class="nav-link text-white {{ request()->routeIs('reports.loans') ? 'bg-secondary rounded-1' : 'text-white-50' }}" href="{{ route('reports.loans') }}">Laporan</a></li>
      </ul>
      @php
        $logoCandidates = ['images/pusdatin-logo.png','images/pusdatin-logo.webp','images/pusdatin-logo.jpg','images/pusdatin-logo.jpeg','images/logo-sarpras.svg'];
        $logo = null;
        foreach ($logoCandidates as $candidate) {
            if (file_exists(public_path($candidate))) {
                $logo = $candidate;
                break;
            }
        }
      @endphp
      <div class="position-absolute" style="left:12px; right:12px; bottom:12px;">
        <img src="{{ asset($logo ?? 'images/logo-sarpras.svg') }}" alt="Pusdatin" style="height:32px;width:auto;opacity:1;">
      </div>
    </aside>
    <main class="container" style="margin-left:240px;">
      @if(session('success'))
          <div class="alert alert-success alert-auto" data-autohide="5000">{{ session('success') }}</div>
      @endif
      @if(session('error'))
          <div class="alert alert-danger alert-auto" data-autohide="5000">{{ session('error') }}</div>
      @endif
      @if(session('info'))
          <div class="alert alert-info alert-auto" data-autohide="5000">{{ session('info') }}</div>
      @endif
      {{ $slot ?? '' }}
      @yield('content')
    </main>
  </div>
@else
<main class="container">
    @if(session('success'))
        <div class="alert alert-success alert-auto" data-autohide="5000">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-auto" data-autohide="5000">{{ session('error') }}</div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-auto" data-autohide="5000">{{ session('info') }}</div>
    @endif

    {{ $slot ?? '' }}
    @yield('content')
 </main>
 @php
   $logoCandidates = ['images/pusdatin-logo.png','images/pusdatin-logo.webp','images/pusdatin-logo.jpg','images/pusdatin-logo.jpeg','images/logo-sarpras.svg'];
   $logo = null;
   foreach ($logoCandidates as $candidate) {
       if (file_exists(public_path($candidate))) {
           $logo = $candidate;
           break;
       }
   }
 @endphp
 <div class="d-none d-md-block" style="position:fixed; left:12px; bottom:12px; z-index:1030;">
   <img src="{{ asset($logo ?? 'images/logo-sarpras.svg') }}" alt="Pusdatin" style="height:32px;width:auto;opacity:1;">
 </div>
@endauth

@php
  $loanableTotals = [
    'total' => \App\Models\Asset::where('kind', \App\Models\Asset::KIND_LOANABLE)->sum('quantity_total'),
    'available' => \App\Models\Asset::where('kind', \App\Models\Asset::KIND_LOANABLE)->sum('quantity_available'),
    'borrowed' => \App\Models\Loan::where('status','borrowed')->sum('quantity')
  ];
@endphp
<div class="running-info">
  <div class="running-track">
    <span>Info persediaan: Total barang peminjaman {{ number_format($loanableTotals['total']) }} unit • Sedang dipinjam {{ number_format($loanableTotals['borrowed']) }} unit • Tersedia {{ number_format(max($loanableTotals['available'], 0)) }} unit</span>
    <span>Info persediaan: Total barang peminjaman {{ number_format($loanableTotals['total']) }} unit • Sedang dipinjam {{ number_format($loanableTotals['borrowed']) }} unit • Tersedia {{ number_format(max($loanableTotals['available'], 0)) }} unit</span>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.alert.alert-auto').forEach((alertEl) => {
      const timeout = parseInt(alertEl.getAttribute('data-autohide'), 10) || 5000;
      setTimeout(() => {
        alertEl.classList.add('fade');
        alertEl.style.transition = 'opacity 0.2s ease';
        alertEl.style.opacity = '0';
        setTimeout(() => alertEl.remove(), 200);
      }, timeout);
    });
  });
</script>
@stack('scripts')
</body>
</html>

