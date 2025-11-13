@php($title = 'Bukti Peminjaman')
@extends('layouts.app')

@push('styles')
<style>
  body {
    background: #fff;
    min-height: 100vh;
  }
  body.receipt-preview-mode {
    padding-top: 2rem;
    padding-bottom: 2rem;
  }
  body.receipt-preview-mode nav.navbar,
  body.receipt-preview-mode aside {
    display: none !important;
  }
  body.receipt-preview-mode .app-main {
    margin-left: 0 !important;
    max-width: none;
    width: 100%;
    padding: 0 1rem;
    display: flex;
    justify-content: center;
    align-items: flex-start;
  }
  @media print {
    nav.navbar, aside, header, footer { display: none !important; }
    body { background: #fff; }
    .receipt-preview__shell { box-shadow: none !important; background: #fff !important; padding: 0; max-width: 100%; }
    .receipt-preview__toolbar { display: none !important; }
  }
  .receipt-preview__shell {
    background: linear-gradient(180deg, #fdfefe 0%, #f3f7ff 100%);
    border-radius: 36px;
    padding: 2.3rem;
    box-shadow: 0 30px 80px rgba(15, 23, 42, 0.18);
    max-width: 920px;
    width: 100%;
    margin: 2rem auto;
    transform: scale(0.98);
    opacity: 0;
    transition: transform 0.45s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.35s ease;
  }
  .receipt-preview__shell.is-loaded {
    transform: scale(1);
    opacity: 1;
  }
  .receipt-preview__toolbar {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1.5rem;
  }
  .receipt-preview__title small {
    display: block;
    color: #94a3b8;
  }
  .receipt-preview__title h1 {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 0.3rem;
    color: #111827;
  }
  .receipt-preview__title span {
    color: #6b7280;
    font-size: 0.95rem;
  }
  .receipt-btn {
    border-radius: 999px;
    padding: 0.6rem 1.4rem;
    font-weight: 600;
    min-width: 150px;
    text-align: center;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
  }
  .receipt-btn:active {
    transform: translateY(1px) scale(0.99);
    box-shadow: inset 0 4px 12px rgba(15, 23, 42, 0.12);
  }
  .receipt-summary {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
  }
  .receipt-summary__item {
    background: #fff;
    border-radius: 18px;
    padding: 1rem 1.2rem;
    border: 1px solid #e2e8f0;
    box-shadow: 0 6px 15px rgba(15, 23, 42, 0.08);
  }
  .receipt-summary__item span {
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #475569;
    font-weight: 600;
    font-size: 0.72rem;
  }
  .receipt-summary__value {
    font-size: 1.25rem;
    font-weight: 700;
    color: #111827;
  }
  .receipt-meta {
    background: #fff;
    border-radius: 26px;
    padding: 1.9rem 2.2rem;
    box-shadow: 0 10px 35px rgba(15, 23, 42, 0.08);
  }
  .receipt-meta__grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
  }
  .receipt-meta__label {
    font-size: 0.7rem;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: #94a3b8;
    margin-bottom: 0.4rem;
    font-weight: 600;
  }
  .receipt-meta__value {
    font-size: 1rem;
    color: #111827;
    font-weight: 600;
  }
  .receipt-table {
    margin-top: 1.5rem;
    border-radius: 22px;
    overflow: hidden;
    box-shadow: 0 20px 50px rgba(15, 23, 42, 0.12);
  }
  .receipt-table table {
    margin-bottom: 0;
    background: #fff;
  }
  .receipt-table thead {
    background: #e2e8f0;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    font-size: 0.8rem;
    color: #1f2937;
  }
  .receipt-table tbody tr:nth-child(odd) {
    background: #f9fafb;
  }
  .receipt-table td,
  .receipt-table th {
    padding: 0.85rem 1rem;
    border-color: transparent;
  }
  .signature-panel {
    background: linear-gradient(135deg, rgba(226,232,240,0.55), rgba(191,219,254,0.5));
    border-radius: 26px;
    padding: 2rem 1.5rem;
    margin-top: 2rem;
  }
  .signature-panel__item {
    text-align: center;
  }
  .signature-panel__label {
    font-size: 0.85rem;
    color: #64748b;
  }
  .signature-line {
    min-height: 64px;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    font-weight: 600;
    font-size: 1rem;
    border-top: 1px dashed rgba(59, 130, 246, 0.6);
    padding-top: 0.75rem;
    margin-top: 1rem;
    color: #0f172a;
  }
  .receipt-attachments {
    margin-top: 1.5rem;
    background: #fff;
    border-radius: 24px;
    padding: 1.5rem;
    box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
  }
  .receipt-attachments__title {
    font-size: 0.85rem;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: #94a3b8;
    font-weight: 700;
    margin-bottom: 1rem;
  }
  .receipt-attachments__grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1rem;
  }
  .receipt-attachment-card {
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 0.75rem;
    text-align: center;
    background: linear-gradient(180deg, #f8fafc 0%, #fff 100%);
  }
  .receipt-attachment-card strong {
    display: block;
    font-size: 0.85rem;
    color: #0f172a;
    margin-bottom: 0.5rem;
  }
  .receipt-attachment-card img {
    max-width: 100%;
    border-radius: 12px;
    border: 1px solid rgba(148, 163, 184, 0.35);
    max-height: 180px;
    object-fit: cover;
  }
</style>
@endpush

@section('content')
<div class="receipt-preview__shell">
  <div class="receipt-preview__toolbar">
    <div class="receipt-preview__title">
      <h1>Bukti Peminjaman Barang</h1>
      <span>Kode Pinjam: <strong>{{ $batch }}</strong></span>
      <small>SARPRAS PUSDATEKIN BPIP</small>
    </div>
    <div class="d-flex gap-3">
      <a class="btn btn-outline-primary receipt-btn" target="_blank" href="{{ route('loans.receipt', ['batch' => $batch, 'download' => 1]) }}">Download PDF</a>
      <button type="button" class="btn btn-primary receipt-btn" onclick="window.print()">Cetak Halaman</button>
    </div>
  </div>

  <div class="receipt-summary">
    <div class="receipt-summary__item">
      <span>Total Unit</span>
      <div class="receipt-summary__value">{{ $items->sum('quantity') }} unit</div>
    </div>
    <div class="receipt-summary__item">
      <span>Status</span>
      <div class="receipt-summary__value" style="font-size:1rem">
        {{ ($return_plan && now()->gt(\Illuminate\Support\Carbon::parse($return_plan))) ? 'Perlu Pengembalian' : 'Sedang Dipinjam' }}
      </div>
    </div>
  </div>

  <section class="receipt-meta mb-2">
    <div class="receipt-meta__grid">
      <div>
        <div class="receipt-meta__label">Nama Peminjam</div>
        <div class="receipt-meta__value">{{ $borrower }}</div>
      </div>
      <div>
        <div class="receipt-meta__label">Unit Kerja</div>
        <div class="receipt-meta__value">{{ $unit }}</div>
      </div>
      @if($activity_name)
      <div>
        <div class="receipt-meta__label">Nama Kegiatan</div>
        <div class="receipt-meta__value">{{ $activity_name }}</div>
      </div>
      @endif
      @if($contact)
      <div>
        <div class="receipt-meta__label">Kontak</div>
        <div class="receipt-meta__value">{{ $contact }}</div>
      </div>
      @endif
      <div>
        <div class="receipt-meta__label">Tanggal Pinjam</div>
        <div class="receipt-meta__value">{{ \Illuminate\Support\Carbon::parse($loan_date)->format('Y-m-d') }}</div>
      </div>
      <div>
        <div class="receipt-meta__label">Estimasi Kembali</div>
        <div class="receipt-meta__value">{{ $return_plan ? \Illuminate\Support\Carbon::parse($return_plan)->format('Y-m-d') : '-' }}</div>
      </div>
      <div>
        <div class="receipt-meta__label">Petugas</div>
        <div class="receipt-meta__value text-capitalize">{{ $officer }}</div>
      </div>
      <div>
        <div class="receipt-meta__label">Dicetak</div>
        <div class="receipt-meta__value">{{ $printed_at->format('Y-m-d H:i') }}</div>
      </div>
    </div>
  </section>

  <section class="receipt-table">
    <div class="table-responsive">
      <table class="table mb-0">
        <thead>
          <tr>
            <th class="text-center" style="width:60px">No</th>
            <th style="width:140px">Kode Barang</th>
            <th>Nama Barang</th>
            <th class="text-center" style="width:100px">Jumlah</th>
          </tr>
        </thead>
        <tbody>
          @foreach($items as $index => $row)
            <tr>
              <td class="text-center fw-semibold">{{ $index + 1 }}</td>
              <td class="fw-semibold">{{ $row->asset->code }}</td>
              <td>{{ $row->asset->name }}</td>
              <td class="text-center fw-semibold">{{ $row->quantity }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </section>

  @php($attachmentList = collect($attachments ?? [])->filter())
  @if($attachmentList->isNotEmpty())
    <section class="receipt-attachments">
      <div class="receipt-attachments__title">Bukti Foto</div>
      <div class="receipt-attachments__grid">
        @foreach($attachmentList as $label => $path)
          @php($exists = $path && \Illuminate\Support\Facades\Storage::disk('public')->exists($path))
          <div class="receipt-attachment-card">
            <strong>{{ $label }}</strong>
            @if($exists)
              <a href="{{ asset('storage/'.$path) }}" target="_blank">
                <img src="{{ asset('storage/'.$path) }}" alt="{{ $label }}">
              </a>
            @else
              <span class="text-muted small d-block">File tidak tersedia</span>
            @endif
          </div>
        @endforeach
      </div>
    </section>
  @endif

  <div class="signature-panel row g-4 mt-0">
    <div class="col-md-6 signature-panel__item">
      <div class="signature-panel__label">Peminjam</div>
      <div class="signature-line">{{ $borrower }}</div>
    </div>
    <div class="col-md-6 signature-panel__item">
      <div class="signature-panel__label">Petugas</div>
      <div class="signature-line">{{ $officer }}</div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.body.classList.add('receipt-preview-mode');
    requestAnimationFrame(function(){
      var shell = document.querySelector('.receipt-preview__shell');
      if(shell){
        shell.classList.add('is-loaded');
      }
    });
  });
</script>
@endpush
