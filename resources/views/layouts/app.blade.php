<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SARPRAS PUSDATEKIN - Sarana Prasarana Aset Pusdatekin' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .app-sidebar {
        width: 260px;
        position: fixed;
        top: 56px;
        bottom: 0;
        left: 0;
        background: var(--sidebar-bg, #fff);
        border-right: 1px solid var(--sidebar-border, rgba(148,163,184,0.25));
        box-shadow: 0 10px 40px rgba(15, 23, 42, 0.08);
        padding: 1.5rem 1.25rem;
        overflow-y: auto;
    }
    .app-sidebar__brand {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .app-sidebar__brand-title {
        font-size: 1rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        color: var(--sidebar-text, #111827);
    }
    .app-sidebar__brand-sub {
        font-size: 0.8rem;
        color: var(--sidebar-muted, #475569);
        text-transform: uppercase;
    }
    .app-sidebar__toggle {
        border: none;
        background: transparent;
        display: flex;
        flex-direction: column;
        gap: 4px;
        padding: 0;
    }
    .app-sidebar__toggle span {
        width: 22px;
        height: 2px;
        background: var(--sidebar-text, #0f172a);
        border-radius: 999px;
    }
    .app-sidebar__section {
        margin-bottom: 1.5rem;
    }
    .app-sidebar__section-title {
        font-size: 0.72rem;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: var(--sidebar-muted, #94a3b8);
        margin-bottom: 0.5rem;
    }
    .app-sidebar__menu {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }
    .app-sidebar__link {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.55rem 0.85rem;
        border-radius: 12px;
        text-decoration: none;
        color: var(--sidebar-text, #1e293b);
        font-weight: 500;
        font-size: 0.92rem;
        background: transparent;
    }
    .app-sidebar__link:hover {
        background: var(--sidebar-active-bg, rgba(37,99,235,0.12));
        color: var(--brand-blue, #1d4ed8);
    }
    .app-sidebar__link.is-active {
        background: var(--sidebar-active-bg, rgba(37,99,235,0.15));
        color: var(--brand-blue, #1d4ed8);
        box-shadow: 0 8px 20px rgba(37,99,235,0.25);
    }
    .app-sidebar__icon {
        font-size: 1rem;
    }
    .app-sidebar__chevron {
        margin-left: auto;
        font-size: 0.95rem;
        color: var(--sidebar-muted, rgba(148,163,184,0.65));
    }
    .app-sidebar__link--danger {
        border: 1px solid rgba(239,68,68,0.35);
        color: #f87171;
        justify-content: flex-start;
        gap: 0.6rem;
        transition: background 0.2s ease, border-color 0.2s ease;
    }
    .app-sidebar__link--danger:hover {
        background: rgba(239,68,68,0.12);
        color: #ef4444;
    }
    .app-sidebar__footer {
        margin-top: 2rem;
        text-align: center;
    }
    .app-sidebar__footer img {
        height: 36px;
        width: auto;
    }
    .app-main {
        margin-left: 260px;
        color: var(--app-text);
    }
    .pill-btn {
        border-radius: 999px;
        padding: 0.32rem 1.1rem;
        font-size: 0.93rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        line-height: 1.2;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .pill-btn span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 1.45rem;
        height: 1.45rem;
        border-radius: 999px;
        font-size: 0.9rem;
    }
    .pill-btn:active { transform: translateY(1px); }
    .pill-btn--primary {
        background: linear-gradient(120deg,#1f5bff,#2140d7);
        border: none;
        color: #fff;
        box-shadow: 0 20px 32px rgba(30,64,175,0.35);
    }
    .pill-btn--primary span {
        background: rgba(255,255,255,0.35);
        color: #fff;
    }
    .pill-btn--outline {
        border: 1.8px solid rgba(72,123,255,0.35);
        background: var(--app-surface-soft);
        color: var(--brand-blue);
        box-shadow: 0 12px 26px rgba(15,23,42,0.08);
    }
    .pill-btn--outline span {
        background: var(--app-highlight);
        color: inherit;
    }
    .pill-btn--outline-green {
        border-color: rgba(46,204,113,0.45);
        color: #199255;
    }
    .pill-btn--outline-green span {
        background: rgba(46,204,113,0.12);
    }

    .table {
        color: var(--app-text);
        border-color: var(--app-border);
        background: transparent;
    }
    .table > :not(caption) > * > * {
        color: inherit;
        border-color: var(--app-border);
        background-color: transparent;
    }
    .table thead th {
        background: var(--app-surface-soft);
        color: var(--app-text);
        border-bottom: 1px solid var(--app-border-strong);
    }
    .table-striped > tbody > tr:nth-of-type(odd) > * {
        background: rgba(148,163,184,0.05);
    }
    .table-hover > tbody > tr:hover > * {
        background: var(--app-highlight);
        color: var(--app-text);
    }
    .list-group-item,
    .dropdown-menu,
    .modal-content {
        background: var(--app-surface);
        color: var(--app-text);
        border-color: var(--app-border);
    }
    .badge {
        color: var(--app-text);
    }
    .alert {
        border-color: var(--app-border-strong);
        background: var(--app-surface-soft);
        color: var(--app-text);
    }
    .alert-success {
        border-color: rgba(34,197,94,0.4);
        background: rgba(34,197,94,0.12);
        color: #4ade80;
    }
    .alert-danger {
        border-color: rgba(248,113,113,0.4);
        background: rgba(248,113,113,0.12);
        color: #fca5a5;
    }
    .alert-info {
        border-color: rgba(59,130,246,0.4);
        background: rgba(59,130,246,0.12);
        color: #93c5fd;
    }
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
            --sidebar-bg: rgba(15,23,42,0.9);
            --sidebar-border: rgba(59,130,246,0.35);
            --sidebar-text: #e2e8f0;
            --sidebar-muted: rgba(148,163,184,0.75);
            --sidebar-active-bg: rgba(59,130,246,0.2);
            --bs-body-color: #e2e8f0;
            --bs-body-bg: transparent;
            --bs-border-color: rgba(148,163,184,0.25);
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
            --sidebar-bg: #ffffff;
            --sidebar-border: rgba(148,163,184,0.3);
            --sidebar-text: #1e293b;
            --sidebar-muted: #94a3b8;
            --sidebar-active-bg: rgba(37,99,235,0.15);
            --bs-body-color: #0f172a;
            --bs-border-color: rgba(15,23,42,0.12);
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
        background: var(--app-highlight);
        color: var(--brand-blue, #2563eb);
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
    .form-control,
    .form-select {
        background: var(--app-input-bg);
        border-color: var(--app-input-border);
        color: var(--app-text);
    }
    .form-control::placeholder,
    .form-select::placeholder {
        color: rgba(148, 163, 184, 0.55);
    }
    .form-control:focus,
    .form-select:focus {
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
        background: var(--app-highlight);
        color: var(--brand-blue, #2563eb);
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
    .text-muted {
        color: var(--app-text-muted) !important;
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
            content: 'â€¢';
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
    <a class="navbar-brand fw-semibold text-uppercase" href="{{ route('landing') }}">SARPRAS PUSDATEKIN</a>
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
            <button class="btn btn-outline-light px-4" type="submit">Logout ({{ auth()->user()->name }}) | {{ strtoupper(auth()->user()->role ?? '') }}</button>
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
    <aside class="app-sidebar">
      <div class="app-sidebar__brand">
        <div>
          <span class="app-sidebar__brand-title">SARPRAS</span>
          <span class="app-sidebar__brand-sub">PUSDATEKIN</span>
        </div>
        <button class="app-sidebar__toggle" type="button" id="sidebarToggle" aria-label="Toggle menu">
          <span></span><span></span><span></span>
        </button>
      </div>
      @php
        $logoCandidates = ['images/pusdatin-logo.png','images/pusdatin-logo.webp','images/pusdatin-logo.jpg','images/pusdatin-logo.jpeg','images/logo-sarpras.svg'];
        $logo = null;
        foreach ($logoCandidates as $candidate) {
            if (file_exists(public_path($candidate))) {
                $logo = $candidate;
                break;
            }
        }
        $menuSections = [
          'Utama' => [
            ['route' => 'dashboard', 'label' => 'Dashboard', 'active' => request()->routeIs('dashboard'), 'icon' => '&#128200;'],
          ],
          'Pengaturan' => [
            ['route' => 'assets.loanable', 'label' => 'Data Barang', 'active' => $loanableMenuActive, 'icon' => '&#128230;'],
            ['route' => 'assets.index', 'label' => 'Data Aset', 'active' => $inventoryMenuActive, 'icon' => '&#128187;'],
          ],
          'Operasional' => [
            ['route' => 'loans.index', 'label' => 'Peminjaman', 'active' => request()->routeIs('loans.*'), 'icon' => '&#128221;'],
            ['route' => 'reports.returns', 'label' => 'Pengembalian', 'active' => request()->routeIs('reports.returns'), 'icon' => '&#128259;'],
            ['route' => 'reports.loans', 'label' => 'Laporan', 'active' => request()->routeIs('reports.loans'), 'icon' => '&#128209;'],
          ],
        ];
        if(auth()->user() && auth()->user()->role==='admin') {
          $menuSections['Administrasi'][] = ['route' => 'users.index', 'label' => 'Anggota', 'active' => request()->routeIs('users.*'), 'icon' => '&#128101;'];
        }
      @endphp
      @foreach($menuSections as $section => $items)
        <div class="app-sidebar__section">
          <p class="app-sidebar__section-title">{{ $section }}</p>
          <ul class="app-sidebar__menu">
            @foreach($items as $item)
              <li>
                <a href="{{ route($item['route']) }}" class="app-sidebar__link {{ $item['active'] ? 'is-active' : '' }}">
                  <span class="app-sidebar__icon">{!! $item['icon'] !!}</span>
                  <span>{{ $item['label'] }}</span>
                  <span class="app-sidebar__chevron">&rsaquo;</span>
                </a>
              </li>
            @endforeach
          </ul>
        </div>
      @endforeach
      <div class="app-sidebar__section">
        <p class="app-sidebar__section-title">Logout</p>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="app-sidebar__link app-sidebar__link--danger w-100">
            <span class="app-sidebar__icon">&#128682;</span>
            <span>Keluar</span>
          </button>
        </form>
      </div>
      <div class="app-sidebar__footer">
        <img src="{{ asset($logo ?? 'images/logo-sarpras.svg') }}" alt="Pusdatin">
      </div>
    </aside>
    <main class="container app-main">
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
    <span>Info persediaan: Total barang peminjaman {{ number_format($loanableTotals['total']) }} unit &bull; Sedang dipinjam {{ number_format($loanableTotals['borrowed']) }} unit &bull; Tersedia {{ number_format(max($loanableTotals['available'], 0)) }} unit</span>
    <span>Info persediaan: Total barang peminjaman {{ number_format($loanableTotals['total']) }} unit &bull; Sedang dipinjam {{ number_format($loanableTotals['borrowed']) }} unit &bull; Tersedia {{ number_format(max($loanableTotals['available'], 0)) }} unit</span>
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







