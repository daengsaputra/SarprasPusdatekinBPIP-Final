<div class="nav-header">
    <a href="{{ route('dashboard') }}" class="brand-logo" aria-label="SARPRAS">
        <img class="logo-abbr" src="{{ asset('evanto/assets/images/logo.avif') }}" alt="logo-abbr">
        <img class="brand-title" src="{{ asset('evanto/assets/images/logo-text.avif') }}" alt="logo-title">
    </a>

    <div class="nav-control">
        <div class="hamburger">
            <span class="line"></span><span class="line"></span><span class="line"></span>
        </div>
    </div>
</div>

<header class="header">
    <div class="header-content">
        <nav class="navbar navbar-expand">
            <div class="collapse navbar-collapse justify-content-between sarpras-header-collapse">
                <div class="header-left sarpras-header-left">
                    <div class="dashboard_bar">{{ trim($__env->yieldContent('title', 'Dashboard')) }}</div>
                </div>
                <ul class="navbar-nav header-right sarpras-header-right">
                    <li class="nav-item">
                        <form>
                            <div class="input-group search-area d-lg-inline-flex d-none me-3">
                                <div class="input-group-append">
                                    <button class="input-group-text rounded-0 rounded-start pe-2 border-0" type="button">
                                        <i class="flaticon-381-search-2"></i>
                                    </button>
                                </div>
                                <input type="text" class="form-control ps-2 border-0" placeholder="Search here" aria-label="Search">
                            </div>
                        </form>
                    </li>
                    <li class="nav-item dropdown notification_dropdown d-none d-lg-flex">
                        <a class="nav-link dz-fullscreen" aria-label="Fullscreen">
                            <svg id="icon-full" viewBox="0 0 24 24" width="24" height="24" stroke="var(--bs-primary)" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"></path>
                            </svg>
                        </a>
                    </li>
                    <li class="nav-item dropdown header-profile">
                        <a class="nav-link" href="javascript:void(0)" role="button" data-bs-toggle="dropdown">
                            <img src="{{ asset('evanto/assets/images/avatar/small/avatar1.webp') }}" width="20" alt="user">
                            <div class="header-info">
                                <span class="name text-black">{{ auth()->user()->name ?? 'User' }}</span>
                                <small>Administrator</small>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a href="{{ route('dashboard') }}" class="dropdown-item">
                                    <i class="fa fa-home text-primary"></i><span class="ms-2">Dashboard</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('logout') }}" class="dropdown-item"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa fa-sign-out-alt text-danger"></i><span class="ms-2 text-danger">Logout</span>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>
