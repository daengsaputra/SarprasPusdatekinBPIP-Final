@php($title = 'Pengaturan Landing Page')
@extends('layouts.app')

@push('styles')
<style>
  body[data-theme="light"] { background:#eef2ff; }
  .landing-settings-shell { display:flex; flex-direction:column; gap:1.1rem; padding-bottom:2.2rem; }
  .landing-settings-hero {
    display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.9rem;
    background:linear-gradient(120deg, rgba(59,130,246,0.12), #fff 70%);
    border:1px solid rgba(148,163,184,0.1);
    border-radius:24px;
    padding:1.35rem 1.6rem;
    box-shadow:0 12px 35px rgba(15,23,42,0.08);
  }
  .landing-settings-title { font-size:clamp(1.15rem,2.2vw,1.65rem); font-weight:700; color:#0f172a; margin-bottom:0.2rem; }
  .landing-settings-subtitle { color:#475569; font-size:0.9rem; }
  .landing-settings-cta { display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap; margin-top:0.85rem; }
  .landing-settings-cta small { color:#64748b; font-weight:600; letter-spacing:0.08em; text-transform:uppercase; }
  .landing-settings-stats { display:flex; flex-wrap:wrap; gap:0.75rem; }
  .landing-settings-summary-card { background:#fff; border-radius:18px; border:1px solid rgba(148,163,184,0.16); box-shadow:0 14px 32px rgba(15,23,42,0.08); padding:0.9rem 1.2rem; min-width:160px; }
  .landing-settings-summary-label { text-transform:uppercase; letter-spacing:0.15em; font-size:0.62rem; color:#94a3b8; }
  .landing-settings-summary-value { font-size:1.35rem; font-weight:700; color:#0f172a; }
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
@php($themeCount = is_countable($themes ?? null) ? count($themes) : 0)
@php($hasVideo = filled($videoUrl ?? null))
@php($activeThemeLabel = !empty($themes[$currentTheme]['label']) ? $themes[$currentTheme]['label'] : \\Illuminate\\Support\\Str::headline((string) $currentTheme))
<main class="content-body">
<div class="container-fluid">
<div class="landing-settings-shell">
<div class="landing-settings-hero">
  <div>
    <div class="landing-settings-title">Pengaturan Landing</div>
    <div class="landing-settings-subtitle">Atur video hero dan tema landing publik dengan tampilan terbaru.</div>
    <div class="landing-settings-cta">
      <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Kembali ke Dashboard</a>
      <small>Konfigurasi tampilan halaman publik</small>
    </div>
  </div>
  <div class="landing-settings-stats">
    <div class="landing-settings-summary-card">
      <div class="landing-settings-summary-label">Tema tersedia</div>
      <div class="landing-settings-summary-value">{{ number_format($themeCount) }}</div>
    </div>
    <div class="landing-settings-summary-card">
      <div class="landing-settings-summary-label">Video hero</div>
      <div class="landing-settings-summary-value">{{ $hasVideo ? 'Aktif' : 'Kosong' }}</div>
    </div>
    <div class="landing-settings-summary-card">
      <div class="landing-settings-summary-label">Tema aktif</div>
      <div class="landing-settings-summary-value" style="font-size:1rem;">{{ $activeThemeLabel }}</div>
    </div>
  </div>
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
</div>
</div>
</main>
@endsection
