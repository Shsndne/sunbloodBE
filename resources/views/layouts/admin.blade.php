<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Admin') - Sunblood</title>
  <link rel="stylesheet" href="{{ asset('assets/css/config.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
  @stack('styles')
  <style>
    /* ── LAYOUT DASAR ── */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Segoe UI', sans-serif; background: #f8f9fa; color: #1a1a2e; display: flex; min-height: 100vh; }

    /* ── SIDEBAR ── */
    .sidebar {
      width: 240px; min-height: 100vh; background: #1a1a2e;
      display: flex; flex-direction: column; position: fixed; top: 0; left: 0; z-index: 100;
      transition: transform .3s ease;
    }
    .sidebar-logo {
      padding: 20px 24px; font-size: 1.4rem; font-weight: 700; color: #fff;
      border-bottom: 1px solid rgba(255,255,255,.1); display: flex; align-items: center; gap: 8px;
    }
    .sidebar-logo span { color: #e53e3e; }
    .sidebar-nav { flex: 1; padding: 16px 0; overflow-y: auto; }
    .nav-item {
      display: flex; align-items: center; gap: 10px; padding: 11px 24px;
      color: rgba(255,255,255,.7); text-decoration: none; font-size: .9rem;
      transition: all .2s; border-left: 3px solid transparent; position: relative;
    }
    .nav-item:hover, .nav-item.active {
      color: #fff; background: rgba(255,255,255,.08); border-left-color: #e53e3e;
    }
    .nav-item .nav-icon { font-size: 1.1rem; width: 20px; text-align: center; }
    .badge-red {
      background: #e53e3e; color: #fff; border-radius: 10px; padding: 1px 7px;
      font-size: .7rem; font-weight: 700; margin-left: auto;
    }
    .sidebar-footer {
      padding: 16px 24px; border-top: 1px solid rgba(255,255,255,.1);
      color: rgba(255,255,255,.6); font-size: .8rem;
    }
    .sidebar-footer p { margin-bottom: 8px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .btn-logout {
      background: #e53e3e; color: #fff; border: none; padding: 6px 14px;
      border-radius: 6px; cursor: pointer; font-size: .8rem; width: 100%;
    }
    .btn-logout:hover { background: #c53030; }

    /* ── MAIN ── */
    .main-wrapper { margin-left: 240px; flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
    .main-content { padding: 28px 32px; flex: 1; }

    /* ── TOPBAR ── */
    .topbar {
      background: #fff; border-bottom: 1px solid #e2e8f0;
      padding: 14px 32px; display: flex; align-items: center; justify-content: space-between;
      position: sticky; top: 0; z-index: 50;
    }
    .topbar-title { font-size: 1.1rem; font-weight: 600; color: #1a1a2e; }
    .topbar-right { display: flex; align-items: center; gap: 14px; font-size: .85rem; color: #64748b; }

    /* ── PAGE HEADER ── */
    .page-header { margin-bottom: 24px; }
    .page-header h1 { font-size: 1.6rem; font-weight: 700; color: #1a1a2e; }
    .page-header p { color: #64748b; margin-top: 4px; font-size: .9rem; }

    /* ── ALERT ── */
    .alert {
      padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: .9rem; font-weight: 500;
    }
    .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
    .alert-danger  { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
    .alert-warning { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }
    .alert-info    { background: #dbeafe; color: #1e40af; border: 1px solid #93c5fd; }

    /* ── STAT CARDS ── */
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 28px; }
    .stat-card {
      background: #fff; border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 14px;
      box-shadow: 0 1px 4px rgba(0,0,0,.06); border: 1px solid #f1f5f9;
    }
    .stat-icon { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0; }
    .stat-info h3 { font-size: 1.5rem; font-weight: 700; color: #1a1a2e; }
    .stat-info p { color: #64748b; font-size: .8rem; margin-top: 2px; }
    .stat-info small { color: #94a3b8; font-size: .75rem; }

    /* ── TABLES ── */
    .table-card { background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.06); border: 1px solid #f1f5f9; }
    .table-header { padding: 16px 20px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; }
    .table-header h3 { font-size: 1rem; font-weight: 600; color: #1a1a2e; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #f8f9fa; padding: 11px 16px; text-align: left; font-size: .78rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: #64748b; }
    td { padding: 12px 16px; font-size: .875rem; border-bottom: 1px solid #f1f5f9; color: #374151; vertical-align: middle; }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: #fafbfc; }

    /* ── BADGES ── */
    .badge {
      display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 20px;
      font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: .04em;
    }
    .badge-danger   { background: #fee2e2; color: #991b1b; }
    .badge-warning  { background: #fef3c7; color: #92400e; }
    .badge-success  { background: #d1fae5; color: #065f46; }
    .badge-info     { background: #dbeafe; color: #1e40af; }
    .badge-secondary{ background: #f1f5f9; color: #64748b; }

    /* ── BUTTONS ── */
    .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 8px; font-size: .85rem; font-weight: 500; cursor: pointer; text-decoration: none; border: none; transition: all .2s; }
    .btn-primary   { background: #e53e3e; color: #fff; }
    .btn-primary:hover { background: #c53030; }
    .btn-secondary { background: #f1f5f9; color: #374151; }
    .btn-secondary:hover { background: #e2e8f0; }
    .btn-success   { background: #10b981; color: #fff; }
    .btn-success:hover { background: #059669; }
    .btn-danger    { background: #ef4444; color: #fff; }
    .btn-danger:hover { background: #dc2626; }
    .btn-info      { background: #3b82f6; color: #fff; }
    .btn-info:hover { background: #2563eb; }
    .btn-sm { padding: 5px 10px; font-size: .78rem; }
    .btn-outline { background: transparent; border: 1px solid #e53e3e; color: #e53e3e; }
    .btn-outline:hover { background: #e53e3e; color: #fff; }

    /* ── FORMS ── */
    .form-card { background: #fff; border-radius: 12px; padding: 28px; box-shadow: 0 1px 4px rgba(0,0,0,.06); border: 1px solid #f1f5f9; }
    .form-group { margin-bottom: 20px; }
    .form-label { display: block; font-size: .85rem; font-weight: 600; color: #374151; margin-bottom: 6px; }
    .form-label span { color: #e53e3e; }
    .form-control {
      width: 100%; padding: 9px 12px; border: 1px solid #e2e8f0; border-radius: 8px;
      font-size: .9rem; color: #374151; transition: border-color .2s;
    }
    .form-control:focus { outline: none; border-color: #e53e3e; box-shadow: 0 0 0 3px rgba(229,62,62,.1); }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .form-actions { display: flex; gap: 10px; margin-top: 24px; }
    .invalid-feedback { color: #e53e3e; font-size: .78rem; margin-top: 4px; display: block; }

    /* ── SIDEBAR TOGGLE (mobile) ── */
    .sidebar-toggle { display: none; position: fixed; top: 14px; left: 14px; z-index: 200; background: #1a1a2e; color: #fff; border: none; padding: 8px 10px; border-radius: 8px; font-size: 1.2rem; cursor: pointer; }
    .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 99; }

    @media (max-width: 768px) {
      .sidebar-toggle { display: block; }
      .sidebar { transform: translateX(-100%); }
      .sidebar.open { transform: translateX(0); }
      .sidebar-overlay.show { display: block; }
      .main-wrapper { margin-left: 0; }
      .main-content { padding: 16px; }
      .form-row { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

<button class="sidebar-toggle" id="sidebarToggle">☰</button>
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<aside class="sidebar" id="sidebar">
  <div class="sidebar-logo">🩸 <span>Sun</span>blood</div>
  <nav class="sidebar-nav">
    <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
      <span class="nav-icon">📊</span> Dashboard
    </a>
    <a href="{{ route('admin.stok.index') }}" class="nav-item {{ request()->routeIs('admin.stok.*') ? 'active' : '' }}">
      <span class="nav-icon">🩸</span> Stok Darah
    </a>
    <a href="{{ route('admin.darurat.index') }}" class="nav-item {{ request()->routeIs('admin.darurat.*') ? 'active' : '' }}">
      <span class="nav-icon">🚨</span> Permintaan Darurat
      @php $permintaanBaru = \App\Models\PermintaanDarurat::whereIn('status',['menunggu','pending'])->count(); @endphp
      @if($permintaanBaru > 0)
        <span class="badge-red">{{ $permintaanBaru }}</span>
      @endif
    </a>
    <a href="{{ route('admin.feedback.index') }}" class="nav-item {{ request()->routeIs('admin.feedback.*') ? 'active' : '' }}">
      <span class="nav-icon">💬</span> Feedback
      @php $fbBelum = \App\Models\Feedback::where('status','belum_dibalas')->count(); @endphp
      @if($fbBelum > 0)
        <span class="badge-red">{{ $fbBelum }}</span>
      @endif
    </a>
    <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
      <span class="nav-icon">👥</span> Pengguna
    </a>
    <a href="{{ route('home') }}" class="nav-item" target="_blank">
      <span class="nav-icon">🌐</span> Lihat Website
    </a>
  </nav>
  <div class="sidebar-footer">
    <p>{{ auth()->user()->name ?? 'Admin' }}</p>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="btn-logout">Keluar</button>
    </form>
  </div>
</aside>

<div class="main-wrapper">
  <div class="topbar">
    <span class="topbar-title">@yield('topbar-title', 'Admin Panel')</span>
    <div class="topbar-right">
      <span>{{ now()->format('d F Y') }}</span>
      <strong>{{ auth()->user()->name ?? 'Admin' }}</strong>
    </div>
  </div>

  <main class="main-content">
    @if(session('success'))
      <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger">❌ {{ session('error') }}</div>
    @endif
    @if($errors->any())
      <div class="alert alert-danger">
        <ul style="margin:0;padding-left:16px;">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @yield('content')
  </main>
</div>

<script>
  // Sidebar toggle mobile
  const sidebarToggle  = document.getElementById('sidebarToggle');
  const sidebar        = document.getElementById('sidebar');
  const sidebarOverlay = document.getElementById('sidebarOverlay');

  sidebarToggle?.addEventListener('click', () => {
    sidebar.classList.toggle('open');
    sidebarOverlay.classList.toggle('show');
  });
  sidebarOverlay?.addEventListener('click', () => {
    sidebar.classList.remove('open');
    sidebarOverlay.classList.remove('show');
  });
</script>
@stack('scripts')
</body>
</html>
