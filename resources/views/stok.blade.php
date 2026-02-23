<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Stok Darah - Admin Sunblood</title>
  
  <!-- CSS -->
  <link rel="stylesheet" href="{{ asset('css/stok.css') }}">
  
  <!-- Axios -->
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>

<!-- Sidebar Toggle untuk Mobile -->
<button class="sidebar-toggle" id="sidebarToggle">☰</button>
<!-- Overlay untuk Mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="container">

<!-- Sidebar di stok.blade.php -->
<aside class="sidebar" id="sidebar">
  <h2 class="logo">🩸<span>Sunblood</span></h2>
  <ul>
    <li onclick="window.location.href='{{ route('dashboard') }}'">
      <i>📊</i>
      <span>Dashboard</span>
    </li>
    <li class="active">
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
    <h1>
      <i>🩸</i>
      Manajemen Stok Darah
    </h1>

    <!-- Alert Container -->
    <div id="alertContainer"></div>

    <!-- Form Section -->
    <div class="form-container">
      <h2>
        <i>📝</i>
        <span id="formTitle">Tambah Data Stok Darah</span>
      </h2>
      
      <form id="formStok" enctype="multipart/form-data">
        <input type="hidden" id="editIndex" value="">
        <input type="hidden" id="editId" value="">

        <div class="form-grid">
          <div class="form-group">
            <label><i>🏥</i> Nama Rumah Sakit</label>
            <input type="text" id="namaRS" placeholder="Contoh: RSUD Dr. Soetomo" required>
          </div>

          <div class="form-group">
            <label><i>📸</i> Foto Rumah Sakit</label>
            <input type="file" id="fotoRS" accept="image/*">
            <small style="color: #6b7280; margin-top: 4px;">Format: JPG, PNG (Max 2MB)</small>
          </div>
        </div>

        <div class="stok-grid">
          <div class="stok-item">
            <label><span>🅰️</span> Golongan A</label>
            <input type="number" id="stokA" min="0" placeholder="0" required>
          </div>

          <div class="stok-item">
            <label><span>🅱️</span> Golongan B</label>
            <input type="number" id="stokB" min="0" placeholder="0" required>
          </div>

          <div class="stok-item">
            <label><span>🆎</span> Golongan AB</label>
            <input type="number" id="stokAB" min="0" placeholder="0" required>
          </div>

          <div class="stok-item">
            <label><span>🅾️</span> Golongan O</label>
            <input type="number" id="stokO" min="0" placeholder="0" required>
          </div>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-primary" id="submitBtn">
            <i>💾</i> Simpan Data
          </button>
          <button type="button" class="btn btn-secondary" id="resetBtn" onclick="resetForm()">
            <i>🔄</i> Reset
          </button>
        </div>
      </form>
    </div>

    <!-- Table Section -->
    <div class="table-container">
      <div class="table-header">
        <h2>
          <i>📋</i>
          Daftar Stok Darah Rumah Sakit
        </h2>
        <button class="btn-refresh" onclick="loadData()" title="Refresh Data">
          🔄
        </button>
      </div>

      <div style="overflow-x: auto;">
        <table>
          <thead>
            <tr>
              <th>Foto</th>
              <th>Nama RS</th>
              <th>A</th>
              <th>B</th>
              <th>AB</th>
              <th>O</th>
              <th>Total</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody id="tableStok">
            <tr>
              <td colspan="9" class="empty-state">
                <i>📭</i>
                <p>Memuat data...</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </main>
</div>

