@php($title = 'Masuk')
@extends('layouts.app')

@push('styles')
<style>
    .auth-hero {
        position: relative;
        overflow: hidden;
        border-radius: 28px;
        padding: 2.5rem;
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.24), rgba(15, 23, 42, 0.88));
        border: 1px solid rgba(59, 130, 246, 0.28);
        box-shadow: 0 25px 60px rgba(15, 23, 42, 0.55);
    }
    [data-theme="light"] .auth-hero {
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.18), rgba(255, 255, 255, 0.95));
        border-color: rgba(37, 99, 235, 0.24);
        box-shadow: 0 20px 48px rgba(37, 99, 235, 0.15);
    }
    .auth-hero::before,
    .auth-hero::after {
        content: '';
        position: absolute;
        border-radius: 999px;
        filter: blur(60px);
        opacity: 0.9;
    }
    .auth-hero::before {
        width: 220px;
        height: 220px;
        background: rgba(59, 130, 246, 0.45);
        top: -60px;
        right: -60px;
    }
    .auth-hero::after {
        width: 260px;
        height: 260px;
        background: rgba(56, 189, 248, 0.35);
        bottom: -80px;
        left: -40px;
    }
    .auth-hero-content {
        position: relative;
        z-index: 2;
        color: var(--app-text);
    }
    .auth-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.5rem 1rem;
        border-radius: 999px;
        background: rgba(15, 23, 42, 0.45);
        border: 1px solid rgba(148, 163, 184, 0.2);
        font-size: 0.8rem;
        letter-spacing: 0.24em;
        text-transform: uppercase;
        color: rgba(226, 232, 240, 0.9);
    }
    [data-theme="light"] .auth-badge {
        background: rgba(255, 255, 255, 0.85);
        color: rgba(30, 41, 59, 0.75);
    }
    .auth-list {
        display: grid;
        gap: 0.7rem;
        padding: 0;
        margin: 1.75rem 0 0;
        list-style: none;
    }
    .auth-list li {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: rgba(226, 232, 240, 0.85);
    }
    [data-theme="light"] .auth-list li {
        color: rgba(30, 41, 59, 0.74);
    }
    .auth-list svg {
        width: 18px;
        height: 18px;
        color: var(--brand-cyan);
        flex-shrink: 0;
    }
    .auth-card {
        position: relative;
        border-radius: 26px;
        background: linear-gradient(180deg, rgba(15, 23, 42, 0.92) 0%, rgba(15, 23, 42, 0.82) 100%);
        border: 1px solid rgba(59, 130, 246, 0.28);
        box-shadow: 0 32px 60px rgba(2, 6, 23, 0.5);
        overflow: hidden;
        backdrop-filter: blur(18px);
    }
    [data-theme="light"] .auth-card {
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.92) 0%, rgba(248, 250, 252, 0.98) 100%);
        border-color: rgba(148, 163, 184, 0.2);
        box-shadow: 0 26px 55px rgba(15, 23, 42, 0.18);
    }
    .auth-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(140% 140% at 0% 0%, rgba(56, 189, 248, 0.18), transparent 65%),
                    radial-gradient(120% 120% at 100% 0%, rgba(37, 99, 235, 0.15), transparent 70%);
        pointer-events: none;
    }
    .auth-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.75rem;
        gap: 1.5rem;
    }
    .auth-card h1 {
        font-size: 1.6rem;
        margin: 0;
        color: var(--app-text);
    }
    .auth-subtext {
        color: var(--app-text-muted);
    }
    .auth-card a {
        color: var(--brand-cyan);
    }
    .auth-card a:hover,
    .auth-card a:focus {
        color: #7dd3fc;
    }
    .auth-divider {
        text-align: center;
        position: relative;
        margin: 1.8rem 0;
        color: var(--app-text-muted);
        font-size: 0.85rem;
        letter-spacing: 0.12em;
        text-transform: uppercase;
    }
    .auth-divider::before,
    .auth-divider::after {
        content: '';
        position: absolute;
        top: 50%;
        width: 30%;
        height: 1px;
        background: rgba(148, 163, 184, 0.2);
    }
    .auth-divider::before {
        left: 0;
    }
    .auth-divider::after {
        right: 0;
    }
    .input-icon {
        position: absolute;
        inset-block: 0;
        left: 1rem;
        display: inline-flex;
        align-items: center;
        color: var(--app-text-muted);
        pointer-events: none;
    }
    .input-icon svg {
        width: 18px;
        height: 18px;
    }
    .form-control.input-with-icon {
        padding-left: 2.75rem;
        min-height: 3.15rem;
        border-radius: 16px;
        border-color: rgba(148, 163, 184, 0.28);
        background: rgba(15, 23, 42, 0.58);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.02);
        transition: border-color .22s ease, box-shadow .22s ease, background .22s ease;
    }
    [data-theme="light"] .form-control.input-with-icon {
        background: rgba(248, 250, 252, 0.9);
        border-color: rgba(148, 163, 184, 0.26);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.5);
    }
    .form-control.input-with-icon:focus {
        border-color: var(--app-input-focus);
        background: rgba(15, 23, 42, 0.65);
    }
    [data-theme="light"] .form-control.input-with-icon:focus {
        background: rgba(255, 255, 255, 0.98);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12), inset 0 1px 0 rgba(255, 255, 255, 0.6);
    }
    .password-toggle {
        position: absolute;
        inset-block: 0;
        right: 1rem;
        display: inline-flex;
        align-items: center;
        cursor: pointer;
        color: var(--app-text-muted);
        transition: color .2s ease;
    }
    .password-toggle:hover {
        color: var(--brand-cyan);
    }
    .remember-pill {
        background: rgba(59, 130, 246, 0.16);
        color: rgba(191, 219, 254, 0.92);
        padding: 0.45rem 0.9rem;
        border-radius: 999px;
        font-size: 0.72rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }
    [data-theme="light"] .remember-pill {
        background: rgba(37, 99, 235, 0.16);
        color: rgba(30, 41, 59, 0.75);
    }
    .auth-card .btn-primary {
        border-radius: 14px;
        font-weight: 600;
        letter-spacing: 0.04em;
        box-shadow: 0 18px 38px rgba(37, 99, 235, 0.32);
        transition: transform .18s ease, box-shadow .18s ease;
    }
    .auth-card .btn-primary:focus-visible {
        outline: 2px solid rgba(37, 99, 235, 0.4);
        outline-offset: 2px;
    }
    .auth-card .btn-primary:active {
        transform: translateY(1px);
        box-shadow: 0 12px 24px rgba(30, 58, 138, 0.35);
    }
    .auth-card .form-check-input {
        width: 1.05rem;
        height: 1.05rem;
        border-radius: 6px;
        background: rgba(15, 23, 42, 0.6);
        border-color: rgba(148, 163, 184, 0.28);
    }
    [data-theme="light"] .auth-card .form-check-input {
        background: rgba(248, 250, 252, 0.95);
        border-color: rgba(148, 163, 184, 0.25);
    }
    .auth-card .form-check-input:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        border-color: var(--app-input-focus);
    }
    .auth-card .form-check-label {
        color: var(--app-text-muted);
    }
    @media (max-width: 991.98px) {
        .auth-hero {
            padding: 2rem;
            margin-bottom: 1.5rem;
        }
        .auth-card {
            border-radius: 22px;
        }
    }
