<div class="deznav">
    <div class="deznav-scroll">
        <ul class="metismenu sarpras-menu-list" id="menu">
            <li class="menu-title sarpras-menu-title">UTAMA</li>
            <li class="{{ request()->routeIs('dashboard') || request()->routeIs('home') ? 'mm-active' : '' }}">
                <a href="{{ route('dashboard') }}" class="ai-icon sarpras-menu-link {{ request()->routeIs('dashboard') || request()->routeIs('home') ? 'active' : '' }}" data-menu-label="Dashboard">
                    <i class="flaticon-381-networking"></i>
                    <span class="nav-text">Dashboard</span>
                    <i class="fa fa-angle-right sarpras-menu-arrow"></i>
                </a>
            </li>

            <li class="menu-title sarpras-menu-title">PENGATURAN</li>
            <li class="{{ request()->routeIs('assets.loanable') ? 'mm-active' : '' }}">
                <a href="{{ route('assets.loanable') }}" class="ai-icon sarpras-menu-link {{ request()->routeIs('assets.loanable') ? 'active' : '' }}" data-menu-label="Data Barang">
                    <i class="flaticon-381-box"></i>
                    <span class="nav-text">Data Barang</span>
                    <i class="fa fa-angle-right sarpras-menu-arrow"></i>
                </a>
            </li>
            <li class="{{ request()->routeIs('assets.index') ? 'mm-active' : '' }}">
                <a href="{{ route('assets.index') }}" class="ai-icon sarpras-menu-link {{ request()->routeIs('assets.index') ? 'active' : '' }}" data-menu-label="Data Aset">
                    <i class="flaticon-381-file"></i>
                    <span class="nav-text">Data Aset</span>
                    <i class="fa fa-angle-right sarpras-menu-arrow"></i>
                </a>
            </li>

            <li class="menu-title sarpras-menu-title">OPERASIONAL</li>
            <li class="{{ request()->routeIs('loans.*') ? 'mm-active' : '' }}">
                <a href="{{ route('loans.create') }}" class="ai-icon sarpras-menu-link {{ request()->routeIs('loans.*') ? 'active' : '' }}" data-menu-label="Peminjaman">
                    <i class="flaticon-381-notepad"></i>
                    <span class="nav-text">Peminjaman</span>
                    <i class="fa fa-angle-right sarpras-menu-arrow"></i>
                </a>
            </li>
            <li class="{{ request()->routeIs('reports.*') ? 'mm-active' : '' }}">
                <a href="{{ route('reports.index') }}" class="ai-icon sarpras-menu-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" data-menu-label="Laporan">
                    <i class="flaticon-381-file-1"></i>
                    <span class="nav-text">Laporan</span>
                    <i class="fa fa-angle-right sarpras-menu-arrow"></i>
                </a>
            </li>

            <li class="menu-title sarpras-menu-title">ADMINISTRASI</li>
            <li class="{{ request()->routeIs('users.*') ? 'mm-active' : '' }}">
                <a href="{{ route('users.index') }}" class="ai-icon sarpras-menu-link {{ request()->routeIs('users.*') ? 'active' : '' }}" data-menu-label="Daftar Anggota">
                    <i class="flaticon-381-user"></i>
                    <span class="nav-text">Daftar Anggota</span>
                    <i class="fa fa-angle-right sarpras-menu-arrow"></i>
                </a>
            </li>
            <li class="{{ request()->routeIs('settings.landing*') ? 'mm-active' : '' }}">
                <a href="{{ route('settings.landing') }}" class="ai-icon sarpras-menu-link {{ request()->routeIs('settings.landing*') ? 'active' : '' }}" data-menu-label="Pengaturan Landing">
                    <i class="flaticon-381-settings-2"></i>
                    <span class="nav-text">Pengaturan Landing</span>
                    <i class="fa fa-angle-right sarpras-menu-arrow"></i>
                </a>
            </li>
        </ul>

        <div class="deznav-footer">
            <h6 class="sarpras-menu-title mb-3">LOGOUT</h6>
            <a href="{{ route('logout') }}" class="btn sarpras-logout-btn w-100"
               onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                <i class="flaticon-381-turn-off me-2"></i> Keluar
            </a>
            <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</div>
