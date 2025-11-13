<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SARPRAS PUSDATEKIN' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @php
        $themeSurfaces = $activeLandingThemeSurfaces ?? \App\Models\SiteSetting::landingThemeSurfaces();
        $brandBlue = data_get($themeSurfaces, 'accent', '#1d4ed8');
        $brandCyan = data_get($themeSurfaces, 'accentSoft', '#38bdf8');
        $surface1 = data_get($themeSurfaces, 'surface1', 'linear-gradient(140deg, #0b1220 0%, #05060a 55%, #020205 100%)');
        $surface2 = data_get($themeSurfaces, 'surface2', 'rgba(15, 23, 42, 0.78)');
        $surface3 = data_get($themeSurfaces, 'surface3', 'rgba(15, 23, 42, 0.55)');
        $textPrimary = data_get($themeSurfaces, 'text_primary', '#e2e8f0');
        $textSecondary = data_get($themeSurfaces, 'text_secondary', 'rgba(226, 232, 240, 0.72)');
        $borderSoft = 'color-mix(in srgb, ' . $textSecondary . ' 35%, transparent)';
    @endphp
    <style>
        :root {
            --brand-blue: {{ $brandBlue }};
            --brand-blue-dark: {{ $brandBlue }};
            --brand-cyan: {{ $brandCyan }};
            --brand-ink: #020617;
            --brand-cloud: #f8fafc;
            --brand-slate: #0f172a;
            --surface-1: {{ $surface1 }};
            --surface-2: {{ $surface2 }};
            --surface-3: {{ $surface3 }};
            --border-soft: {{ $borderSoft }};
            --text-primary: {{ $textPrimary }};
            --text-secondary: {{ $textSecondary }};
            --text-muted: color-mix(in srgb, {{ $textSecondary }} 85%, transparent);
            --bs-body-bg: transparent;
            --bs-body-color: var(--text-primary);
            --bs-heading-color: var(--text-primary);
            --bs-link-color: var(--brand-cyan);
            --bs-link-hover-color: #7dd3fc;
            --bs-emphasis-color: var(--text-primary);
            --bs-border-color: var(--border-soft);
            --bs-secondary-color: rgba(203, 213, 225, 0.7);
            --bs-body-secondary-color: rgba(203, 213, 225, 0.7);
            --bs-body-tertiary-color: rgba(148, 163, 184, 0.55);
            --bs-body-secondary-bg: rgba(15, 23, 42, 0.55);
            --bs-body-tertiary-bg: rgba(15, 23, 42, 0.45);
            --bs-card-bg: var(--surface-2);
            --bs-card-border-color: var(--border-soft);
            --bs-primary: {{ $brandBlue }};
            --bs-primary-rgb: 37, 99, 235;
            --bs-success: #22c55e;
            --bs-success-rgb: 34, 197, 94;
            --bs-warning: #f59e0b;
            --bs-warning-rgb: 245, 158, 11;
            --bs-primary-bg-subtle: color-mix(in srgb, {{ $brandBlue }} 18%, transparent);
            --bs-primary-border-subtle: color-mix(in srgb, {{ $brandBlue }} 35%, transparent);
            --bs-primary-text-emphasis: rgba(191, 219, 254, 0.95);
            --bs-success-bg-subtle: rgba(34, 197, 94, 0.15);
            --bs-success-border-subtle: rgba(34, 197, 94, 0.3);
            --bs-success-text-emphasis: #bbf7d0;
            --bs-warning-bg-subtle: rgba(250, 204, 21, 0.18);
            --bs-warning-border-subtle: rgba(250, 204, 21, 0.35);
            --bs-warning-text-emphasis: #facc15;
        }
        [data-theme="light"] {
            --surface-1: linear-gradient(160deg, #ffffff 0%, #eef2ff 40%, #e2e8f0 100%);
            --surface-2: rgba(241, 245, 249, 0.92);
            --surface-3: rgba(226, 232, 240, 0.78);
            --border-soft: rgba(15, 23, 42, 0.08);
            --text-primary: #0f172a;
            --text-secondary: rgba(30, 41, 59, 0.78);
            --text-muted: rgba(71, 85, 105, 0.68);
            --bs-body-bg: transparent;
            --bs-body-color: var(--text-primary);
            --bs-heading-color: var(--text-primary);
            --bs-link-color: var(--brand-blue);
            --bs-link-hover-color: var(--brand-blue-dark);
            --bs-emphasis-color: var(--text-primary);
            --bs-border-color: rgba(15, 23, 42, 0.08);
            --bs-secondary-color: rgba(71, 85, 105, 0.7);
            --bs-body-secondary-color: rgba(71, 85, 105, 0.7);
            --bs-body-tertiary-color: rgba(100, 116, 139, 0.6);
            --bs-body-secondary-bg: rgba(241, 245, 249, 0.92);
            --bs-body-tertiary-bg: rgba(226, 232, 240, 0.78);
            --bs-card-bg: var(--surface-2);
            --bs-card-border-color: var(--border-soft);
            --bs-primary: var(--brand-blue);
            --bs-primary-rgb: 37, 99, 235;
            --bs-success: #22c55e;
            --bs-success-rgb: 34, 197, 94;
            --bs-warning: #f59e0b;
            --bs-warning-rgb: 245, 158, 11;
            --bs-primary-bg-subtle: color-mix(in srgb, var(--brand-blue) 18%, transparent);
            --bs-primary-border-subtle: color-mix(in srgb, var(--brand-blue) 25%, transparent);
            --bs-primary-text-emphasis: var(--brand-blue-dark);
            --bs-success-bg-subtle: rgba(34, 197, 94, 0.12);
            --bs-success-border-subtle: rgba(34, 197, 94, 0.25);
            --bs-success-text-emphasis: #166534;
            --bs-warning-bg-subtle: rgba(250, 204, 21, 0.16);
            --bs-warning-border-subtle: rgba(250, 204, 21, 0.28);
            --bs-warning-text-emphasis: #92400e;
        }
        body {
            min-height: 100vh;
            background: var(--surface-1);
            color: var(--text-primary);
            transition: background .35s ease, color .35s ease;
        }
        .text-muted {
            color: var(--text-muted) !important;
        }
        a { color: var(--brand-cyan); }
        a:hover { color: #7dd3fc; }
        .landing-navbar {
            background: rgba(2, 6, 23, 0.82);
            backdrop-filter: blur(6px);
            border-bottom: 1px solid rgba(59, 130, 246, 0.25);
            position: sticky;
            top: 0;
            z-index: 1200;
            transition: background .3s ease, border-color .3s ease, box-shadow .3s ease;
        }
        .landing-navbar.is-scrolled {
            background: rgba(2, 6, 23, 0.95);
            border-bottom-color: rgba(59, 130, 246, 0.4);
            box-shadow: 0 12px 32px rgba(2, 6, 23, 0.4);
        }
        [data-theme="light"] .landing-navbar {
            background: rgba(248, 250, 252, 0.9);
            border-bottom: 1px solid rgba(15, 23, 42, 0.08);
        }
        [data-theme="light"] .landing-navbar.is-scrolled {
            background: rgba(255, 255, 255, 0.95);
            border-bottom-color: rgba(15, 23, 42, 0.14);
            box-shadow: 0 12px 32px rgba(15, 23, 42, 0.18);
        }
        .navbar-brand {
            color: var(--text-primary) !important;
            letter-spacing: 0.18em;
        }
        .nav-link { color: var(--text-secondary) !important; }
        .nav-link:hover, .nav-link:focus { color: var(--text-primary) !important; }
        .btn-cta {
            background: linear-gradient(120deg, var(--brand-blue), var(--brand-blue-dark));
            border: none;
            color: #fff !important;
            box-shadow: 0 12px 30px rgba(30, 64, 175, 0.35);
        }
        .btn-cta:hover {
            background: linear-gradient(120deg, var(--brand-blue-dark), #1d4ed8);
        }
        /* Theme Slider Toggle */
        .theme-toggle-slider {
            cursor: pointer;
            width: 64px;
            height: 32px;
            border-radius: 999px;
            position: fixed;
            top: 1.2rem;
            right: 1.5rem;
            z-index: 1500;
            background: linear-gradient(135deg, rgba(20, 31, 49, 0.95), rgba(8, 14, 26, 0.9));
            border: 1px solid rgba(148, 163, 184, 0.35);
            box-shadow: inset 0 0 12px rgba(4, 6, 12, 0.65);
            transition: background .3s ease, border-color .3s ease, box-shadow .3s ease;
        }
        .theme-toggle-slider input { display: none; }
        .theme-toggle-slider .slider-knob {
            position: absolute;
            top: 3px;
            left: 3px;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, #f8fbff, #dbeafe);
            box-shadow: 0 6px 16px rgba(5, 9, 20, 0.45);
            transition: transform .3s ease, background .3s ease, box-shadow .3s ease;
        }
        .theme-toggle-slider input:checked + .slider-knob {
            transform: translateX(30px);
            background: radial-gradient(circle at 30% 30%, #fff8e5, #fed89b);
            box-shadow: 0 6px 16px rgba(249, 178, 51, 0.45);
        }
        [data-theme="light"] .theme-toggle-slider {
            background: linear-gradient(135deg, rgba(226, 232, 240, 0.95), rgba(203, 213, 225, 0.85));
            border-color: rgba(148, 163, 184, 0.5);
            box-shadow: inset 0 0 12px rgba(255, 255, 255, 0.6);
        }
        main.container {
            padding-top: 4rem;
            padding-bottom: 5rem;
        }
        footer {
            background: rgba(2, 6, 23, 0.82);
            border-top: 1px solid var(--border-soft);
            color: var(--text-muted);
        }
        [data-theme="light"] footer {
            background: rgba(248, 250, 252, 0.95);
        }
    </style>
    @stack('styles')
</head>
<body data-theme="dark">
<nav class="navbar landing-navbar navbar-expand-lg py-3">
    <div class="container">
        <a class="navbar-brand fw-semibold text-uppercase" href="{{ url('/') }}">SARPRAS PUSDATEKIN</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#landingNav" aria-controls="landingNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="landingNav">
            <ul class="navbar-nav ms-auto gap-2 align-items-lg-center">
                <li class="nav-item"><a class="nav-link" href="#fitur">Fitur</a></li>
                <li class="nav-item"><a class="nav-link" href="#stok">Stok</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('assets.loanable') }}">Data Barang</a></li>
                <li class="nav-item"><a class="btn btn-cta px-4" href="{{ route('login') }}">Login</a></li>
            </ul>
        </div>
    </div>
</nav>

<label for="themeToggleInput" class="theme-toggle-slider" aria-label="Toggle theme">
    <input type="checkbox" id="themeToggleInput">
    <span class="slider-knob">
        <svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.25a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zM7.5 12a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM18.894 6.106a.75.75 0 010 1.06l-1.591 1.59a.75.75 0 11-1.06-1.06l1.59-1.59a.75.75 0 011.06 0zM21.75 12a.75.75 0 01-.75.75h-2.25a.75.75 0 010-1.5H21a.75.75 0 01.75.75zM17.894 17.894a.75.75 0 01-1.06 0l-1.59-1.591a.75.75 0 111.06-1.06l1.59 1.59a.75.75 0 010 1.06zM12 18.75a.75.75 0 01-.75.75v2.25a.75.75 0 011.5 0V19.5a.75.75 0 01-.75-.75zM5.106 17.894a.75.75 0 010-1.06l1.59-1.59a.75.75 0 111.06 1.06l-1.59 1.59a.75.75 0 01-1.06 0zM3 12a.75.75 0 01.75-.75h2.25a.75.75 0 010 1.5H3.75A.75.75 0 013 12zM6.106 5.106a.75.75 0 011.06 0l1.59 1.591a.75.75 0 01-1.06 1.06l-1.59-1.59a.75.75 0 010-1.06z"></path></svg>
        <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M9.528 1.718a.75.75 0 01.162.819A8.97 8.97 0 009 6a9 9 0 009 9 8.97 8.97 0 004.463-.69a.75.75 0 01.981.981A10.503 10.503 0 0118 19.5a10.5 10.5 0 01-10.5-10.5c0-1.563.34-3.056.942-4.432a.75.75 0 01.819-.162z" clip-rule="evenodd"></path></svg>
    </span>
</label>

<main class="container">
    @yield('content')
</main>

<footer class="text-center small py-4">
    &copy; {{ now()->year }} SARPRAS PUSDATEKIN &ndash; Sarana Prasarana BPIP.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const bodyEl = document.body;
    const toggleInput = document.getElementById('themeToggleInput');
    const landingNav = document.querySelector('.landing-navbar');
    const LANDING_STORAGE_KEY = 'landingTheme';
    const APP_STORAGE_KEY = 'appTheme';
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const storedTheme = localStorage.getItem(LANDING_STORAGE_KEY) || localStorage.getItem(APP_STORAGE_KEY);

    const applyTheme = (theme, { persist = true } = {}) => {
        const safeTheme = theme === 'light' ? 'light' : 'dark';
        bodyEl.dataset.theme = safeTheme;
        if (toggleInput) {
            toggleInput.checked = safeTheme === 'light';
        }
        if (persist) {
            localStorage.setItem(LANDING_STORAGE_KEY, safeTheme);
            localStorage.setItem(APP_STORAGE_KEY, safeTheme);
        }
        bodyEl.dispatchEvent(new CustomEvent('app:theme-changed', { detail: { theme: safeTheme } }));
    };

    // Terapkan tema saat halaman dimuat
    const initialTheme = storedTheme === 'light' || storedTheme === 'dark'
        ? storedTheme
        : (prefersDark ? 'dark' : 'light');
    applyTheme(initialTheme, { persist: false });

    if (toggleInput) {
        toggleInput.addEventListener('change', () => {
            const newTheme = toggleInput.checked ? 'light' : 'dark';
            applyTheme(newTheme);
        });
    }

    window.addEventListener('storage', (event) => {
        if (event.key === LANDING_STORAGE_KEY || event.key === APP_STORAGE_KEY) {
            const updatedTheme = event.newValue === 'light' ? 'light' : 'dark';
            applyTheme(updatedTheme, { persist: false });
        }
    });

    const handleScroll = () => {
        if (!landingNav) {
            return;
        }
        landingNav.classList.toggle('is-scrolled', window.scrollY > 12);
    };
    handleScroll();
    window.addEventListener('scroll', handleScroll, { passive: true });
</script>
@stack('scripts')
</body>
</html>
