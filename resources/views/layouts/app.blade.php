<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | {{ config('app.name', 'SARPRAS') }}</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('evanto/assets/images/favicon.avif') }}">

    @include('layouts.head-css')
    @stack('styles')
</head>
<body data-theme-version="light" data-layout="vertical" data-nav-headerbg="color_1" data-headerbg="color_1" data-sidebar-style="full" data-sidebarbg="color_1" data-sidebar-position="fixed" data-header-position="fixed" data-container="wide" direction="ltr">
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>

    <div id="main-wrapper">
        @include('layouts.topbar')
        @include('layouts.sidebar')

        @yield('content')

        @include('layouts.footer')
    </div>

    @include('layouts.vendor-scripts')
    @stack('scripts')
    @stack('script')
</body>
</html>
