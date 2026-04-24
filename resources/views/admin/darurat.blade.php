@extends('layouts.admin')

@section('title', 'Permintaan Darurat')
@section('topbar-title', 'Permintaan Darurat')

@section('content')
<div class="page-header">
  <h1>🚨 Permintaan Darurat</h1>
  <p>Kelola dan tindak lanjuti permintaan darah darurat dari pengguna</p>
</div>

<!-- Filter -->
<div class="form-card" style="margin-bottom:20px;padding:16px 20px;">
  <form method="GET" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap;">
    <div>
      <label class="form-label" style="margin-bottom:4px;">Status</label>
      <select name="status" class="form-control" style="min-width:140px;">
        <option value="">Semua Status</option>
        <option value="menunggu"  {{ request('status') == 'menunggu'  ? 'selected' : '' }}>Menunggu</option>
        <option value="diproses"  {{ request('status') == 'diproses'  ? 'selected' : '' }}>Diproses</option>
        <option value="selesai"   {{ request('status') == 'selesai'   ? 'selected' : '' }}>Selesai</option>
        <option value="ditolak"   {{ request('status') == 'ditolak'   ? 'selected' : '' }}>Ditolak</option>
      </select>
    </div>
    <div>
      <label class="form-label" style="margin-bottom:4px;">Urgensi</label>
      <select name="urgensi" class="form-control" style="min-width:140px;">
        <option value="">Semua Urgensi</option>
        <option value="darurat"   {{ request('urgensi') == 'darurat'   ? 'selected' : '' }}>🔴 Darurat</option>
        <option value="normal"    {{ request('urgensi') == 'normal'    ? 'selected' : '' }}>🟡 Normal</option>
        <option value="terjadwal" {{ request('urgensi') == 'terjadwal' ? 'selected' : '' }}>🔵 Terjadwal</option>
      </select>
    </div>
    <button type="submit" class="btn btn-primary" style="height:38px;">🔍 Filter</button>
    <a href="{{ route('admin.darurat.index') }}" class="btn btn-secondary" style="height:38px;">Reset</a>
  </form>
</div>

<div class="table-card">
  <div class="table-header">
    <h3>Daftar Permintaan</h3>
    <span style="color:#64748b;font-size:.85rem;">{{ $permintaans->total() }} total permintaan</span>
  </div>
  <div style="overflow-x:auto;">
    <table>
      <thead>
        <tr>
          <th>No. Resi</th>
          <th>Pasien</th>
          <th>Kebutuhan</th>
          <th>Urgensi</th>
          <th>Rumah Sakit</th>
          <th>Tgl. Dibutuhkan</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($permintaans as $p)
          <tr>
            <td>
              <span style="font-family:monospace;font-size:.78rem;background:#f1f5f9;padding:3px 8px;border-radius:4px;">
                {{ $p->nomor_resi ?? $p->kode ?? '-' }}
              </span>
            </td>
            <td>
              <strong>{{ $p->nama_pasien }}</strong>
              <br><small style="color:#94a3b8;">{{ $p->usia_pasien ?? $p->usia }} thn · {{ ucfirst($p->jenis_kelamin ?? $p->gender ?? '-') }}</small>
            </td>
            <td>
              <strong style="color:#e53e3e;">{{ $p->golongan_darah }}{{ $p->rhesus }}</strong>
              <br><small>{{ $p->jumlah_kantong ?? $p->jumlah }} kantong</small>
            </td>
            <td>
              <span class="badge badge-{{ $p->urgensi_color }}">
                {{ ucfirst($p->tingkat_urgensi ?? 'normal') }}
              </span>
            </td>
            <td style="max-width:150px;">
              <span style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                {{ $p->nama_rumah_sakit ?? $p->nama_rs ?? '-' }}
              </span>
            </td>
            <td>{{ $p->tanggal_dibutuhkan ? $p->tanggal_dibutuhkan->format('d/m/Y') : '-' }}</td>
            <td>
              <span class="badge badge-{{ $p->status_color }}">{{ $p->status_label }}</span>
            </td>
            <td>
              <div style="display:flex;gap:5px;">
                <a href="{{ route('admin.darurat.show', $p) }}" class="btn btn-sm btn-info">👁️</a>
                <form method="POST" action="{{ route('admin.darurat.destroy', $p) }}"
                      onsubmit="return confirm('Hapus permintaan ini?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" style="text-align:center;padding:40px;color:#94a3b8;">
              Tidak ada permintaan darurat.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($permintaans->hasPages())
    <div style="padding:16px 20px;border-top:1px solid #f1f5f9;">
      {{ $permintaans->appends(request()->query())->links() }}
    </div>
  @endif
</div>
@endsection
