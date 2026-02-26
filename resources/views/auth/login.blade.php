@php($title = 'Masuk')
@extends('layouts.app')

@push('styles')
<style>
  .simple-auth {
    min-height: calc(100vh - 120px);
    display: grid;
    place-items: center;
    padding: 1rem;
  }
  .simple-auth__card {
    width: 100%;
    max-width: 560px;
    border-radius: 28px;
    background: #f3f5fb;
    border: 1px solid #dce2f0;
    box-shadow: 0 22px 45px rgba(15, 23, 42, 0.12);
    padding: 2rem 1.5rem;
  }
  [data-theme="light"] .simple-auth__card {
    background: #f3f5fb;
  }
  .simple-auth__logo {
    width: 110px;
    height: 110px;
    border-radius: 24px;
    margin: 0 auto 1.2rem;
    border: 1px solid #d7dceb;
    background: #eef2f8;
  }
  .simple-auth__title {
    margin: 0;
    text-align: center;
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 700;
    color: #1f2430;
  }
  .simple-auth__subtitle {
    margin: .8rem 0 1.5rem;
    text-align: center;
    color: #6f778a;
    line-height: 1.4;
    font-size: 1.05rem;
  }
  .simple-auth .alert {
    border-radius: 16px;
  }
  .simple-auth .form-label {
    font-weight: 700;
    color: #6a7387;
  }
  .simple-auth .form-control {
    min-height: 62px;
    border-radius: 16px;
    border-color: #b9c2d8;
    background: #e9eef8;
    font-size: 1.05rem;
  }
  .simple-auth .form-control:focus {
    border-color: #6b7ef3;
    box-shadow: 0 0 0 4px rgba(107, 126, 243, 0.15);
    background: #eef2fb;
  }
  .simple-auth__input-wrap {
    position: relative;
  }
  .simple-auth__icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #3c4355;
    font-size: 1.4rem;
    pointer-events: none;
  }
  .simple-auth__input-wrap .form-control {
    padding-left: 3rem;
  }
  .simple-auth__actions {
    display: flex;
    justify-content: flex-end;
    margin: .4rem 0 1.1rem;
  }
  .simple-auth__actions a {
    color: #5b6272;
    font-weight: 700;
    text-decoration: underline;
  }
  .simple-auth__btn {
    width: 100%;
    min-height: 62px;
    border: none;
    border-radius: 16px;
    font-size: 2rem;
    font-weight: 700;
    color: #fff;
    background: linear-gradient(140deg, #6a7ef0, #4f60d8);
  }
  .simple-auth__btn:hover {
    filter: brightness(1.03);
  }
</style>
@endpush

@section('content')
<div class="simple-auth">
  <div class="simple-auth__card">
    <div class="simple-auth__logo"></div>
    <h1 class="simple-auth__title">Selamat Datang</h1>
    <p class="simple-auth__subtitle">Masuk ke Akun Anda<br>Silahkan login menggunakan user dan password.</p>

    <form method="POST" action="{{ route('login.post') }}" class="row gy-3">
      @csrf
      <div class="col-12">
        <label class="form-label">User</label>
        <div class="simple-auth__input-wrap">
          <span class="simple-auth__icon"><i class='bx bx-envelope'></i></span>
          <input type="text" name="identifier" value="{{ old('identifier') }}" class="form-control @error('identifier') is-invalid @enderror" required autofocus>
          @error('identifier')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
      </div>

      <div class="col-12">
        <label class="form-label">Password</label>
        <div class="simple-auth__input-wrap">
          <span class="simple-auth__icon"><i class='bx bx-lock-alt'></i></span>
          <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
          @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
      </div>

      <div class="col-12 simple-auth__actions">
        <a href="{{ route('password.request') }}">Lupa Password?</a>
      </div>

      <div class="col-12">
        <button type="submit" class="simple-auth__btn">Login</button>
      </div>
    </form>
  </div>
</div>
@endsection
