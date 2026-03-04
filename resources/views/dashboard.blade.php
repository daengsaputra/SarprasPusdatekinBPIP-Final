@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<main class="content-body">
    <div class="container-fluid">
        <!-- Welcome Row -->
        <div class="row mb-4">
            <div class="col-xl-12">
                <h4 class="text-black font-w600 mb-1">Selamat Datang, {{ auth()->user()->name ?? 'User' }}!</h4>
                <p class="text-muted mb-0">Sistem Arsip Barang Rupa (SARPRAS) - Dashboard Utama</p>
            </div>
        </div>

        <!-- Activity Cards Row -->
        <div class="row">
            <div class="col-sm-6 col-lg-3">
                <div class="card avtivity-card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <span class="activity-icon bgl-success me-md-4 me-3">
                                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M20 2C10.06 2 2 10.06 2 20c0 9.94 8.06 18 18 18s18-8.06 18-18S29.94 2 20 2zm0 32c-7.73 0-14-6.27-14-14s6.27-14 14-14 14 6.27 14 14-6.27 14-14 14z" fill="#27BC48"/>
                                    <path d="M20.5 8h-1v13l10.76 6.44.5-.87-10.26-6.12V8z" fill="#27BC48"/>
                                </svg>
                            </span>
                            <div class="media-body">
                                <p class="fs-14 mb-2">Total Barang</p>
                                <span class="title text-black font-w600">{{ \App\Models\Asset::count() }}</span>
                            </div>
                        </div>
                        <div class="progress" style="height:5px;">
                            <div class="progress-bar bg-success" style="width: 100%; height:5px;" role="progressbar"></div>
                        </div>
                    </div>
                    <div class="effect bg-success"></div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card avtivity-card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <span class="activity-icon bgl-secondary  me-md-4 me-3">
                                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M20 2C10.06 2 2 10.06 2 20c0 9.94 8.06 18 18 18s18-8.06 18-18S29.94 2 20 2zm0 32c-7.73 0-14-6.27-14-14s6.27-14 14-14 14 6.27 14 14-6.27 14-14 14z" fill="#A02CFA"/>
                                    <path d="M20.5 8h-1v13l10.76 6.44.5-.87-10.26-6.12V8z" fill="#A02CFA"/>
                                </svg>
                            </span>
                            <div class="media-body">
                                <p class="fs-14 mb-2">Total User</p>
                                <span class="title text-black font-w600">{{ \App\Models\User::count() }}</span>
                            </div>
                        </div>
                        <div class="progress" style="height:5px;">
                            <div class="progress-bar bg-secondary" style="width: 75%; height:5px;" role="progressbar"></div>
                        </div>
                    </div>
                    <div class="effect bg-secondary"></div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card avtivity-card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <span class="activity-icon bgl-danger me-md-4 me-3">
                                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M20 2C10.06 2 2 10.06 2 20c0 9.94 8.06 18 18 18s18-8.06 18-18S29.94 2 20 2zm0 32c-7.73 0-14-6.27-14-14s6.27-14 14-14 14 6.27 14 14-6.27 14-14 14z" fill="#FF3282"/>
                                    <path d="M20.5 8h-1v13l10.76 6.44.5-.87-10.26-6.12V8z" fill="#FF3282"/>
                                </svg>
                            </span>
                            <div class="media-body">
                                <p class="fs-14 mb-2">Peminjaman Aktif</p>
                                <span class="title text-black font-w600">{{ \App\Models\Loan::where('status', 'active')->count() ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="progress" style="height:5px;">
                            <div class="progress-bar bg-danger" style="width: 60%; height:5px;" role="progressbar"></div>
                        </div>
                    </div>
                    <div class="effect bg-danger"></div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card avtivity-card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <span class="activity-icon bgl-warning  me-md-4 me-3">
                                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M20 2C10.06 2 2 10.06 2 20c0 9.94 8.06 18 18 18s18-8.06 18-18S29.94 2 20 2zm0 32c-7.73 0-14-6.27-14-14s6.27-14 14-14 14 6.27 14 14-6.27 14-14 14z" fill="#FFBC11"/>
                                    <path d="M20.5 8h-1v13l10.76 6.44.5-.87-10.26-6.12V8z" fill="#FFBC11"/>
                                </svg>
                            </span>
                            <div class="media-body">
                                <p class="fs-14 mb-2">Total Pinjaman</p>
                                <span class="title text-black font-w600">{{ \App\Models\Loan::count() ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="progress" style="height:5px;">
                            <div class="progress-bar bg-warning" style="width: 85%; height:5px;" role="progressbar"></div>
                        </div>
                    </div>
                    <div class="effect bg-warning"></div>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row mt-4">
            <!-- Statistics & Info -->
            <div class="col-xl-8 col-xxl-9">
                <div class="row">
                    <!-- System Status -->
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header d-sm-flex d-block pb-0 border-0">
                                <div class="me-auto pe-3 mb-sm-0 mb-3">
                                    <h4 class="text-black fs-20 font-w600">Status Sistem</h4>
                                    <p class="fs-13 mb-0">Informasi terkini tentang sistem SARPRAS</p>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex justify-content-between align-items-center pb-3 border-bottom">
                                            <span class="fs-14">Server Status</span>
                                            <span class="badge bg-success">Online</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex justify-content-between align-items-center pb-3 border-bottom">
                                            <span class="fs-14">Database</span>
                                            <span class="badge bg-success">Connected</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex justify-content-between align-items-center pb-3 border-bottom">
                                            <span class="fs-14">Last Backup</span>
                                            <span class="fs-13">{{ now()->format('d M Y H:i') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex justify-content-between align-items-center pb-3 border-bottom">
                                            <span class="fs-14">System Version</span>
                                            <span class="fs-13">v1.0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activities -->
                    <div class="col-xl-12 mt-4">
                        <div class="card">
                            <div class="card-header d-sm-flex d-block pb-0 border-0">
                                <div class="me-auto pe-3">
                                    <h4 class="text-black fs-20 font-w600">Aktivitas Terbaru</h4>
                                    <p class="fs-13 mb-0">Pembaruan terkini dalam sistem</p>
                                </div>
                            </div>
                            <div class="card-body pt-3">
                                <div class="timeline">
                                    @forelse(\App\Models\Loan::latest()->take(5)->get() as $loan)
                                    <div class="timeline-item mb-3 pb-3 border-bottom">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1 font-w600">Peminjaman Barang</h6>
                                                <p class="fs-13 mb-0 text-muted">{{ $loan->asset?->nama_barang ?? 'N/A' }}</p>
                                            </div>
                                            <small class="text-muted">{{ $loan->created_at?->diffForHumans() ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                    @empty
                                    <p class="text-muted">Tidak ada aktivitas terbaru</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="col-xl-4 col-xxl-3">
                <div class="card">
                    <div class="card-header border-0 pb-0">
                        <h4 class="card-title mb-0">Info Singkat</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-4 pb-3 border-bottom">
                            <h6 class="mb-2 font-w600">Pengguna Saat Ini</h6>
                            <p class="mb-0 fs-14">
                                <strong>{{ auth()->user()->name ?? 'User' }}</strong><br>
                                <small class="text-muted">{{ auth()->user()->email ?? 'email@example.com' }}</small>
                            </p>
                        </div>
                        <div class="mb-4 pb-3 border-bottom">
                            <h6 class="mb-2 font-w600">Statistik</h6>
                            <ul class="list-unstyled">
                                <li class="d-flex justify-content-between mb-2">
                                    <span>Barang Tersedia:</span>
                                    <strong>{{ \App\Models\Asset::count() }}</strong>
                                </li>
                                <li class="d-flex justify-content-between mb-2">
                                    <span>Total User:</span>
                                    <strong>{{ \App\Models\User::count() }}</strong>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <span>Total Pinjaman:</span>
                                    <strong>{{ \App\Models\Loan::count() }}</strong>
                                </li>
                            </ul>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Lihat Profil</a>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="card mt-4">
                    <div class="card-header border-0 pb-0">
                        <h4 class="card-title mb-0">Menu Cepat</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <a href="{{ route('assets.index') }}" class="btn btn-light btn-sm w-100 text-start">
                                    <i class="fas fa-box me-2"></i> Data Barang
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('loans.create', ['fresh' => 1]) }}" class="btn btn-light btn-sm w-100 text-start">
                                    <i class="fas fa-handshake me-2"></i> Tambah Peminjaman
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('users.index') }}" class="btn btn-light btn-sm w-100 text-start">
                                    <i class="fas fa-users me-2"></i> Manajemen User
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('settings.landing') }}" class="btn btn-light btn-sm w-100 text-start">
                                    <i class="fas fa-cog me-2"></i> Pengaturan
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
