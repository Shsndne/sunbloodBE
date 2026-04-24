@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')
@section('topbar-title', 'Manajemen Pengguna')

@section('content')
<div class="page-header">
  <h1>👥 Manajemen Pengguna</h1>
  <p>Daftar pengguna yang terdaftar di sistem Sunblood</p>
</div>

<div class="table-card">
  <div class="table-header">
    <h3>Daftar Pengguna</h3>
    <span style="color:#64748b;font-size:.85rem;">{{ $users->total() }} pengguna terdaftar</span>
  </div>
  <div style="overflow-x:auto;">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Nama</th>
          <th>Email</th>
          <th>No. HP</th>
          <th>Terdaftar</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $user)
          <tr>
            <td style="color:#94a3b8;">{{ $loop->iteration }}</td>
            <td>
              <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:36px;height:36px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;color:#e53e3e;font-size:.9rem;flex-shrink:0;">
                  {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                  <strong>{{ $user->name }}</strong>
                </div>
              </div>
            </td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->phone ?? '-' }}</td>
            <td>{{ $user->created_at->format('d/m/Y') }}</td>
            <td>
              <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                    onsubmit="return confirm('Hapus akun {{ $user->name }}? Aksi ini tidak bisa dibatalkan.')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">🗑️ Hapus</button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" style="text-align:center;padding:40px;color:#94a3b8;">
              Belum ada pengguna terdaftar.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())
    <div style="padding:16px 20px;border-top:1px solid #f1f5f9;">
      {{ $users->links() }}
    </div>
  @endif
</div>
@endsection