<script>
  // Konfigurasi Axios
  axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
  axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // Base URL API
  const API_URL = '/api/stok-darah';

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

  // Load data saat halaman dimuat
  document.addEventListener('DOMContentLoaded', loadData);

  // Handle form submit
  document.getElementById("formStok").addEventListener("submit", async function(e) {
    e.preventDefault();
    
    const id = document.getElementById("editId").value;
    const fileInput = document.getElementById("fotoRS");
    const formData = new FormData();

    // Append data
    formData.append('nama_rs', document.getElementById("namaRS").value);
    formData.append('stok_a', document.getElementById("stokA").value);
    formData.append('stok_b', document.getElementById("stokB").value);
    formData.append('stok_ab', document.getElementById("stokAB").value);
    formData.append('stok_o', document.getElementById("stokO").value);
    
    if (fileInput.files[0]) {
      formData.append('foto', fileInput.files[0]);
    }

    try {
      let response;
      
      if (id) {
        // Update data
        formData.append('_method', 'PUT');
        response = await axios.post(`${API_URL}/${id}`, formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        });
        showAlert('success', 'Data berhasil diperbarui!');
      } else {
        // Create data
        response = await axios.post(API_URL, formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        });
        showAlert('success', 'Data berhasil ditambahkan!');
      }

      // Reset form dan reload data
      resetForm();
      loadData();

    } catch (error) {
      console.error('Error:', error);
      showAlert('error', error.response?.data?.message || 'Gagal menyimpan data');
    }
  });

  // Fungsi load data
  async function loadData() {
    try {
      const response = await axios.get(API_URL);
      renderTable(response.data);
    } catch (error) {
      console.error('Error loading data:', error);
      showAlert('error', 'Gagal memuat data');
      
      // Tampilkan empty state
      document.getElementById("tableStok").innerHTML = `
        <tr>
          <td colspan="9" class="empty-state">
            <i>❌</i>
            <p>Gagal memuat data. Silakan refresh halaman.</p>
          </td>
        </tr>
      `;
    }
  }

  // Fungsi render table
  function renderTable(data) {
    const tbody = document.getElementById("tableStok");
    
    if (!data || data.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td colspan="9" class="empty-state">
            <i>📭</i>
            <p>Belum ada data stok darah</p>
          </td>
        </tr>
      `;
      return;
    }

    tbody.innerHTML = "";

    data.forEach((rs, index) => {
      const total = rs.stok_a + rs.stok_b + rs.stok_ab + rs.stok_o;
      
      // Tentukan status berdasarkan total
      let status = "Aman";
      let statusClass = "aman";
      
      if (total < 30) {
        status = "Kritis";
        statusClass = "kritis";
      } else if (total < 70) {
        status = "Sedang";
        statusClass = "sedang";
      }

      // Foto URL
      const fotoUrl = rs.foto ? `/storage/${rs.foto}` : null;

      tbody.innerHTML += `
        <tr>
          <td>
            ${fotoUrl ? 
              `<img src="${fotoUrl}" alt="${rs.nama_rs}" class="hospital-image">` : 
              `<div class="no-image">🏥</div>`
            }
          </td>
          <td><strong>${rs.nama_rs}</strong></td>
          <td><strong style="color: #ef4444;">${rs.stok_a}</strong></td>
          <td><strong style="color: #3b82f6;">${rs.stok_b}</strong></td>
          <td><strong style="color: #f97316;">${rs.stok_ab}</strong></td>
          <td><strong style="color: #10b981;">${rs.stok_o}</strong></td>
          <td class="total-stok">${total}</td>
          <td><span class="status ${statusClass}">${status}</span></td>
          <td>
            <div class="action-buttons">
              <button class="btn-icon edit" onclick="editData(${rs.id})" title="Edit">
                ✏️
              </button>
              <button class="btn-icon delete" onclick="hapusData(${rs.id})" title="Hapus">
                🗑️
              </button>
            </div>
          </td>
        </tr>
      `;
    });
  }

  // Fungsi edit data
  async function editData(id) {
    try {
      const response = await axios.get(`${API_URL}/${id}`);
      const rs = response.data;

      // Isi form dengan data
      document.getElementById("editId").value = rs.id;
      document.getElementById("namaRS").value = rs.nama_rs;
      document.getElementById("stokA").value = rs.stok_a;
      document.getElementById("stokB").value = rs.stok_b;
      document.getElementById("stokAB").value = rs.stok_ab;
      document.getElementById("stokO").value = rs.stok_o;
      
      // Update form title
      document.getElementById("formTitle").innerText = "Edit Data Stok Darah";
      document.getElementById("submitBtn").innerHTML = '<i>✏️</i> Update Data';

      // Scroll ke form
      document.querySelector('.form-container').scrollIntoView({ behavior: 'smooth' });

    } catch (error) {
      console.error('Error:', error);
      showAlert('error', 'Gagal memuat data untuk diedit');
    }
  }

  // Fungsi hapus data
  async function hapusData(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
      return;
    }

    try {
      await axios.delete(`${API_URL}/${id}`);
      showAlert('success', 'Data berhasil dihapus!');
      loadData();
    } catch (error) {
      console.error('Error:', error);
      showAlert('error', 'Gagal menghapus data');
    }
  }

  // Fungsi reset form
  function resetForm() {
    document.getElementById("formStok").reset();
    document.getElementById("editId").value = "";
    document.getElementById("editIndex").value = "";
    document.getElementById("formTitle").innerText = "Tambah Data Stok Darah";
    document.getElementById("submitBtn").innerHTML = '<i>💾</i> Simpan Data';
  }

  // Fungsi show alert
  function showAlert(type, message) {
    const alertContainer = document.getElementById("alertContainer");
    const alertClass = type === 'success' ? 'alert-success' : 'alert-error';
    const icon = type === 'success' ? '✅' : '❌';

    alertContainer.innerHTML = `
      <div class="alert ${alertClass}">
        <i>${icon}</i>
        <span>${message}</span>
      </div>
    `;

    // Auto hide setelah 3 detik
    setTimeout(() => {
      alertContainer.innerHTML = '';
    }, 3000);
  }

  // Fungsi pencarian (optional)
  function searchData() {
    // Implementasi pencarian jika diperlukan
  }
</script>

</body>
</html>