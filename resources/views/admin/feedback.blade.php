@extends('layouts.admin')

@section('title', 'Feedback Pengguna')
@section('topbar-title', 'Feedback Pengguna')

@section('content')
<div class="page-header">
  <h1>💬 Feedback Pengguna</h1>
  <p>Balas dan kelola masukan dari pengguna</p>
</div>

<!-- Ringkasan -->
<div class="stats-grid" style="margin-bottom:24px;">
  <div class="stat-card">
    <div class="stat-icon" style="background:#dbeafe;">💬</div>
    <div class="stat-info">
      <h3>{{ $feedbacks->total() }}</h3>
      <p>Total Feedback</p>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:#fef3c7;">⏳</div>
    <div class="stat-info">
      <h3>{{ $feedbacks->where('status','belum_dibalas')->count() }}</h3>
      <p>Belum Dibalas</p>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:#d1fae5;">⭐</div>
    <div class="stat-info">
      <h3>{{ $rataRating ? number_format($rataRating, 1) : '-' }}</h3>
      <p>Rating Rata-rata</p>
    </div>
  </div>
</div>

<!-- Daftar Feedback -->
@forelse($feedbacks as $fb)
  <div class="form-card" style="margin-bottom:16px;">
    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;">
      <div style="flex:1;">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;flex-wrap:wrap;">
          <strong>{{ $fb->nama ?? 'Anonim' }}</strong>
          @if($fb->email)
            <span style="color:#64748b;font-size:.8rem;">{{ $fb->email }}</span>
          @endif
          @if($fb->rating)
            <span style="color:#f59e0b;">
              {{ str_repeat('★', $fb->rating) }}{{ str_repeat('☆', 5 - $fb->rating) }}
            </span>
          @endif
          <span class="badge {{ $fb->status === 'sudah_dibalas' ? 'badge-success' : 'badge-warning' }}">
            {{ $fb->status === 'sudah_dibalas' ? 'Sudah Dibalas' : 'Belum Dibalas' }}
          </span>
          <small style="color:#94a3b8;">{{ $fb->created_at->diffForHumans() }}</small>
        </div>

        <p style="color:#374151;line-height:1.6;margin-bottom:12px;">{{ $fb->pesan }}</p>

        @if($fb->admin_response)
          <div style="background:#f0fdf4;border-left:3px solid #10b981;padding:10px 14px;border-radius:0 8px 8px 0;margin-bottom:12px;">
            <p style="font-size:.78rem;font-weight:600;color:#065f46;margin-bottom:4px;">Balasan Admin ({{ $fb->responded_at?->format('d/m/Y H:i') }}):</p>
            <p style="color:#374151;font-size:.9rem;">{{ $fb->admin_response }}</p>
          </div>
        @endif

        @if($fb->status === 'belum_dibalas')
          <form method="POST" action="{{ route('admin.feedback.balas', $fb) }}" style="display:flex;gap:8px;align-items:flex-end;">
            @csrf
            <div style="flex:1;">
              <textarea name="admin_response" class="form-control" rows="2"
                        placeholder="Tulis balasan Anda..." required minlength="5"></textarea>
            </div>
            <button type="submit" class="btn btn-success btn-sm" style="height:38px;white-space:nowrap;">💬 Balas</button>
          </form>
        @endif
      </div>

      <!-- Hapus -->
      <form method="POST" action="{{ route('admin.feedback.destroy', $fb) }}"
            onsubmit="return confirm('Hapus feedback ini?')">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger" style="flex-shrink:0;">🗑️</button>
      </form>
    </div>
  </div>
@empty
  <div style="text-align:center;padding:60px;color:#94a3b8;">
    <div style="font-size:3rem;">💬</div>
    <p style="margin-top:12px;">Belum ada feedback dari pengguna.</p>
  </div>
@endforelse

@if($feedbacks->hasPages())
  <div style="margin-top:16px;">
    {{ $feedbacks->links() }}
  </div>
@endif
@endsection
