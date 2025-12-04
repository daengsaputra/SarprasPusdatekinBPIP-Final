<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Bagikan tema landing hanya jika tabel sudah tersedia.
        if (Schema::hasTable('site_settings')) {
            $activeTheme = SiteSetting::landingTheme();
            $activeSurfaces = SiteSetting::landingThemeSurfaces();

            View::share('activeLandingTheme', $activeTheme);
            View::share('activeLandingThemeSurfaces', $activeSurfaces);
        }
    }
}