</style>
@endpush

@section('content')
<div class="container px-0 px-md-3">
  <div class="row g-4 align-items-center justify-content-between">
    <div class="col-lg-6">
      <div class="auth-hero">
        <div class="auth-hero-content">
          <span class="auth-badge">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M4 5a1 1 0 0 1 1-1h4V2h6v2h4a1 1 0 0 1 1 1v3h-2V6H6v3H4zM4 9h2v2H4zm14 0h2v2h-2z"/><path d="M5 11h14a1 1 0 0 1 1 1v7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-7a1 1 0 0 1 1-1zm6 6v2h2v-2zm-4 0v2h2v-2zm8 0v2h2v-2z"/></svg>
            SARPRAS BPIP
          </span>
          <h2 class="mt-4 mb-3 fw-semibold" style="font-size: clamp(2rem, 3.4vw, 2.8rem); letter-spacing: 0.02em;">
            Kelola Peminjaman Sarpras Lebih Cepat & Transparan
          </h2>
            <p class="auth-subtext mb-4" style="max-width: 520px;">
            Pantau status aset, proses peminjaman, dan pengembalian dengan dashboard yang intuitif.
            Login untuk mulai mengelola kebutuhan operasional Anda.
          </p>
          <ul class="auth-list">
            <li>
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 13l4 4L19 7"/></svg>
              Monitoring real-time ketersediaan sarpras.
            </li>
            <li>
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2v-7H3v7a2 2 0 002 2z"/></svg>
              Pengingat jadwal peminjaman & pengembalian otomatis.
            </li>
            <li>
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3h7l2 3h9v13a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
              Laporan cepat untuk kebutuhan administrasi dan audit.
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div class="col-lg-5 col-xl-4">
      <div class="auth-card p-4 p-md-5">
        <div class="auth-card-header">
          <div>
            <h1>Masuk</h1>
            <p class="mb-0 auth-subtext" style="font-size: 0.95rem;">Silakan gunakan kredensial akun Anda.</p>
          </div>
          <span class="remember-pill d-none d-md-inline-flex">Akses Terbatas</span>
        </div>

        <form method="POST" action="{{ route('login.post') }}" class="row gy-3 position-relative">
          @csrf

          <div class="col-12 position-relative">
            <label class="form-label fw-semibold">Username atau Email</label>
            <span class="input-icon">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                <path d="M12 12c2.485 0 4.5-2.015 4.5-4.5S14.485 3 12 3 7.5 5.015 7.5 7.5 9.515 12 12 12zm0 1.5c-3.038 0-9 1.523-9 4.5V21h18v-3c0-2.977-5.962-4.5-9-4.5z"/>
              </svg>
            </span>
            <input type="text" name="identifier" value="{{ old('identifier') }}" class="form-control input-with-icon @error('identifier') is-invalid @enderror" required autofocus>
            @error('identifier')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="col-12 position-relative">
            <label class="form-label fw-semibold d-flex justify-content-between">
              <span>Password</span>
              <a href="{{ route('password.request') }}" class="small text-decoration-none">Lupa password?</a>
            </label>
            <span class="input-icon">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                <path d="M17 8h-1V6a4 4 0 10-8 0v2H7a2 2 0 00-2 2v8a2 2 0 002 2h10a2 2 0 002-2v-8a2 2 0 00-2-2zm-6 7.732V17a1 1 0 002 0v-1.268a2 2 0 10-2 0zM14 8h-4V6a2 2 0 114 0z"/>
              </svg>
            </span>
            <input type="password" name="password" class="form-control input-with-icon @error('password') is-invalid @enderror" required>
            <span class="password-toggle" data-toggle-password>
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" width="18" height="18">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
            </span>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="col-12 d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="form-check">
              <input type="checkbox" name="remember" id="remember" class="form-check-input">
              <label for="remember" class="form-check-label">Ingat saya</label>
            </div>
            <span class="remember-pill d-inline-flex d-md-none">Akses Terbatas</span>
          </div>

          <div class="col-12 d-grid">
            <button class="btn btn-primary py-2" type="submit">Masuk ke Dashboard</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const toggleEl = document.querySelector('[data-toggle-password]');
    const passwordInput = document.querySelector('input[name="password"]');
    if (!toggleEl || !passwordInput) {
      return;
    }
    toggleEl.addEventListener('click', () => {
      const showing = passwordInput.getAttribute('type') === 'text';
      passwordInput.setAttribute('type', showing ? 'password' : 'text');
      toggleEl.innerHTML = showing
        ? `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" width="18" height="18">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
           </svg>`
        : `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" width="18" height="18">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.049 10.049 0 013.272-4.568M6.223 6.223A10.05 10.05 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.249 2.507"/>
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M3 3l18 18"/>
           </svg>`;
    });
  });
</script>
@endpush

@endsection
