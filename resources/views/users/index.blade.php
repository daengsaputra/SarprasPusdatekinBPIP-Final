@php($title = 'Daftar Anggota')
@extends('layouts.app')

@push('styles')
<style>
  body[data-theme="light"] { background: #f4f6ff; }
  .users-shell { display:flex; flex-direction:column; gap:1.5rem; }
  .users-hero {
    display:flex; justify-content:space-between; flex-wrap:wrap; gap:1.5rem;
    padding:1.5rem 1.75rem; border-radius:30px;
    background:linear-gradient(120deg, rgba(59,130,246,0.12), #ffffff 65%);
    border:1px solid rgba(148,163,184,0.18); box-shadow:0 20px 40px rgba(15,23,42,0.1);
  }
  .hero-action {
    display:inline-flex; align-items:center; gap:0.45rem;
    border-radius:999px; padding:0.48rem 1.1rem;
    font-size:0.86rem; font-weight:600; color:#0f172a;
    background:#fff; border:1px solid rgba(148,163,184,0.35);
    box-shadow:0 8px 18px rgba(15,23,42,0.08);
    text-decoration:none; transition:transform 0.15s ease, box-shadow 0.15s ease;
  }
  .hero-action span { display:inline-flex; width:1.05rem; height:1.05rem; border-radius:999px; background:rgba(59,130,246,0.15); align-items:center; justify-content:center; font-size:0.82rem; color:#2563eb; }
  .hero-action:active { transform:translateY(1px); }
  .users-hero__title { font-size:clamp(1.8rem,3vw,2.4rem); font-weight:700; color:#0f172a; }
  .users-summary-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:1rem; }
  .users-summary-card { background:#fff; border-radius:22px; border:1px solid rgba(148,163,184,0.16); box-shadow:0 18px 38px rgba(15,23,42,0.08); padding:1.2rem 1.4rem; }
  .users-summary-label { text-transform:uppercase; letter-spacing:0.12em; font-size:0.72rem; color:#94a3b8; }
  .users-summary-value { font-size:1.85rem; font-weight:700; color:#0f172a; }
  .users-card-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:1rem; }
  .user-card { background:#fff; border-radius:26px; border:1px solid rgba(148,163,184,0.16); box-shadow:0 18px 38px rgba(15,23,42,0.08); padding:1.2rem; display:flex; gap:1rem; align-items:center; }
  .user-avatar { width:56px; height:56px; border-radius:18px; object-fit:cover; border:1px solid #e2e8f0; }
  .user-avatar--fallback { background:linear-gradient(135deg,#475569,#1e293b); color:#fff; font-weight:600; display:flex; align-items:center; justify-content:center; font-size:1rem; }
  .user-actions { margin-left:auto; display:flex; gap:0.3rem; flex-wrap:wrap; }
  .user-actions .btn-sm { padding:0.25rem 0.65rem; font-size:0.78rem; border-radius:10px; }
  .users-pagination { display:flex; justify-content:flex-end; margin-top:1rem; }
  @media (max-width: 992px) { body[data-theme="light"] .app-main { margin-left:0!important; } .users-hero { flex-direction:column; } }
</style>
@endpush

@php($totalUsers = $users->total())
@php($adminCount = $users->filter(fn($u) => $u->role === 'admin')->count())
@php($staffCount = $totalUsers - $adminCount)

@section('content')
<div class="users-shell">
  <section class="users-hero">
    <div>
      <p class="text-uppercase text-muted small mb-1" style="letter-spacing:0.25em;">Anggota</p>
      <h1 class="users-hero__title">Manajemen Akun Tim</h1>
      <p class="text-muted mb-0">Kelola akses akun, reset sandi, serta tambah anggota baru dengan tampilan yang lebih intuitif.</p>
    </div>
    <a href="{{ route('users.create') }}" class="hero-action">+ Tambah Anggota <span>&rsaquo;</span></a>
  </section>

  <section class="users-summary-grid">
    <div class="users-summary-card">
      <div class="users-summary-label">Total Akun</div>
      <div class="users-summary-value">{{ number_format($totalUsers) }}</div>
      <div class="text-muted small">Semua pengguna terdaftar.</div>
    </div>
    <div class="users-summary-card">
      <div class="users-summary-label">Admin</div>
      <div class="users-summary-value">{{ number_format($adminCount) }}</div>
      <div class="text-muted small">Hak akses penuh.</div>
    </div>
    <div class="users-summary-card">
      <div class="users-summary-label">Petugas</div>
      <div class="users-summary-value">{{ number_format($staffCount) }}</div>
      <div class="text-muted small">Pengguna operasional.</div>
    </div>
  </section>

  <section class="users-card-grid">
    @foreach($users as $u)
      <div class="user-card">
        @if($u->photo)
          <img class="user-avatar" src="{{ asset('storage/'.$u->photo) }}" alt="Foto {{ $u->name }}">
        @else
          @php($parts = preg_split('/\s+/', trim($u->name)))
          @php($initials = strtoupper(mb_substr($parts[0]??'',0,1).mb_substr($parts[1]??'',0,1)))
          <div class="user-avatar user-avatar--fallback">{{ $initials ?: '?' }}</div>
        @endif
        <div>
          <div class="fw-semibold">{{ $u->name }}</div>
          <div class="text-muted small">{{ $u->email }}</div>
          <span class="badge {{ $u->role === 'admin' ? 'bg-primary' : 'bg-secondary' }} text-uppercase">{{ $u->role }}</span>
        </div>
        <div class="user-actions gap-1">
          <a href="{{ route('users.edit', $u) }}" class="btn btn-sm btn-outline-primary">Edit</a>
          <form method="POST" action="{{ route('users.reset', $u) }}" onsubmit="return confirm('Reset password untuk {{ $u->name }}?');">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-warning">Reset</button>
          </form>
          <form method="POST" action="{{ route('users.destroy', $u) }}" onsubmit="return confirm('Hapus anggota {{ $u->name }}?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
          </form>
        </div>
      </div>
    @endforeach
  </section>

  <div class="users-pagination">
    {{ $users->links() }}
  </div>
</div>
@endsection
