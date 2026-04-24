<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Login Admin - Sunblood</title>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
      min-height: 100vh; display: flex; align-items: center; justify-content: center;
      padding: 20px;
    }
    .login-box {
      background: #fff; border-radius: 16px; padding: 40px; width: 100%; max-width: 400px;
      box-shadow: 0 20px 60px rgba(0,0,0,.3);
    }
    .logo { text-align: center; margin-bottom: 28px; }
    .logo h1 { font-size: 2rem; font-weight: 800; color: #1a1a2e; }
    .logo h1 span { color: #e53e3e; }
    .logo p { color: #64748b; font-size: .85rem; margin-top: 4px; }
    .form-group { margin-bottom: 18px; }
    .form-label { display: block; font-size: .85rem; font-weight: 600; color: #374151; margin-bottom: 6px; }
    .form-control {
      width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;
      font-size: .9rem; color: #374151; transition: border-color .2s;
    }
    .form-control:focus { outline: none; border-color: #e53e3e; box-shadow: 0 0 0 3px rgba(229,62,62,.1); }
    .form-control.is-invalid { border-color: #ef4444; }
    .invalid-feedback { color: #ef4444; font-size: .78rem; margin-top: 4px; display: block; }
    .btn-login {
      width: 100%; padding: 12px; background: #e53e3e; color: #fff; border: none;
      border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background .2s;
    }
    .btn-login:hover { background: #c53030; }
    .alert { padding: 10px 14px; border-radius: 8px; margin-bottom: 16px; font-size: .85rem; }
    .alert-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
    .back-link { text-align: center; margin-top: 16px; }
    .back-link a { color: #64748b; font-size: .85rem; text-decoration: none; }
    .back-link a:hover { color: #e53e3e; }
    .remember-row { display: flex; align-items: center; gap: 8px; margin-bottom: 18px; }
    .remember-row input { width: 16px; height: 16px; accent-color: #e53e3e; }
    .remember-row label { font-size: .85rem; color: #64748b; cursor: pointer; }
  </style>
</head>
<body>
  <div class="login-box">
    <div class="logo">
      <h1>🩸 <span>Sun</span>blood</h1>
      <p>Admin Panel — Akses Terbatas</p>
    </div>

    @if(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('status'))
      <div class="alert" style="background:#d1fae5;color:#065f46;border:1px solid #6ee7b7;">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
      @csrf

      <div class="form-group">
        <label for="email" class="form-label">Email Admin</label>
        <input id="email" type="email" name="email"
               class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email') }}" required autofocus autocomplete="username"
               placeholder="admin@sunblood.id">
        @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
      </div>

      <div class="form-group">
        <label for="password" class="form-label">Password</label>
        <input id="password" type="password" name="password"
               class="form-control @error('password') is-invalid @enderror"
               required autocomplete="current-password" placeholder="••••••••">
        @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
      </div>

      <div class="remember-row">
        <input type="checkbox" id="remember_me" name="remember">
        <label for="remember_me">Ingat saya</label>
      </div>

      <button type="submit" class="btn-login">Masuk ke Panel Admin</button>
    </form>

    <div class="back-link">
      <a href="{{ route('home') }}">← Kembali ke Website</a>
    </div>
  </div>
</body>
</html>
