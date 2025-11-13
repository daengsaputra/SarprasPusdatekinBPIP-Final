<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SARPRAS PUSDATEKIN - Sarana Prasarana Aset Pusdatekin' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @php
        $sharedThemeSurfaces = $activeLandingThemeSurfaces ?? \App\Models\SiteSetting::landingThemeSurfaces();
        $sharedBrandBlue = data_get($sharedThemeSurfaces, 'accent', '#2563eb');
        $sharedBrandCyan = data_get($sharedThemeSurfaces, 'accentSoft', '#38bdf8');
        $sharedSurface1 = data_get($sharedThemeSurfaces, 'surface1', 'linear-gradient(145deg, #050b18 0%, #070d1f 55%, #0b1124 100%)');
        $sharedSurface2 = data_get($sharedThemeSurfaces, 'surface2', 'rgba(12, 19, 33, 0.92)');
        $sharedSurface3 = data_get($sharedThemeSurfaces, 'surface3', 'rgba(15, 23, 42, 0.78)');
        $sharedTextPrimary = data_get($sharedThemeSurfaces, 'text_primary', '#e2e8f0');
        $sharedTextSecondary = data_get($sharedThemeSurfaces, 'text_secondary', 'rgba(148, 163, 184, 0.78)');
    @endphp
    <style>
    .app-sidebar {
        width: var(--layout-sidebar-width, 250px);
        position: fixed;
        top: 56px;
        bottom: 0;
        left: 0;
        background: var(--sidebar-bg, #fff);
        border-right: 1px solid var(--sidebar-border, rgba(148,163,184,0.25));
        box-shadow: 0 10px 40px rgba(15, 23, 42, 0.08);
        padding: 1.4rem 1.15rem;
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
        margin-bottom: 1.25rem;
    }
    .app-sidebar__section-title {
        font-size: var(--font-size-label, 0.78rem);
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: var(--sidebar-muted, #94a3b8);
        margin-bottom: 0.45rem;
    }
    .app-sidebar__menu {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }
    .app-sidebar__link {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.5rem 0.85rem;
        border-radius: 12px;
        text-decoration: none;
        color: var(--sidebar-text, #1e293b);
        font-weight: 500;
        font-size: calc(var(--font-size-base, 1rem) * 0.94);
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
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 1.75rem;
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
        margin-left: var(--layout-sidebar-width, 250px);
        width: calc(100% - var(--layout-sidebar-width, 250px));
        max-width: 100%;
        padding: var(--layout-padding-vertical, 2rem) var(--layout-padding, clamp(1.5rem, 2.8vw, 2.75rem));
        color: var(--app-text);
        background: var(--app-surface, #f8fafc);
        min-height: 100vh;
    }
    .app-main--guest {
        margin-left: 0;
        width: 100%;
        padding: var(--layout-padding-vertical, 2rem) var(--layout-padding, clamp(1.5rem, 2.8vw, 2.75rem));
    }
    .report-subnav {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        padding: 1rem 1.25rem;
        border-radius: 24px;
        background: var(--app-surface, #fff);
        border: 1px solid var(--app-border-strong, rgba(148,163,184,0.25));
        box-shadow: 0 16px 32px rgba(15,23,42,0.08);
    }
    .report-subnav__link {
        flex: 1 1 180px;
        min-width: 180px;
        text-decoration: none;
        border-radius: 18px;
        border: 1px solid rgba(148,163,184,0.3);
        padding: 0.8rem 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        color: var(--app-text, #0f172a);
        font-weight: 600;
        transition: all 0.2s ease;
        background: var(--app-surface-soft, #fff);
    }
    .report-subnav__link small {
        font-weight: 500;
        color: #64748b;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        font-size: 0.7rem;
    }
    .report-subnav__link.is-active {
        background: linear-gradient(125deg,#f59e0b,#fef3c7);
        border-color: #f59e0b;
        color: #92400e;
        box-shadow: 0 12px 28px rgba(245,158,11,0.35);
    }
    @media (max-width: 992px) {
        .app-main {
            margin-left: 0;
            width: 100%;
            padding: 1.5rem;
        }
        .report-subnav {
            flex-direction: column;
        }
        .report-subnav__link {
            width: 100%;
        }
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
            --brand-blue: {{ $sharedBrandBlue }};
            --brand-blue-dark: {{ $sharedBrandBlue }};
            --brand-cyan: {{ $sharedBrandCyan }};
            --font-sans: 'Instrument Sans', 'Inter', 'Segoe UI', 'Nunito', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
            --font-size-base: 0.88rem;
            --font-size-small: 0.8rem;
            --font-size-label: 0.78rem;
            --font-size-heading: clamp(1.5rem, 2.4vw, 2.1rem);
            --layout-sidebar-width: 250px;
            --layout-padding: clamp(1rem, 2vw, 2rem);
            --layout-padding-vertical: 1.25rem;
            --card-radius-lg: 20px;
            --card-radius: 14px;
            --card-shadow: 0 12px 28px rgba(15, 23, 42, 0.1);
            --app-bg: {{ $sharedSurface1 }};
            --app-surface: {{ $sharedSurface2 }};
            --app-surface-soft: {{ $sharedSurface3 }};
            --app-border: color-mix(in srgb, {{ $sharedTextSecondary }} 35%, transparent);
            --app-border-strong: color-mix(in srgb, {{ $sharedBrandBlue }} 35%, transparent);
            --app-text: {{ $sharedTextPrimary }};
            --app-text-muted: {{ $sharedTextSecondary }};
            --app-shadow: 0 30px 60px rgba(2, 6, 23, 0.55);
            --app-card-shadow: 0 26px 60px rgba(2, 6, 23, 0.5);
            --app-nav-bg: color-mix(in srgb, {{ $sharedSurface2 }} 90%, transparent);
            --app-nav-border: color-mix(in srgb, {{ $sharedBrandBlue }} 25%, transparent);
            --app-input-bg: rgba(15, 23, 42, 0.75);
            --app-input-border: rgba(148, 163, 184, 0.3);
            --app-input-focus: color-mix(in srgb, {{ $sharedBrandBlue }} 60%, transparent);
            --app-scroll-thumb: color-mix(in srgb, {{ $sharedBrandBlue }} 45%, transparent);
            --app-highlight: color-mix(in srgb, {{ $sharedBrandBlue }} 22%, transparent);
            --sidebar-bg: color-mix(in srgb, {{ $sharedSurface2 }} 94%, transparent);
            --sidebar-border: color-mix(in srgb, {{ $sharedBrandBlue }} 35%, transparent);
            --sidebar-text: {{ $sharedTextPrimary }};
            --sidebar-muted: {{ $sharedTextSecondary }};
            --sidebar-active-bg: color-mix(in srgb, {{ $sharedBrandBlue }} 22%, transparent);
            --bs-body-color: {{ $sharedTextPrimary }};
            --bs-body-bg: transparent;
            --bs-border-color: color-mix(in srgb, {{ $sharedTextSecondary }} 35%, transparent);
        }
        [data-theme="light"] {
            --app-bg: linear-gradient(165deg, #ffffff 0%, #f5f7ff 38%, #e7efff 100%);
            --app-surface: rgba(255, 255, 255, 0.97);
            --app-surface-soft: rgba(247, 249, 255, 0.94);
            --app-border: rgba(15, 23, 42, 0.08);
            --app-border-strong: rgba(148, 163, 184, 0.28);
            --app-text: #0f172a;
            --app-text-muted: rgba(71, 85, 105, 0.78);
            --app-shadow: 0 25px 45px rgba(15, 23, 42, 0.12);
            --app-card-shadow: 0 20px 36px rgba(148, 163, 184, 0.18);
            --app-nav-bg: rgba(255, 255, 255, 0.96);
            --app-nav-border: rgba(15, 23, 42, 0.1);
            --app-input-bg: rgba(255, 255, 255, 0.98);
            --app-input-border: rgba(148, 163, 184, 0.35);
            --app-input-focus: rgba(37, 99, 235, 0.45);
            --app-scroll-thumb: rgba(37, 99, 235, 0.3);
            --app-highlight: rgba(37, 99, 235, 0.1);
            --sidebar-bg: #ffffff;
            --sidebar-border: rgba(148,163,184,0.22);
            --sidebar-text: #0f172a;
            --sidebar-muted: rgba(71,85,105,0.8);
            --sidebar-active-bg: rgba(37,99,235,0.12);
            --bs-body-color: #0f172a;
            --bs-border-color: rgba(15,23,42,0.1);
        }
        body {
            padding-top: 4.5rem;
            padding-bottom: 3.25rem;
            min-height: 100vh;
            background: var(--app-bg);
            color: var(--app-text);
            font-family: var(--font-sans);
            font-size: var(--font-size-base, 1rem);
            line-height: 1.55;
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
            transition: background .3s ease, color .3s ease;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-sans);
            letter-spacing: -0.01em;
            color: var(--app-text);
        }
        .app-sidebar__brand-title,
        .navbar .navbar-brand {
            font-family: var(--font-sans);
        }
        a {
            color: var(--brand-blue);
            transition: color .2s ease;
        }
        a:hover,
        a:focus {
            color: var(--brand-blue-dark);
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
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .navbar .navbar-brand img {
            height: 38px;
            width: auto;
            display: block;
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
        box-shadow: 0 0 0 0.2rem color-mix(in srgb, var(--brand-blue) 25%, transparent);
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
    .app-main {
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
            background: #ffffff;
            color: #0f172a;
            z-index: 1035;
            overflow: hidden;
            font-size: 0.85rem;
            border-top: 1px solid rgba(148, 163, 184, 0.35);
            box-shadow: 0 -8px 20px rgba(15, 23, 42, 0.1);
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
            gap: 4rem;
            padding: 0.6rem 0;
            animation: ticker 55s linear infinite;
            align-items: center;
        }
        .running-track > span {
            display: inline-flex;
            align-items: center;
            gap: 1rem;
        }
        .marquee-entry {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: .08em;
            color: #0f172a;
        }
        .marquee-label {
            color: rgba(30, 64, 175, 0.9);
            letter-spacing: 0.16em;
            font-size: 0.78rem;
        }
        .marquee-borrower {
            color: #0ea5e9;
            letter-spacing: 0.2em;
        }
        .marquee-dot {
            color: rgba(234, 179, 8, 0.9);
            font-size: 1.2rem;
        }
        .marquee-sep {
            color: rgba(71, 85, 105, 0.85);
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
    <a class="navbar-brand fw-semibold text-uppercase" href="{{ route('landing') }}">
      @auth
        <img src="{{ asset('images/logo-pusdatekin.svg') }}" alt="SARPRAS PUSDATEKIN">
      @else
        <span>SARPRAS PUSDATEKIN</span>
      @endauth
    </a>
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
            ['route' => 'reports.loans', 'label' => 'Laporan Peminjaman', 'active' => request()->routeIs('reports.loans'), 'icon' => '&#128209;'],
            ['route' => 'reports.returns', 'label' => 'Laporan Pengembalian', 'active' => request()->routeIs('reports.returns'), 'icon' => '&#128259;'],
          ],
        ];
        if(auth()->user() && auth()->user()->role==='admin') {
          $menuSections['Administrasi'][] = ['route' => 'users.index', 'label' => 'Anggota', 'active' => request()->routeIs('users.*'), 'icon' => '&#128101;'];
          $menuSections['Administrasi'][] = ['route' => 'settings.landing', 'label' => 'Pengaturan Landing', 'active' => request()->routeIs('settings.*'), 'icon' => '&#9881;'];
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
    </aside>
    <main class="app-main">
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
<main class="app-main app-main--guest">
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
@endauth

@php
  $activeLoansTicker = \App\Models\Loan::with('asset')
      ->whereIn('status', ['borrowed', 'partial'])
      ->orderByDesc('loan_date')
      ->get()
      ->groupBy(function ($loan) {
          return $loan->batch_code ?: ('loan-' . $loan->id);
      })
      ->map(function ($group) {
          $first = $group->first();
          $loanDate = $group->min('loan_date');
          $plannedReturn = $group->filter(fn($loan) => $loan->return_date_planned)->min('return_date_planned');
          $activity = trim((string) ($first->activity_name ?? ''));
          if ($activity === '') {
              $activity = trim((string) ($first->notes ?? ''));
          }
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

          return (object) [
              'borrower_name' => $first->borrower_name,
              'activity' => $activity !== '' ? $activity : 'Tidak ada keterangan kegiatan',
              'total_quantity' => (int) $group->sum('quantity'),
              'return_date_planned' => $plannedReturn,
              'assets_preview' => $assetsLabels->take(2)->implode(' • '),
          ];
      })
      ->values();

  $tickerEntries = [];
  foreach ($activeLoansTicker as $loan) {
      $borrower = e($loan->borrower_name ?? 'Peminjam');
      $activity = e(\Illuminate\Support\Str::limit($loan->activity ?? 'Tidak ada keterangan kegiatan', 80));
      $assets = e($loan->assets_preview ?: 'Sarana tidak ditemukan');
      $quantity = e((int) ($loan->total_quantity ?? 0));
      $dueDate = e(optional($loan->return_date_planned)->format('d M Y') ?: 'Tanpa batas');
      $tickerEntries[] = "<span class=\"marquee-entry\"><span class=\"marquee-label\">Nama Peminjam:</span> <span class=\"marquee-borrower\">{$borrower}</span> <span class=\"marquee-sep\">|</span> <span class=\"marquee-label\">Nama Kegiatan:</span> {$activity} <span class=\"marquee-sep\">|</span> <span class=\"marquee-label\">Alat:</span> {$assets} ({$quantity} unit) <span class=\"marquee-sep\">|</span> <span class=\"marquee-label\">Target Kembali:</span> {$dueDate}</span>";
  }
  if (empty($tickerEntries)) {
      $tickerEntries[] = '<span class="marquee-entry">Belum ada peminjaman aktif.</span>';
  }
  $tickerHtml = '<span class="marquee-entry"><span class="marquee-label">Monitoring SARPRAS PUSDATEKIN :</span></span> ' . implode('<span class="marquee-dot">&bull;</span>', $tickerEntries);
@endphp
<div class="running-info" aria-live="polite">
  <div class="running-track">
    <span>{!! $tickerHtml !!}</span>
    <span>{!! $tickerHtml !!}</span>
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







