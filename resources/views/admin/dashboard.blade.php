@extends('layouts.admin')

@section('title', 'Dashboard')
@section('topbar-title', 'Dashboard')

@section('content')
<div class="page-header">
  <h1>Dashboard</h1>
  <p>Selamat datang, <strong>{{ auth()->user()->name }}</strong> — {{ now()->format('d F Y') }}</p>
</div>

<!-- Stat Cards -->
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon" style="background:#fee2e2;">🩸</div>
    <div class="stat-info">
      <h3>{{ number_format($totalStokKantong) }}</h3>
      <p>Total Kantong Darah</p>
      <small>dari {{ $totalStok }} rumah sakit</small>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:#fef3c7;">🚨</div>
    <div class="stat-info">
      <h3>{{ $totalPermintaan }}</h3>
      <p>Permintaan Darurat</p>
      <small>{{ $permintaanBaru }} baru menunggu</small>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:#dbeafe;">💬</div>
    <div class="stat-info">
      <h3>{{ $totalFeedback }}</h3>
      <p>Total Feedback</p>
      <small>{{ $feedbackBelum }} belum dibalas</small>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:#d1fae5;">👥</div>
    <div class="stat-info">
      <h3>{{ $totalUsers }}</h3>
      <p>Pengguna Terdaftar</p>
    </div>
  </div>
</div>

<!-- Stok Per Golongan -->
<div class="table-card" style="margin-bottom:24px;">
  <div class="table-header">
    <h3>📊 Stok Darah per Golongan</h3>
    <a href="{{ route('admin.stok.index') }}" class="btn btn-sm btn-secondary">Kelola Stok</a>
  </div>
  <div style="padding:20px;display:grid;grid-template-columns:repeat(auto-fit,minmax(110px,1fr));gap:12px;">
    @foreach($stokPerGolongan as $golongan => $jumlah)
      @php
        $persen = $totalStokKantong > 0 ? round(($jumlah / $totalStokKantong) * 100) : 0;
        $color  = $jumlah < 10 ? '#ef4444' : ($jumlah < 20 ? '#f59e0b' : '#10b981');
      @endphp
      <div style="background:#f8f9fa;border-radius:10px;padding:14px;text-align:center;border:2px solid {{ $color }}20;">
        <div style="font-size:1.3rem;font-weight:700;color:{{ $color }};">{{ $jumlah }}</div>
        <div style="font-size:.8rem;font-weight:600;color:#1a1a2e;">Gol. {{ $golongan }}</div>
        <div style="font-size:.7rem;color:#94a3b8;">kantong</div>
        @if($jumlah < 10)
          <span class="badge badge-danger" style="margin-top:4px;">Kritis</span>
        @elseif($jumlah < 20)
          <span class="badge badge-warning" style="margin-top:4px;">Rendah</span>
        @endif
      </div>
    @endforeach
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
  <!-- Permintaan Terbaru -->
  <div class="table-card">
    <div class="table-header">
      <h3>🚨 Permintaan Darurat Terbaru</h3>
      <a href="{{ route('admin.darurat.index') }}" class="btn btn-sm btn-secondary">Lihat Semua</a>
    </div>
    <table>
      <thead>
        <tr>
          <th>Pasien</th>
          <th>Gol. Darah</th>
          <th>Urgensi</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse($permintaanTerbaru as $p)
          <tr>
            <td>
              <a href="{{ route('admin.darurat.show', $p) }}" style="color:#e53e3e;text-decoration:none;font-weight:500;">
                {{ $p->nama_pasien }}
              </a>
              <br><small style="color:#94a3b8;">{{ $p->created_at->diffForHumans() }}</small>
            </td>
            <td><strong>{{ $p->golongan_darah }}{{ $p->rhesus }}</strong></td>
            <td>
              <span class="badge badge-{{ $p->urgensi_color }}">{{ ucfirst($p->tingkat_urgensi ?? '-') }}</span>
            </td>
            <td>
              <span class="badge badge-{{ $p->status_color }}">{{ $p->status_label }}</span>
            </td>
          </tr>
        @empty
          <tr><td colspan="4" style="text-align:center;color:#94a3b8;padding:24px;">Belum ada permintaan.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- Feedback Terbaru -->
  <div class="table-card">
    <div class="table-header">
      <h3>💬 Feedback Terbaru</h3>
      <a href="{{ route('admin.feedback.index') }}" class="btn btn-sm btn-secondary">Lihat Semua</a>
    </div>
    <table>
      <thead>
        <tr>
          <th>Pengirim</th>
          <th>Pesan</th>
          <th>Rating</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse($feedbackTerbaru as $fb)
          <tr>
            <td>
              <strong>{{ $fb->nama ?? 'Anonim' }}</strong>
              <br><small style="color:#94a3b8;">{{ $fb->created_at->diffForHumans() }}</small>
            </td>
            <td style="max-width:160px;">
              <span style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                {{ $fb->pesan }}
              </span>
            </td>
            <td>
              @if($fb->rating)
                <span style="color:#f59e0b;">{{ str_repeat('★', $fb->rating) }}{{ str_repeat('☆', 5 - $fb->rating) }}</span>
              @else
                <span style="color:#cbd5e1;">—</span>
              @endif
            </td>
            <td>
              @if($fb->status === 'sudah_dibalas')
                <span class="badge badge-success">Dibalas</span>
              @else
                <span class="badge badge-warning">Belum</span>
              @endif
            </td>
          </tr>
        @empty
          <tr><td colspan="4" style="text-align:center;color:#94a3b8;padding:24px;">Belum ada feedback.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
