<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Dashboard Admin - Sunblood</title>
  
  <!-- CSS Files -->
  <link rel="stylesheet" href="{{ asset('css/config.css') }}">
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
  
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  
  <!-- Axios untuk HTTP Request -->
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>

<!-- Sidebar Toggle Button untuk Mobile -->
<button class="sidebar-toggle" id="sidebarToggle">☰</button>
<!-- Overlay untuk Mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="container">

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
  <h2 class="logo">🩸<span>Sunblood</span></h2>
  <ul>
    <li class="active">
      <i>📊</i>
      <span>Dashboard</span>
    </li>
    <li onclick="window.location.href='{{ route('stok.darah') }}'">
      <i>🩸</i>
      <span>Stok Darah</span>
    </li>
    <li onclick="window.location.href='{{ route('permintaan.darurat') }}'">
      <i>🚨</i>
      <span>Permintaan Darurat</span>
    </li>
  </ul>
</aside>

  <!-- Main Content -->
  <main class="content">
    <h1>Dashboard</h1>

    <!-- Cards dengan Loading State -->
    <div class="cards" id="dashboardCards">
      <div class="card red loading" id="card-rs">
        <h3>🏥 Total Rumah Sakit</h3>
        <div class="stat-value">
          <p class="card-value">-</p>
          <small>RS</small>
        </div>
      </div>

      <div class="card blue loading" id="card-stok">
        <h3>🩸 Total Stok Darah</h3>
        <div class="stat-value">
          <p class="card-value">-</p>
          <small>Kantong</small>
        </div>
      </div>

      <div class="card orange loading" id="card-darurat">
        <h3>🚨 Permintaan Darurat</h3>
        <div class="stat-value">
          <p class="card-value">-</p>
          <small>aktif</small>
        </div>
      </div>

      <div class="card green loading" id="card-terpenuhi">
        <h3>✅ Permintaan Terpenuhi</h3>
        <div class="stat-value">
          <p class="card-value">-</p>
          <small>bulan ini</small>
        </div>
      </div>
    </div>

    <!-- Charts -->
    <div class="chart-grid">
      <!-- Distribusi Stok -->
      <div class="chart-container">
        <h2>📊 Distribusi Stok Darah</h2>
        <div class="chart-wrapper">
          <canvas id="stokChart"></canvas>
        </div>
      </div>

      <!-- Statistik per Golongan -->
      <div class="chart-container">
        <h2>⏱️ Stok per Golongan</h2>
        <div class="chart-wrapper">
          <canvas id="golonganChart"></canvas>
        </div>
      </div>

      <!-- Tren Permintaan per Bulan -->
      <div class="trend-container">
        <h2>📈 Tren Permintaan per Bulan</h2>
        <div class="chart-wrapper" style="height: 250px;">
          <canvas id="trendChart"></canvas>
        </div>
      </div>
    </div>
  </main>
</div>

