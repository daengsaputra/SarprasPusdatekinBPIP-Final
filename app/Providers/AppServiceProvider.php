<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        $activeTheme = SiteSetting::landingTheme();
        $activeSurfaces = SiteSetting::landingThemeSurfaces();

        View::share('activeLandingTheme', $activeTheme);
        View::share('activeLandingThemeSurfaces', $activeSurfaces);
    }
}

