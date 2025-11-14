<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SARPRAS PUSDATEKIN' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --brand-blue: #2563eb;
            --brand-blue-dark: #1d4ed8;
            --brand-cyan: #38bdf8;
            --brand-ink: #020617;
            --brand-cloud: #f8fafc;
            --brand-slate: #0f172a;
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
            background: rgba(248, 250, 252, 0.9);
            backdrop-filter: blur(6px);
            border-bottom: 1px solid rgba(15, 23, 42, 0.08);
            position: sticky;
            top: 0;
            z-index: 1200;
            transition: background .3s ease, border-color .3s ease, box-shadow .3s ease;
        }
        .landing-navbar.is-scrolled {
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
        main.container {
            padding-top: 4rem;
            padding-bottom: 5rem;
        }
        footer {
            background: rgba(248, 250, 252, 0.95);
            border-top: 1px solid rgba(15, 23, 42, 0.1);
            color: var(--text-muted);
        }
    </style>
    @stack('styles')
</head>
<body>
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

<main class="container">
    @yield('content')
</main>

<footer class="text-center small py-4">
    &copy; {{ now()->year }} SARPRAS PUSDATEKIN &ndash; Sarana Prasarana BPIP.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    (function () {
        const landingNav = document.querySelector('.landing-navbar');
        const handleScroll = () => {
            if (!landingNav) {
                return;
            }
            landingNav.classList.toggle('is-scrolled', window.scrollY > 12);
        };
        handleScroll();
        window.addEventListener('scroll', handleScroll, { passive: true });
    })();
</script>
@stack('scripts')
</body>
</html>
