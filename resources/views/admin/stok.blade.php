@extends('layouts.admin')

@section('title', 'Manajemen Stok Darah')
@section('topbar-title', 'Manajemen Stok Darah')

@section('content')
<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;">
  <div>
    <h1>Manajemen Stok Darah</h1>
    <p>Kelola ketersediaan darah di setiap rumah sakit</p>
  </div>
  <a href="{{ route('admin.stok.create') }}" class="btn btn-primary">+ Tambah Stok</a>
</div>

<div class="table-card">
  <div class="table-header">
    <h3>🏥 Daftar Rumah Sakit</h3>
    <span style="color:#64748b;font-size:.85rem;">{{ $stoks->total() }} rumah sakit terdaftar</span>
  </div>
  <div style="overflow-x:auto;">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Rumah Sakit</th>
          <th style="text-align:center;">A+</th>
          <th style="text-align:center;">A-</th>
          <th style="text-align:center;">B+</th>
          <th style="text-align:center;">B-</th>
          <th style="text-align:center;">AB+</th>
          <th style="text-align:center;">AB-</th>
          <th style="text-align:center;">O+</th>
          <th style="text-align:center;">O-</th>
          <th style="text-align:center;">Total</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($stoks as $stok)
          <tr>
            <td style="color:#94a3b8;">{{ $loop->iteration }}</td>
            <td>
              <strong>{{ $stok->nama_rs }}</strong>
              <br><small style="color:#94a3b8;">Update: {{ $stok->updated_at->format('d/m/Y H:i') }}</small>
            </td>
            @foreach(['stok_a_plus','stok_a_minus','stok_b_plus','stok_b_minus','stok_ab_plus','stok_ab_minus','stok_o_plus','stok_o_minus'] as $col)
              @php $val = $stok->$col; @endphp
              <td style="text-align:center;">
                <span style="font-weight:600;color:{{ $val < 5 ? '#ef4444' : ($val < 15 ? '#f59e0b' : '#10b981') }}">
                  {{ $val }}
                </span>
              </td>
            @endforeach
            <td style="text-align:center;"><strong>{{ $stok->total_stok }}</strong></td>
            <td>
              <div style="display:flex;gap:6px;flex-wrap:nowrap;">
                <a href="{{ route('admin.stok.edit', $stok) }}" class="btn btn-sm btn-info">✏️ Edit</a>
                <form method="POST" action="{{ route('admin.stok.destroy', $stok) }}"
                      onsubmit="return confirm('Hapus data stok {{ $stok->nama_rs }}?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="12" style="text-align:center;padding:40px;color:#94a3b8;">
              Belum ada data stok darah.
              <a href="{{ route('admin.stok.create') }}" style="color:#e53e3e;">Tambah sekarang</a>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($stoks->hasPages())
    <div style="padding:16px 20px;border-top:1px solid #f1f5f9;">
      {{ $stoks->links() }}
    </div>
  @endif
</div>

@push('styles')
<style>
  /* Keterangan warna */
  .legend { display:flex; gap:16px; margin-top:16px; flex-wrap:wrap; font-size:.8rem; }
  .legend span { display:flex; align-items:center; gap:5px; }
  .dot { width:10px; height:10px; border-radius:50%; }
</style>
@endpush

<div class="legend">
  <span><div class="dot" style="background:#ef4444;"></div> Kritis (&lt;5 kantong)</span>
  <span><div class="dot" style="background:#f59e0b;"></div> Rendah (5–14 kantong)</span>
  <span><div class="dot" style="background:#10b981;"></div> Aman (≥15 kantong)</span>
</div>
@endsection
