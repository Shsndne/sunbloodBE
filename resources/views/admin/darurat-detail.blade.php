@extends('layouts.admin')

@section('title', 'Detail Permintaan Darurat')
@section('topbar-title', 'Detail Permintaan Darurat')

@section('content')
<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;">
  <div>
    <h1>🚨 Detail Permintaan</h1>
    <p>No. Resi: <strong style="font-family:monospace;">{{ $permintaan->nomor_resi ?? $permintaan->kode ?? '-' }}</strong></p>
  </div>
  <a href="{{ route('admin.darurat.index') }}" class="btn btn-secondary">← Kembali</a>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;">

  <!-- Detail Data -->
  <div style="display:flex;flex-direction:column;gap:20px;">

    <!-- Info Pasien -->
    <div class="form-card">
      <h3 style="font-size:1rem;font-weight:600;margin-bottom:16px;color:#e53e3e;">👤 Data Pasien</h3>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div>
          <p style="font-size:.78rem;color:#94a3b8;font-weight:600;text-transform:uppercase;">Nama Pasien</p>
          <p style="font-weight:600;">{{ $permintaan->nama_pasien }}</p>
        </div>
        <div>
          <p style="font-size:.78rem;color:#94a3b8;font-weight:600;text-transform:uppercase;">Usia</p>
          <p>{{ $permintaan->usia_pasien ?? $permintaan->usia ?? '-' }} tahun</p>
        </div>
        <div>
          <p style="font-size:.78rem;color:#94a3b8;font-weight:600;text-transform:uppercase;">Jenis Kelamin</p>
          <p>{{ ucfirst($permintaan->jenis_kelamin ?? $permintaan->gender ?? '-') }}</p>
        </div>
        <div>
          <p style="font-size:.78rem;color:#94a3b8;font-weight:600;text-transform:uppercase;">Diagnosis</p>
          <p>{{ $permintaan->diagnosis ?? '-' }}</p>
        </div>
      </div>
    </div>

    <!-- Kebutuhan Darah -->
    <div class="form-card">
      <h3 style="font-size:1rem;font-weight:600;margin-bottom:16px;color:#e53e3e;">🩸 Kebutuhan Darah</h3>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div>
          <p style="font-size:.78rem;color:#94a3b8;font-weight:600;text-transform:uppercase;">Golongan Darah</p>
          <p style="font-size:1.5rem;font-weight:700;color:#e53e3e;">
            {{ $permintaan->golongan_darah }}{{ $permintaan->rhesus }}
          </p>
        </div>
        <div>
          <p style="font-size:.78rem;color:#94a3b8;font-weight:600;text-transform:uppercase;">Jumlah Kantong</p>
          <p style="font-size:1.5rem;font-weight:700;">{{ $permintaan->jumlah_kantong ?? $permintaan->jumlah ?? '-' }}</p>
        </div>
        <div>
          <p style="font-size:.78rem;color:#94a3b8;font-weight:600;text-transform:uppercase;">Tanggal Dibutuhkan</p>
          <p>{{ $permintaan->tanggal_dibutuhkan ? $permintaan->tanggal_dibutuhkan->format('d F Y') : '-' }}</p>
        </div>
        <div>
          <p style="font-size:.78rem;color:#94a3b8;font-weight:600;text-transform:uppercase;">Tingkat Urgensi</p>
          <span class="badge badge-{{ $permintaan->urgensi_color }}" style="font-size:.85rem;padding:5px 12px;">
            {{ ucfirst($permintaan->tingkat_urgensi ?? 'normal') }}
          </span>
        </div>
      </div>
    </div>

    <!-- Lokasi RS -->
    <div class="form-card">
      <h3 style="font-size:1rem;font-weight:600;margin-bottom:16px;color:#e53e3e;">🏥 Lokasi Rumah Sakit</h3>
      <div style="display:grid;gap:12px;">
        <div>
          <p style="font-size:.78rem;color:#94a3b8;font-weight:600;text-transform:uppercase;">Nama Rumah Sakit</p>
          <p style="font-weight:600;">{{ $permintaan->nama_rumah_sakit ?? $permintaan->nama_rs ?? '-' }}</p>
        </div>
        <div>
          <p style="font-size:.78rem;color:#94a3b8;font-weight:600;text-transform:uppercase;">Alamat Lengkap</p>
          <p>{{ $permintaan->alamat_lengkap ?? $permintaan->alamat_rs ?? '-' }}</p>
        </div>
      </div>
    </div>

    <!-- Kontak Darurat -->
    <div class="form-card">
      <h3 style="font-size:1rem;font-weight:600;margin-bottom:16px;color:#e53e3e;">📞 Kontak Darurat</h3>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div>
          <p style="font-size:.78rem;color:#94a3b8;font-weight:600;text-transform:uppercase;">Nama Kontak</p>
          <p style="font-weight:600;">{{ $permintaan->nama_kontak ?? '-' }}</p>
        </div>
        <div>
          <p style="font-size:.78rem;color:#94a3b8;font-weight:600;text-transform:uppercase;">Nomor Telepon</p>
          <p>
            <a href="tel:{{ $permintaan->telepon_kontak ?? $permintaan->kontak }}" style="color:#e53e3e;font-weight:600;">
              {{ $permintaan->telepon_kontak ?? $permintaan->kontak ?? '-' }}
            </a>
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Panel Status & Aksi -->
  <div style="display:flex;flex-direction:column;gap:20px;">

    <!-- Status Saat Ini -->
    <div class="form-card" style="text-align:center;">
      <p style="font-size:.78rem;color:#94a3b8;font-weight:600;text-transform:uppercase;margin-bottom:12px;">Status Saat Ini</p>
      <span class="badge badge-{{ $permintaan->status_color }}" style="font-size:1rem;padding:8px 20px;">
        {{ $permintaan->status_label }}
      </span>
      <p style="margin-top:12px;font-size:.78rem;color:#94a3b8;">
        Dibuat: {{ $permintaan->created_at->format('d/m/Y H:i') }}
      </p>
    </div>

    <!-- Update Status -->
    <div class="form-card">
      <h3 style="font-size:1rem;font-weight:600;margin-bottom:16px;">🔄 Update Status</h3>
      <form method="POST" action="{{ route('admin.darurat.status', $permintaan) }}">
        @csrf @method('PATCH')
        <div class="form-group">
          <label class="form-label">Status Baru</label>
          <select name="status" class="form-control">
            <option value="menunggu"  {{ $permintaan->status == 'menunggu'  ? 'selected' : '' }}>⏳ Menunggu</option>
            <option value="diproses"  {{ $permintaan->status == 'diproses'  ? 'selected' : '' }}>🔄 Diproses</option>
            <option value="selesai"   {{ $permintaan->status == 'selesai'   ? 'selected' : '' }}>✅ Selesai</option>
            <option value="ditolak"   {{ $permintaan->status == 'ditolak'   ? 'selected' : '' }}>❌ Ditolak</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;">Simpan Status</button>
      </form>
    </div>

    <!-- Catatan Admin -->
    @if($permintaan->catatan)
      <div class="form-card">
        <h3 style="font-size:1rem;font-weight:600;margin-bottom:10px;">📝 Catatan Admin</h3>
        <p style="font-size:.9rem;color:#374151;">{{ $permintaan->catatan }}</p>
      </div>
    @endif

    <!-- Hapus -->
    <form method="POST" action="{{ route('admin.darurat.destroy', $permintaan) }}"
          onsubmit="return confirm('Yakin hapus permintaan ini secara permanen?')">
      @csrf @method('DELETE')
      <button type="submit" class="btn btn-danger" style="width:100%;">🗑️ Hapus Permintaan</button>
    </form>
  </div>
</div>
@endsection
