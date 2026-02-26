@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
<div class="authincation h-100">
    <div class="container h-100">
        <div class="row justify-content-center h-100 align-items-center">
            <div class="col-md-6">
                <div class="card p-5 shadow-lg">
                    <h4 class="text-center mb-4">Forgot Password</h4>
                    <p class="text-center text-muted mb-4">Masukkan email untuk menerima tautan reset password.</p>

                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="form-label"><strong>Email</strong></label>
                            <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100">Send Reset Link</button>
                    </form>

                    <div class="mt-3 text-center">
                        <a href="{{ route('login') }}">Back to login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