<script>
  // Konfigurasi Axios
  axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
  axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // Navigasi halaman
  function goTo(url) {
    window.location.href = url;
  }

  // Sidebar Toggle untuk Mobile
  const sidebar = document.getElementById('sidebar');
  const sidebarToggle = document.getElementById('sidebarToggle');
  const sidebarOverlay = document.getElementById('sidebarOverlay');

  if (sidebarToggle) {
    sidebarToggle.addEventListener('click', () => {
      sidebar.classList.toggle('active');
      sidebarOverlay.classList.toggle('active');
    });
  }

  if (sidebarOverlay) {
    sidebarOverlay.addEventListener('click', () => {
      sidebar.classList.remove('active');
      sidebarOverlay.classList.remove('active');
    });
  }

  // Inisialisasi Chart
  let stokChart, golonganChart, trendChart;

  // Fungsi untuk memuat data dashboard
  async function loadDashboardData() {
    try {
      // Tampilkan loading state
      showLoading();

      // Request ke Laravel API
      const response = await axios.get('/api/dashboard-data');
      const data = response.data;

      // Update cards
      updateCards(data);

      // Update charts
      updateCharts(data);

      // Sembunyikan loading
      hideLoading();

    } catch (error) {
      console.error('Error loading dashboard data:', error);
      showError('Gagal memuat data dashboard');
    }
  }

  // Fungsi update cards
  function updateCards(data) {
    // Total Rumah Sakit
    document.querySelector('#card-rs .card-value').textContent = data.total_rs || 0;
    document.querySelector('#card-rs').classList.remove('loading');

    // Total Stok Darah
    document.querySelector('#card-stok .card-value').textContent = 
      (data.total_stok || 0).toLocaleString('id-ID');
    document.querySelector('#card-stok').classList.remove('loading');

    // Permintaan Darurat Aktif
    document.querySelector('#card-darurat .card-value').textContent = 
      data.permintaan_darurat_aktif || 0;
    document.querySelector('#card-darurat').classList.remove('loading');

    // Permintaan Terpenuhi Bulan Ini
    document.querySelector('#card-terpenuhi .card-value').textContent = 
      data.permintaan_terpenuhi_bulan_ini || 0;
    document.querySelector('#card-terpenuhi').classList.remove('loading');
  }

  // Fungsi update charts
  function updateCharts(data) {
    // Destroy existing charts
    if (stokChart) stokChart.destroy();
    if (golonganChart) golonganChart.destroy();
    if (trendChart) trendChart.destroy();

    // Data distribusi stok dari Laravel
    const distribusi = data.distribusi_stok || { A: 0, B: 0, AB: 0, O: 0 };
    const total = Object.values(distribusi).reduce((a, b) => a + b, 0);

    // Chart Distribusi Stok (Doughnut)
    const ctx1 = document.getElementById("stokChart").getContext("2d");
    stokChart = new Chart(ctx1, {
      type: "doughnut",
      data: {
        labels: ["Golongan A", "Golongan B", "Golongan AB", "Golongan O"],
        datasets: [{
          data: [distribusi.A, distribusi.B, distribusi.AB, distribusi.O],
          backgroundColor: ["#ef4444", "#3b82f6", "#f97316", "#10b981"],
          borderWidth: 0,
          borderRadius: 8,
          spacing: 6
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '65%',
        plugins: {
          legend: {
            position: "bottom",
            labels: {
              padding: 20,
              font: { size: 12, weight: '500' },
              usePointStyle: true,
              pointStyle: 'circle'
            }
          },
          tooltip: {
            callbacks: {
              label: (context) => {
                const value = context.raw;
                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                return `${context.label}: ${value} kantong (${percentage}%)`;
              }
            }
          }
        }
      }
    });

    // Chart Stok per Golongan (Bar)
    const ctx2 = document.getElementById("golonganChart").getContext("2d");
    golonganChart = new Chart(ctx2, {
      type: "bar",
      data: {
        labels: ["A", "B", "AB", "O"],
        datasets: [{
          label: 'Ketersediaan (kantong)',
          data: [distribusi.A, distribusi.B, distribusi.AB, distribusi.O],
          backgroundColor: ["#ef4444", "#3b82f6", "#f97316", "#10b981"],
          borderRadius: 8
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: { display: false }
          }
        }
      }
    });

    // Chart Tren Permintaan (Line)
    if (data.tren_permintaan && data.tren_permintaan.labels) {
      const ctx3 = document.getElementById("trendChart").getContext("2d");
      trendChart = new Chart(ctx3, {
        type: "line",
        data: {
          labels: data.tren_permintaan.labels,
          datasets: [
            {
              label: 'Permintaan Darurat',
              data: data.tren_permintaan.darurat || [],
              borderColor: "#ef4444",
              backgroundColor: "rgba(239, 68, 68, 0.1)",
              tension: 0.3,
              fill: true,
              pointBackgroundColor: "#ef4444",
              pointBorderColor: "white",
              pointBorderWidth: 2,
              pointRadius: 4
            },
            {
              label: 'Permintaan Terpenuhi',
              data: data.tren_permintaan.terpenuhi || [],
              borderColor: "#10b981",
              backgroundColor: "rgba(16, 185, 129, 0.1)",
              tension: 0.3,
              fill: true,
              pointBackgroundColor: "#10b981",
              pointBorderColor: "white",
              pointBorderWidth: 2,
              pointRadius: 4
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: "bottom",
              labels: { usePointStyle: true, pointStyle: 'circle' }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              grid: { color: "#f0f0f0" }
            }
          }
        }
      });
    }
  }

  // Fungsi loading
  function showLoading() {
    document.querySelectorAll('.card').forEach(card => {
      card.classList.add('loading');
    });
  }

  function hideLoading() {
    document.querySelectorAll('.card').forEach(card => {
      card.classList.remove('loading');
    });
  }

  // Fungsi error
  function showError(message) {
    // Hapus loading
    hideLoading();
    
    // Tampilkan pesan error di cards
    document.querySelectorAll('.card-value').forEach(el => {
      el.textContent = '!';
    });
    
    // Buat element error
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.innerHTML = `<i>⚠️</i> ${message}`;
    
    // Tambahkan ke halaman
    document.querySelector('.chart-grid').prepend(errorDiv);
    
    // Hapus setelah 5 detik
    setTimeout(() => {
      errorDiv.remove();
    }, 5000);
  }

  // Auto refresh data setiap 30 detik
  let refreshInterval = setInterval(loadDashboardData, 30000);

  // Load data saat halaman dimuat
  document.addEventListener('DOMContentLoaded', loadDashboardData);

  // Bersihkan interval saat halaman ditutup
  window.addEventListener('beforeunload', () => {
    if (refreshInterval) {
      clearInterval(refreshInterval);
    }
  });
</script>

</body>
</html>