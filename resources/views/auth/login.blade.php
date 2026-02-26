@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="authincation h-100">
    <div class="container h-100">
        <div class="row justify-content-center h-100 align-items-center">
            <div class="col-md-6">
                <div class="card p-5 shadow-lg">
                    <div class="text-center mb-3">
                        <a href="{{ route('root') }}" class="brand-logo" aria-label="SARPRAS">
                            <img src="{{ asset('evanto/assets/images/logo-full.avif') }}" alt="logo" onerror="this.style.display='none'">
                            <h3 class="mb-0">SARPRAS</h3>
                        </a>
                    </div>

                    <h4 class="text-center mb-4">Sign in your account</h4>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label class="form-label"><strong>Email / Username</strong></label>
                            <input id="login" type="text" class="form-control form-control-lg @error('login') is-invalid @enderror"
                                   name="login" value="{{ old('login') }}" required autofocus autocomplete="username" maxlength="255">
                            @error('login')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label"><strong>Password</strong></label>
                            <div class="position-relative">
                                <input id="password" type="password" autocomplete="current-password"
                                       class="form-control form-control-lg dz-password @error('password') is-invalid @enderror"
                                       name="password" required placeholder="Enter your password">
                                <span class="show-pass position-absolute top-50 end-0 me-2 translate-middle">
                                    <span class="show"><i class="fa fa-eye-slash"></i></span>
                                    <span class="hide"><i class="fa fa-eye"></i></span>
                                </span>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row d-flex justify-content-between mt-4 mb-2 flex-wrap">
                            <div class="form-group mb-3">
                                <div class="custom-control custom-checkbox ms-1">
                                    <input type="checkbox" class="form-check-input" name="remember" id="basic_checkbox_1" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="basic_checkbox_1">Remember my preference</label>
                                </div>
                            </div>
                            @if (Route::has('password.request'))
                                <div class="form-group">
                                    <a href="{{ route('password.request') }}">Forgot Password?</a>
                                </div>
                            @endif
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg w-100">Sign Me In</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
