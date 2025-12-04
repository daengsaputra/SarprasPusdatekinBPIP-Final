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
  .theme-option {
    border: 2px solid transparent;
    border-radius: 16px;
    padding: 1rem;
    cursor: pointer;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
    display: flex;
    gap: 1rem;
    align-items: center;
    background: #f8fafc;
  }
  .theme-option input[type="radio"] {
    accent-color: #2563eb;
  }
  .theme-option.selected {
    border-color: #2563eb;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
    background: #ffffff;
  }
  .theme-swatch {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    flex-shrink: 0;
    display: flex;
    overflow: hidden;
  }
  .theme-swatch span {
    flex: 1;
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

  @if(!empty($themes))
    @php($selectedTheme = old('theme', $currentTheme))
    <div class="mb-4">
      <label class="form-label d-block">Tema Landing Page</label>
      <div class="row g-3">
        @foreach($themes as $key => $theme)
          @php($isActive = $selectedTheme === $key)
          <div class="col-md-6">
            <label class="theme-option {{ $isActive ? 'selected' : '' }}">
              <input class="form-check-input me-3" type="radio" name="theme" value="{{ $key }}" {{ $isActive ? 'checked' : '' }}>
              <div class="theme-swatch">
                @foreach($theme['swatch'] ?? [] as $color)
                  <span style="background: {{ $color }}"></span>
                @endforeach
              </div>
              <div>
                <div class="fw-semibold">{{ $theme['label'] ?? \Illuminate\Support\Str::headline($key) }}</div>
                <div class="text-muted small mb-1">{{ $theme['tagline'] ?? 'Tema landing' }}</div>
                <div class="text-uppercase text-muted small" style="letter-spacing:0.2em;">{{ $key }}</div>
              </div>
            </label>
          </div>
        @endforeach
      </div>
      @error('theme')
        <div class="text-danger small mt-2">{{ $message }}</div>
      @enderror
    </div>
  @endif

  <div class="d-flex gap-3 mb-0">
    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    <button type="reset" class="btn btn-light border">Reset Form</button>
  </div>
</form>
@endsection
