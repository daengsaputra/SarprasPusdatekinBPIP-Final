@php($title = 'Pengaturan Landing Page')
@extends('layouts.app')

@push('styles')
<style>
  .settings-card {
    background: #fff;
    border-radius: 22px;
    border: 1px solid rgba(226,232,240,0.9);
    padding: 2rem;
    box-shadow: 0 25px 60px rgba(15,23,42,0.08);
  }
  .settings-card h2 {
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.18em;
    color: #64748b;
    margin-bottom: 1.25rem;
  }
  .settings-preview {
    border-radius: 18px;
    border: 1px solid rgba(148,163,184,0.35);
    overflow: hidden;
    background: #0f172a;
    max-width: 400px;
    margin: 0 auto;
  }
  .settings-preview video {
    width: 100%;
    height: auto;
    display: block;
  }
  .theme-grid {
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:1rem;
  }
  .theme-option {
    border:1px solid rgba(148,163,184,0.35);
    border-radius:18px;
    padding:1rem;
    display:flex;
    gap:1rem;
    align-items:center;
    cursor:pointer;
    transition:border-color .2s ease, box-shadow .2s ease;
  }
  .theme-option input {
    display:none;
  }
  .theme-option__swatch {
    width:48px;
    height:48px;
    border-radius:14px;
    background:linear-gradient(135deg,#e2e8f0,#cbd5f5);
    border:1px solid rgba(148,163,184,0.4);
  }
  .theme-option__info h3 {
    margin:0;
    font-size:0.9rem;
    font-weight:600;
  }
  .theme-option__info p {
    margin:0;
    font-size:0.8rem;
    color:#64748b;
  }
  .theme-option.is-active {
    border-color:#2563eb;
    box-shadow:0 12px 30px rgba(37,99,235,0.18);
  }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-start mb-4">
  <div>
    <p class="text-uppercase text-muted small mb-1" style="letter-spacing:0.25em;">Pengaturan</p>
    <h1 class="h4 mb-0">Video Landing Page</h1>
    <p class="text-muted mb-0">Atur video hero yang akan tampil pada halaman publik.</p>
  </div>
  <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Kembali ke Dashboard</a>
</div>

@if(session('status'))
  <div class="alert alert-success mb-4">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('settings.landing.update') }}" enctype="multipart/form-data" class="settings-card">
  @csrf
  <h2>Video Hero</h2>
  <div class="mb-4">
    <label class="form-label">Unggah Video Baru</label>
    <input type="file" name="landing_video" accept="video/mp4,video/webm,video/ogg" class="form-control @error('landing_video') is-invalid @enderror">
    <div class="form-text">
      @php($maxMb = number_format((int) config('bpip.landing_video_max_kb', 51200) / 1024, 1))
      Format yang didukung: MP4, WebM, OGG. Ukuran maksimal {{ $maxMb }} MB.
    </div>
    @error('landing_video')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  @if($videoUrl ?? false)
    <div class="mb-4">
      <label class="form-label">Preview Saat Ini</label>
      <div class="settings-preview mb-3">
        <video controls preload="metadata">
          <source src="{{ $videoUrl }}" @if($videoMime) type="{{ $videoMime }}" @endif>
          Browser Anda tidak mendukung pemutaran video.
        </video>
      </div>
      <div class="form-check">
        <input type="checkbox" name="remove_video" value="1" class="form-check-input" id="removeVideo">
        <label class="form-check-label" for="removeVideo">Hapus video saat ini</label>
      </div>
    </div>
  @endif

  <div class="d-flex gap-3 mb-4">
    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    <button type="reset" class="btn btn-light border">Reset Form</button>
  </div>

  <h2 class="mt-4">Tema Landing</h2>
  <p class="text-muted mb-3">Pilih satu dari tiga template tema untuk mengubah warna latar dan aksen halaman landing.</p>
  <div class="theme-grid">
    @foreach($themes as $key => $theme)
      @php($active = $currentTheme === $key)
      <label class="theme-option {{ $active ? 'is-active' : '' }}">
        <input type="radio" name="theme" value="{{ $key }}" {{ $active ? 'checked' : '' }}>
        <div class="theme-option__swatch" style="background:linear-gradient(135deg, {{ $theme['swatch'][0] }}, {{ $theme['swatch'][1] ?? $theme['swatch'][0] }});"></div>
        <div class="theme-option__info">
          <h3>{{ $theme['label'] ?? \Illuminate\Support\Str::title($key) }}</h3>
          <p>{{ $theme['tagline'] ?? 'Tema landing' }}</p>
        </div>
      </label>
    @endforeach
  </div>
  @error('theme')
    <div class="text-danger small mt-2">{{ $message }}</div>
  @enderror
</form>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.theme-option input').forEach((input) => {
      input.addEventListener('change', () => {
        document.querySelectorAll('.theme-option').forEach((card) => card.classList.remove('is-active'));
        input.closest('.theme-option')?.classList.add('is-active');
      });
    });
  });
</script>
@endpush
