@php($title = 'Pinjam Aset')
@extends('layouts.app')

@push('styles')
<style>
  .list-aset { max-height: 480px; overflow:auto; }
  .status-dot { width:10px;height:10px;border-radius:50%;display:inline-block;margin-right:6px }
  .dot-green{ background:#10b981 }
  .dot-red{ background:#ef4444 }
  .cart-item input[type=number]{ width:90px }
  .file-drop {
    border: 1px dashed rgba(148, 163, 184, 0.8);
    border-radius: 16px;
    padding: 1.25rem;
    text-align: center;
    background: rgba(248, 250, 252, 0.85);
    transition: border-color .2s ease, background .2s ease, box-shadow .2s ease;
    cursor: pointer;
  }
  .file-drop:hover { border-color: rgba(59, 130, 246, 0.8); }
  .file-drop.is-dragover {
    border-color: #2563eb;
    background: rgba(37, 99, 235, 0.06);
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.08) inset;
  }
  .file-drop.is-invalid { border-color: #dc3545; }
  .file-drop__input { display: none; }
  .file-drop__icon {
    width: 48px; height: 48px; border-radius: 12px;
    margin: 0 auto 0.75rem;
    display: flex; align-items:center; justify-content:center;
    background: rgba(37,99,235,0.12); color:#2563eb;
  }
  .file-drop__filename { display:block; margin-top:0.3rem; color:#475569; font-size:0.9rem; }
  .file-drop__actions {
    display: flex;
    gap: .45rem;
    justify-content: center;
    flex-wrap: wrap;
    margin-top: .7rem;
  }
  .file-drop__list {
    margin: .75rem 0 0;
    padding-left: 1rem;
    text-align: left;
    font-size: .86rem;
    color: #334155;
  }
  .file-drop__list li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: .5rem;
    margin-bottom: .35rem;
  }
  .date-quick-actions {
    display: flex;
    gap: .35rem;
    flex-wrap: wrap;
    margin-top: .45rem;
  }
  .date-quick-actions .btn {
    --bs-btn-padding-y: .2rem;
    --bs-btn-padding-x: .5rem;
    --bs-btn-font-size: .72rem;
  }
</style>
@endpush

@section('content')
<main class="content-body">
<div class="container-fluid">
<h1 class="h5 mb-3">Pilih barang yang akan dipinjam</h1>

<div class="row g-3">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between mb-2">
          <div>
            <label class="me-2">Show</label>
            <select id="pageSize" class="form-select d-inline-block" style="width:80px">
              <option>10</option><option>25</option><option>50</option>
            </select>
            <span class="ms-2">entries</span>
          </div>
          <div>
            <label class="me-2">Search:</label>
            <input type="text" id="search" class="form-control d-inline-block" style="width:220px" placeholder="cari aset...">
          </div>
        </div>

        <div class="table-responsive list-aset">
          <table class="table table-hover align-middle">
            <thead>
              <tr>
                <th>Nama Barang</th>
                <th>ID Barang</th>
                <th>Status</th>
                <th class="text-end">Aksi</th>
              </tr>
            </thead>
            <tbody id="assetTable">
              @foreach($assets as $a)
                <tr data-name="{{ strtolower($a->name.' '.$a->code) }}">
                  <td>{{ $a->name }}</td>
                  <td>{{ $a->code }}</td>
                  <td>
                    @if($a->quantity_available>0)
                      <span class="status-dot dot-green"></span>Tersedia
                    @else
                      <span class="status-dot dot-red"></span>Terpinjam
                    @endif
                  </td>
                  <td class="text-end">
                    <button class="btn btn-sm btn-info" onclick="addToCart({ id: {{ $a->id }}, name: @js($a->name), code: @js($a->code), max: {{ $a->quantity_available }} })" {{ $a->quantity_available<=0 ? 'disabled' : '' }}>pilih</button>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <strong>Daftar pinjam</strong>
          <button class="btn btn-sm btn-outline-secondary" onclick="clearCart()">Clear</button>
        </div>
        <div id="cartList" class="mb-3"></div>

        <form id="batchForm" method="POST" action="{{ route('loans.store.batch') }}" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="items" id="itemsField">

          <div class="mb-2">
            <label class="form-label">Nama Peminjam</label>
            <input type="text" name="borrower_name" class="form-control @error('borrower_name') is-invalid @enderror" required>
            @error('borrower_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-2">
            <label class="form-label">Kontak Peminjam</label>
            <input type="text" name="borrower_contact" class="form-control">
          </div>
          <div class="mb-2">
            <label class="form-label">Unit Kerja BPIP</label>
            <select name="unit" class="form-select @error('unit') is-invalid @enderror" required>
              <option value="">-- pilih unit kerja --</option>
              @foreach(($units ?? config('bpip.units')) as $unit)
                <option value="{{ $unit }}">{{ $unit }}</option>
              @endforeach
            </select>
            @error('unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-2">
            <label class="form-label">Nama Kegiatan</label>
            <input type="text" name="activity_name" value="{{ old('activity_name') }}" class="form-control @error('activity_name') is-invalid @enderror" required>
            @error('activity_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="row g-2">
            <div class="col-6">
              <label for="loan_date" class="form-label">Tanggal Pinjam</label>
              <div class="input-group">
                <span class="input-group-text">📅</span>
                <input type="date" id="loan_date" name="loan_date" value="{{ old('loan_date', now()->format('Y-m-d')) }}" class="form-control" required>
              </div>
            </div>
            <div class="col-6">
              <label for="return_date_planned" class="form-label">Rencana Kembali</label>
              <div class="input-group">
                <span class="input-group-text">🗓️</span>
                <input type="date" id="return_date_planned" name="return_date_planned" value="{{ old('return_date_planned') }}" class="form-control">
              </div>
              <div class="date-quick-actions">
                <button type="button" class="btn btn-outline-secondary" data-date-preset="today">Hari ini</button>
                <button type="button" class="btn btn-outline-secondary" data-date-preset="plus1">+1 hari</button>
                <button type="button" class="btn btn-outline-secondary" data-date-preset="plus7">+7 hari</button>
              </div>
            </div>
          </div>
          <div class="mb-2 mt-2">
            <label class="form-label">Foto ND/Helpdesk Pengajuan</label>
            <div class="file-drop @error('request_photo') is-invalid @enderror" data-file-drop>
              <input type="file" name="request_photo[]" id="requestPhotoInput" accept=".jpg,.jpeg,.png,.webp,image/*" class="file-drop__input" required multiple data-max-kb="{{ (int) config('bpip.loan_attachment_max_kb', 4096) }}" data-max-files="5">
              <div class="file-drop__body">
                <div class="file-drop__icon">
                  📄
                </div>
                <strong>Tarik & lepaskan file di sini</strong>
                <div>atau klik untuk memilih dari komputer</div>
                                <small class="file-drop__filename" data-file-drop-name>Belum ada file</small>
                <div class="file-drop__actions">
                  <button type="button" class="btn btn-sm btn-outline-primary" data-file-action="append">Tambah Foto</button>
                  <button type="button" class="btn btn-sm btn-outline-secondary" data-file-action="replace">Ganti Semua</button>
                  <button type="button" class="btn btn-sm btn-outline-danger" data-file-action="clear">Hapus Semua</button>
                </div>
              </div>
            </div>
            <ul class="file-drop__list mt-2" data-file-drop-list></ul>
            <div class="form-text">Unggah dokumentasi ND/helpdesk (wajib, JPG/PNG/WebP, maks {{ number_format(((int) config('bpip.loan_attachment_max_kb', 4096)) / 1024, 1) }} MB per file, maks 5 file).</div>
            @error('request_photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
            @error('request_photo.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
          </div>
          <div class="mb-2">
            <label class="form-label">Foto Serah Terima (Saat Peminjaman)</label>
            <div class="file-drop @error('loan_photo') is-invalid @enderror" data-file-drop>
              <input type="file" name="loan_photo[]" id="loanPhotoInput" accept=".jpg,.jpeg,.png,.webp,image/*" class="file-drop__input" required multiple data-max-kb="{{ (int) config('bpip.loan_attachment_max_kb', 4096) }}" data-max-files="5">
              <div class="file-drop__body">
                <div class="file-drop__icon">
                  📷
                </div>
                <strong>Tarik & lepaskan foto</strong>
                <div>atau klik untuk memilih dari komputer</div>
                                <small class="file-drop__filename" data-file-drop-name>Belum ada file</small>
                <div class="file-drop__actions">
                  <button type="button" class="btn btn-sm btn-outline-primary" data-file-action="append">Tambah Foto</button>
                  <button type="button" class="btn btn-sm btn-outline-secondary" data-file-action="replace">Ganti Semua</button>
                  <button type="button" class="btn btn-sm btn-outline-danger" data-file-action="clear">Hapus Semua</button>
                </div>
              </div>
            </div>
            <ul class="file-drop__list mt-2" data-file-drop-list></ul>
            <div class="form-text">Gunakan foto bukti serah terima saat barang keluar (wajib, JPG/PNG/WebP, maks {{ number_format(((int) config('bpip.loan_attachment_max_kb', 4096)) / 1024, 1) }} MB per file, maks 5 file).</div>
            @error('loan_photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
            @error('loan_photo.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
          </div>
          <div class="mb-2 mt-2">
            <label class="form-label">Catatan</label>
            <textarea name="notes" class="form-control" rows="2"></textarea>
          </div>
          <button class="btn btn-success w-100" type="submit">Simpan</button>
          <a class="btn btn-secondary w-100 mt-2" href="{{ route('loans.index') }}">Batal</a>
        </form>
      </div>
    </div>
  </div>
</div>

</div>
</main>
@endsection

@push('scripts')
<script>
  const DRAFT_KEY = 'loan_create_draft_v1';
  const cart = [];
  const cartList = document.getElementById('cartList');
  const itemsField = document.getElementById('itemsField');
  const batchForm = document.getElementById('batchForm');
  const searchInput = document.getElementById('search');
  const trackedFieldNames = [
    'borrower_name',
    'borrower_contact',
    'unit',
    'activity_name',
    'loan_date',
    'return_date_planned',
    'notes',
  ];

  function saveDraft() {
    try {
      const formData = {};
      trackedFieldNames.forEach((name) => {
        const field = batchForm?.querySelector(`[name="${name}"]`);
        if (!field) return;
        formData[name] = field.value ?? '';
      });

      const payload = {
        form: formData,
        cart,
        search: searchInput?.value ?? '',
      };
      sessionStorage.setItem(DRAFT_KEY, JSON.stringify(payload));
    } catch (_) {}
  }

  function restoreDraft() {
    try {
      const raw = sessionStorage.getItem(DRAFT_KEY);
      if (!raw) return;
      const payload = JSON.parse(raw);

      if (payload?.form) {
        trackedFieldNames.forEach((name) => {
          const field = batchForm?.querySelector(`[name="${name}"]`);
          if (!field) return;
          if (field.value) return; // keep old() value from backend if exists
          field.value = payload.form[name] ?? '';
        });
      }

      if (Array.isArray(payload?.cart)) {
        payload.cart.forEach((item) => {
          if (!item || !item.id) return;
          cart.push({
            id: Number(item.id),
            name: String(item.name ?? ''),
            code: String(item.code ?? ''),
            max: Number(item.max ?? 1),
            qty: Math.max(1, Number(item.qty ?? 1)),
          });
        });
      }

      if (searchInput && typeof payload?.search === 'string') {
        searchInput.value = payload.search;
      }
    } catch (_) {}
  }

  function renderCart(){
    if(cart.length===0){
      cartList.innerHTML = '<div class="text-muted small">Belum ada item.</div>';
      itemsField.value = '';
      saveDraft();
      return;
    }
    cartList.innerHTML = cart.map((it,idx)=>`
      <div class="cart-item d-flex align-items-center justify-content-between border rounded p-2 mb-2">
        <div>
          <div class="fw-semibold">${it.code} — ${it.name}</div>
          <div class="small text-muted">Tersedia: ${it.max}</div>
        </div>
        <div class="d-flex align-items-center gap-2">
          <input type="number" min="1" max="${it.max}" value="${it.qty}" class="form-control form-control-sm" onchange="updateQty(${idx}, this.value)">
          <button class="btn btn-sm btn-outline-danger" onclick="removeItem(${idx})">Hapus</button>
        </div>
      </div>
    `).join('');
    itemsField.value = JSON.stringify(cart.map(x=>({asset_id:x.id, quantity:x.qty})));
    saveDraft();
  }
  function addToCart(obj){
    const found = cart.find(x=>x.id===obj.id);
    if(found){ found.qty = Math.min(found.qty+1, found.max); }
    else { cart.push({ id: obj.id, name: obj.name, code: obj.code, max: obj.max, qty: 1 }); }
    renderCart();
  }
  function updateQty(idx, val){ cart[idx].qty = Math.max(1, Math.min(parseInt(val||1,10), cart[idx].max)); renderCart(); }
  function removeItem(idx){ cart.splice(idx,1); renderCart(); }
  function clearCart(){ cart.length=0; renderCart(); saveDraft(); }

  // client search filter
  searchInput?.addEventListener('input', (e)=>{
    const val = e.target.value.trim().toLowerCase();
    document.querySelectorAll('#assetTable tr').forEach(tr=>{
      const ok = tr.dataset.name.includes(val);
      tr.style.display = ok? '' : 'none';
    });
    saveDraft();
  });

  // Submit transform: stringified items to array
  batchForm?.addEventListener('submit', (e)=>{
    if(cart.length===0){ e.preventDefault(); alert('Pilih minimal satu item.'); }
    else {
      // name="items" needs to be JSON string; controller expects array via $request->validate
      // Laravel will parse it as string; we handle in controller by decoding automatically via Request casting not available
      // So keep as string and decode in controller? Simpler: use hidden multiple input fields
    }
  });

  trackedFieldNames.forEach((name) => {
    const field = batchForm?.querySelector(`[name="${name}"]`);
    if (!field) return;
    field.addEventListener('input', saveDraft);
    field.addEventListener('change', saveDraft);
  });

  restoreDraft();
  renderCart();

  initFileDropzones();
  initDateFieldFocusFix();

  function initFileDropzones() {
    const zones = document.querySelectorAll('[data-file-drop]');
    if (!zones.length) return;

    zones.forEach((zone) => {
      const input = zone.querySelector('input[type="file"]');
      const nameEl = zone.querySelector('[data-file-drop-name]');
      const listEl = zone.parentElement?.querySelector('[data-file-drop-list]');
      const maxKb = Number(input?.dataset?.maxKb || 4096);
      const maxFiles = Number(input?.dataset?.maxFiles || 5);
      let buffer = [];

      const syncInputFiles = () => {
        if (!input) return;
        const dt = new DataTransfer();
        buffer.forEach((file) => dt.items.add(file));
        input.files = dt.files;
      };

      const setFileName = () => {
        if (!buffer.length) {
          if (nameEl) nameEl.textContent = 'Belum ada file';
          if (listEl) listEl.innerHTML = '';
          return;
        }
        if (nameEl) nameEl.textContent = `${buffer.length} file dipilih`;
        if (listEl) {
          listEl.innerHTML = buffer.map((file, idx) => `
            <li>
              <span>${file.name}</span>
              <div class="d-inline-flex align-items-center gap-2">
                <button type="button" class="btn btn-sm btn-link text-primary p-0" data-replace-file="${idx}">ganti</button>
                <button type="button" class="btn btn-sm btn-link text-danger p-0" data-remove-file="${idx}">hapus</button>
              </div>
            </li>
          `).join('');
        }
      };

      const clearFiles = () => {
        buffer = [];
        syncInputFiles();
        setFileName();
      };

      const appendFiles = (files, mode = 'append') => {
        if (!Array.isArray(files) || !files.length) return;
        let next = mode === 'replace' ? [] : [...buffer];

        files.forEach((file) => {
          if (!file || !file.type || !file.type.startsWith('image/')) return;
          if (file.size > maxKb * 1024) {
            alert(`File "${file.name}" melebihi batas ${(maxKb / 1024).toFixed(1)} MB.`);
            return;
          }
          next.push(file);
        });

        if (next.length > maxFiles) {
          alert(`Maksimal ${maxFiles} file per upload.`);
          next = next.slice(0, maxFiles);
        }

        buffer = next;
        syncInputFiles();
        setFileName();
      };

      const replaceFileAt = (idx, file) => {
        if (idx < 0 || idx >= buffer.length) return;
        if (!file || !file.type || !file.type.startsWith('image/')) return;
        if (file.size > maxKb * 1024) {
          alert(`File "${file.name}" melebihi batas ${(maxKb / 1024).toFixed(1)} MB.`);
          return;
        }
        buffer[idx] = file;
        syncInputFiles();
        setFileName();
      };

      const replacePicker = document.createElement('input');
      replacePicker.type = 'file';
      replacePicker.accept = '.jpg,.jpeg,.png,.webp,image/*';
      replacePicker.className = 'd-none';
      replacePicker.dataset.mode = 'single-replace';
      zone.appendChild(replacePicker);

      zone.addEventListener('click', (e) => {
        const actionEl = e.target.closest('[data-file-action]');
        const removeEl = e.target.closest('[data-remove-file]');
        const replaceEl = e.target.closest('[data-replace-file]');
        if (actionEl || removeEl || replaceEl) return;
        if (!input) return;
        input.dataset.pickMode = 'append';
        input.click();
      });
      zone.addEventListener('click', (e) => {
        const actionEl = e.target.closest('[data-file-action]');
        if (!actionEl || !input) return;
        const action = actionEl.getAttribute('data-file-action');
        if (action === 'clear') {
          clearFiles();
          return;
        }
        input.dataset.pickMode = action === 'replace' ? 'replace' : 'append';
        input.click();
      });
      zone.addEventListener('click', (e) => {
        const replaceBtn = e.target.closest('[data-replace-file]');
        if (!replaceBtn) return;
        const idx = Number(replaceBtn.getAttribute('data-replace-file'));
        if (Number.isNaN(idx)) return;
        replacePicker.dataset.replaceIndex = String(idx);
        replacePicker.click();
      });
      zone.addEventListener('click', (e) => {
        const removeBtn = e.target.closest('[data-remove-file]');
        if (!removeBtn) return;
        const idx = Number(removeBtn.getAttribute('data-remove-file'));
        if (Number.isNaN(idx)) return;
        buffer.splice(idx, 1);
        syncInputFiles();
        setFileName();
      });
      zone.addEventListener('dragover', (e) => {
        e.preventDefault();
        zone.classList.add('is-dragover');
      });
      zone.addEventListener('dragleave', (e) => {
        if (!zone.contains(e.relatedTarget)) {
          zone.classList.remove('is-dragover');
        }
      });
      zone.addEventListener('drop', (e) => {
        e.preventDefault();
        zone.classList.remove('is-dragover');
        appendFiles(Array.from(e.dataTransfer?.files || []), 'append');
      });
      input?.addEventListener('change', () => {
        const mode = input.dataset.pickMode === 'replace' ? 'replace' : 'append';
        appendFiles(Array.from(input.files || []), mode);
        input.dataset.pickMode = 'append';
      });
      replacePicker.addEventListener('change', () => {
        const idx = Number(replacePicker.dataset.replaceIndex);
        const file = replacePicker.files?.[0] || null;
        if (!Number.isNaN(idx) && file) {
          replaceFileAt(idx, file);
        }
        replacePicker.value = '';
        replacePicker.dataset.replaceIndex = '';
      });
      setFileName();
    });
  }

  function initDateFieldFocusFix() {
    const loanDate = document.getElementById('loan_date');
    const returnDate = document.getElementById('return_date_planned');
    if (!loanDate || !returnDate) return;

    const toIso = (dateObj) => {
      const y = dateObj.getFullYear();
      const m = String(dateObj.getMonth() + 1).padStart(2, '0');
      const d = String(dateObj.getDate()).padStart(2, '0');
      return `${y}-${m}-${d}`;
    };

    const applyMinReturnDate = () => {
      if (!loanDate.value) return;
      returnDate.min = loanDate.value;

      if (!returnDate.value) {
        const nextDay = new Date(`${loanDate.value}T00:00:00`);
        if (!Number.isNaN(nextDay.getTime())) {
          nextDay.setDate(nextDay.getDate() + 1);
          returnDate.value = toIso(nextDay);
        }
      }
      if (returnDate.value && returnDate.value < loanDate.value) {
        returnDate.value = loanDate.value;
      }
    };

    const setReturnByOffset = (offset) => {
      const base = loanDate.value ? new Date(`${loanDate.value}T00:00:00`) : new Date();
      if (Number.isNaN(base.getTime())) return;
      base.setDate(base.getDate() + offset);
      returnDate.value = toIso(base);
      applyMinReturnDate();
    };

    loanDate.addEventListener('change', applyMinReturnDate);
    applyMinReturnDate();

    document.querySelectorAll('[data-date-preset]').forEach((btn) => {
      btn.addEventListener('click', () => {
        const preset = btn.getAttribute('data-date-preset');
        if (preset === 'today') setReturnByOffset(0);
        if (preset === 'plus1') setReturnByOffset(1);
        if (preset === 'plus7') setReturnByOffset(7);
      });
    });
  }
</script>
@endpush



