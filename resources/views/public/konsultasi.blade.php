<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <title>Konsultasi - SunBlood</title>
    <link rel="stylesheet" href="/assets/css/consultation.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
    <link rel="icon" href="/assets/imgs/logo.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
  </head>
  <body>
    <nav class="navbar">
      <div class="nav-content">
        <a href="{{ route('home') }}" class="logo">SunBlood</a>
        <div class="menu-toggle" id="menu-toggle">
          <i class="bi bi-list" id="menu-icon"></i>
        </div>
        <ul class="nav-links" id="nav-links">
          <li><a href="{{ route('konsultasi') }}" class="active">Konsultasi</a></li>
          <li><a href="{{ route('stok-darah') }}">Ketersediaan Darah</a></li>
          <li><a href="{{ route('darurat') }}" class="btn">Darurat</a></li>
        </ul>
      </div>
    </nav>

    <main>
      <section id="consultation">
        <aside class="sidebar">
          <h2>Layanan Konsultasi</h2>
          <button class="btn disease-btn" data-disease="darurat">Darurat</button>
          <button class="btn disease-btn" data-disease="kecocokan">Cek Kecocokan Darah</button>
          <button class="btn disease-btn" data-disease="transfusi">Info Transfusi</button>
        </aside>
        <div class="chat-area">
          <div class="chat-display" id="chat-display">
            <p class="placeholder">Pilih opsi keluhan untuk memulai konsultasi...</p>
          </div>
          <div class="chat-input">
            <input type="text" id="user-input" placeholder="Ketik pesan..." />
            <button id="send-btn" class="btn"><i class="bi bi-send"></i></button>
          </div>
        </div>
      </section>
    </main>

    <footer>
      <div class="cta-container">
        <h2 class="cta-title">Butuh Konsultasi? Kami Siap Membantu</h2>
        <p class="cta-text">Jangan tunda masalah kesehatan. Konsultasikan keluhanmu dengan tenaga medis berpengalaman dari SunBlood.</p>
        <div class="cta-buttons">
          <a href="{{ route('feedback.page') }}" class="btn">Hubungi Kami</a>
          <a href="{{ route('konsultasi') }}" class="btn-outline">Konsultasi Sekarang <i class="bi bi-arrow-right-short"></i></a>
        </div>
      </div>
      <div class="footer-nav container">
        <div class="footer-flex">
          <div class="footer-copyright">
            <h2 class="brand-title">SunBlood</h2>
            <p class="brand-desc">Untuk Semarang yang Lebih Sehat.</p>
            <p class="brand-copy">©2026 SunBlood. All Rights Reserved.</p>
          </div>
          <div class="footer-links">
            <a href="{{ route('konsultasi') }}">Konsultasi</a>
            <a href="{{ route('stok-darah') }}">Ketersediaan Darah</a>
            <a href="{{ route('darurat') }}">Emergency Request</a>
          </div>
        </div>
      </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/assets/js/navbar.js"></script>
    <script src="/assets/js/consultation.js"></script>
  </body>
</html>
