@extends('layouts.admin')

@section('title', isset($stok) ? 'Edit Stok Darah' : 'Tambah Stok Darah')
@section('topbar-title', isset($stok) ? 'Edit Stok Darah' : 'Tambah Stok Darah')

@section('content')
<div class="page-header">
  <h1>{{ isset($stok) ? '✏️ Edit Stok Darah' : '➕ Tambah Stok Darah' }}</h1>
  <p>{{ isset($stok) ? 'Perbarui data stok darah ' . $stok->nama_rs : 'Tambahkan data stok darah rumah sakit baru' }}</p>
</div>

<div style="max-width:700px;">
  <div class="form-card">
    <form method="POST"
          action="{{ isset($stok) ? route('admin.stok.update', $stok) : route('admin.stok.store') }}"
          enctype="multipart/form-data">
      @csrf
      @if(isset($stok)) @method('PUT') @endif

      <!-- Nama RS -->
      <div class="form-group">
        <label class="form-label">Nama Rumah Sakit <span>*</span></label>
        <input type="text" name="nama_rs" class="form-control @error('nama_rs') is-invalid @enderror"
               value="{{ old('nama_rs', $stok->nama_rs ?? '') }}"
               placeholder="Contoh: RSUP Dr. Kariadi" required>
        @error('nama_rs') <span class="invalid-feedback">{{ $message }}</span> @enderror
      </div>

      <!-- Foto -->
      <div class="form-group">
        <label class="form-label">Foto Rumah Sakit <small style="color:#94a3b8;font-weight:400;">(opsional, max 2MB)</small></label>
        @if(isset($stok) && $stok->foto)
          <div style="margin-bottom:8px;">
            <img src="{{ asset('storage/' . $stok->foto) }}" alt="Foto RS"
                 style="height:80px;border-radius:8px;object-fit:cover;">
          </div>
        @endif
        <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror"
               accept="image/*">
        @error('foto') <span class="invalid-feedback">{{ $message }}</span> @enderror
      </div>

      <hr style="border:none;border-top:1px solid #f1f5f9;margin:20px 0;">
      <p style="font-size:.85rem;font-weight:600;color:#64748b;margin-bottom:16px;">🩸 STOK PER GOLONGAN DARAH (kantong)</p>

      <!-- Golongan A -->
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Golongan A+ <span>*</span></label>
          <input type="number" name="stok_a_plus" class="form-control @error('stok_a_plus') is-invalid @enderror"
                 value="{{ old('stok_a_plus', $stok->stok_a_plus ?? 0) }}" min="0" required>
          @error('stok_a_plus') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
          <label class="form-label">Golongan A- <span>*</span></label>
          <input type="number" name="stok_a_minus" class="form-control @error('stok_a_minus') is-invalid @enderror"
                 value="{{ old('stok_a_minus', $stok->stok_a_minus ?? 0) }}" min="0" required>
          @error('stok_a_minus') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>
      </div>

      <!-- Golongan B -->
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Golongan B+ <span>*</span></label>
          <input type="number" name="stok_b_plus" class="form-control @error('stok_b_plus') is-invalid @enderror"
                 value="{{ old('stok_b_plus', $stok->stok_b_plus ?? 0) }}" min="0" required>
          @error('stok_b_plus') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
          <label class="form-label">Golongan B- <span>*</span></label>
          <input type="number" name="stok_b_minus" class="form-control @error('stok_b_minus') is-invalid @enderror"
                 value="{{ old('stok_b_minus', $stok->stok_b_minus ?? 0) }}" min="0" required>
          @error('stok_b_minus') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>
      </div>

      <!-- Golongan AB -->
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Golongan AB+ <span>*</span></label>
          <input type="number" name="stok_ab_plus" class="form-control @error('stok_ab_plus') is-invalid @enderror"
                 value="{{ old('stok_ab_plus', $stok->stok_ab_plus ?? 0) }}" min="0" required>
          @error('stok_ab_plus') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
          <label class="form-label">Golongan AB- <span>*</span></label>
          <input type="number" name="stok_ab_minus" class="form-control @error('stok_ab_minus') is-invalid @enderror"
                 value="{{ old('stok_ab_minus', $stok->stok_ab_minus ?? 0) }}" min="0" required>
          @error('stok_ab_minus') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>
      </div>

      <!-- Golongan O -->
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Golongan O+ <span>*</span></label>
          <input type="number" name="stok_o_plus" class="form-control @error('stok_o_plus') is-invalid @enderror"
                 value="{{ old('stok_o_plus', $stok->stok_o_plus ?? 0) }}" min="0" required>
          @error('stok_o_plus') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
          <label class="form-label">Golongan O- <span>*</span></label>
          <input type="number" name="stok_o_minus" class="form-control @error('stok_o_minus') is-invalid @enderror"
                 value="{{ old('stok_o_minus', $stok->stok_o_minus ?? 0) }}" min="0" required>
          @error('stok_o_minus') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">
          {{ isset($stok) ? '💾 Simpan Perubahan' : '➕ Tambah Stok' }}
        </button>
        <a href="{{ route('admin.stok.index') }}" class="btn btn-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>
@endsection
