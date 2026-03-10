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

  <style>
    .stok-cell {
    font-weight: 600;
    text-align: center;
}
.stok-a-plus { color: #dc2626; } /* Merah */
.stok-a-minus { color: #b91c1c; } /* Merah tua */
.stok-b-plus { color: #2563eb; } /* Biru */
.stok-b-minus { color: #1e40af; } /* Biru tua */
.stok-ab-plus { color: #ea580c; } /* Oranye */
.stok-ab-minus { color: #c2410c; } /* Oranye tua */
.stok-o-plus { color: #16a34a; } /* Hijau */
.stok-o-minus { color: #166534; } /* Hijau tua */
.total-stok { 
    font-weight: 700; 
    text-align: center;
}
.status-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    text-align: center;
    display: inline-block;
    min-width: 60px;
}
.status-kritis {
    background: #fee2e2;
    color: #dc2626;
}
.status-sedang {
    background: #fef3c7;
    color: #d97706;
}
.status-aman {
    background: #d1fae5;
    color: #059669;
}
.hospital-image {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 8px;
}

    /* Status Badge Styles */
    .status-badge {
      padding: 4px 8px;
      border-radius: 12px;
      font-size: 12px;
      font-weight: 600;
      text-align: center;
      display: inline-block;
      min-width: 60px;
    }
    .status-kritis {
      background: #fee2e2;
      color: #dc2626;
    }
    .status-sedang {
      background: #fef3c7;
      color: #d97706;
    }
    .status-aman {
      background: #d1fae5;
      color: #059669;
    }
    .hospital-image {
      width: 50px;
      height: 50px;
      object-fit: cover;
      border-radius: 8px;
    }
    .no-image {
      width: 50px;
      height: 50px;
      background: #f3f4f6;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
    }
    .stok-cell {
      font-weight: 600;
    }
    .stok-a-plus { color: #dc2626; }
    .stok-a-minus { color: #b91c1c; }
    .stok-b-plus { color: #2563eb; }
    .stok-b-minus { color: #1e40af; }
    .stok-ab-plus { color: #ea580c; }
    .stok-ab-minus { color: #c2410c; }
    .stok-o-plus { color: #16a34a; }
    .stok-o-minus { color: #166534; }
  </style>
</head>
<body>

<!-- Sidebar Toggle untuk Mobile -->
<button class="sidebar-toggle" id="sidebarToggle">☰</button>
<!-- Overlay untuk Mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="container">
  <!-- Sidebar -->
  <aside class="sidebar" id="sidebar">
    <h2 class="logo">🩸<span>Sunblood</span></h2>
    <ul>
      <li onclick="window.location.href='{{ route('admin.dashboard') }}'">      
        <span>Dashboard</span>
      </li>

      <li onclick="window.location.href='{{ route('admin.stok.darah') }}'">
        <span>Stok Darah</span>
      </li>

      <li onclick="window.location.href='{{ route('admin.permintaan.darurat') }}'" >
        <span>Permintaan Darurat</span>
      </li>
      
      <li onclick="window.location.href='{{ route('admin.feedback') }}'" >
        <span>Feedback</span>
      </li>

    </ul>
  </aside>


  <!-- Main Content -->
  <main class="content">
    <h1>Manajemen Stok Darah</h1>

    <!-- Alert Container -->
    <div id="alertContainer"></div>

    <!-- Form Section -->
    <div class="form-container">
      <h2>
        <span id="formTitle">Tambah Data Stok Darah</span>
      </h2>
      
      <form id="formStok" enctype="multipart/form-data">
        <input type="hidden" id="editId" value="">

        <div class="form-grid">
          <div class="form-group">
            <label> Nama Rumah Sakit</label>
            <input type="text" id="namaRS" placeholder="Contoh: RSUD Dr. Soetomo" required>
          </div>

          <div class="form-group">
            <label> Foto Rumah Sakit</label>
            <input type="file" id="fotoRS" accept="image/*">
            <small style="color: #6b7280; margin-top: 4px;">Format: JPG, PNG (Max 2MB)</small>
            <!-- Preview foto lama saat edit -->
            <div id="fotoPreview" style="margin-top: 8px; display: none;">
              <img src="" alt="Preview" style="max-width: 100px; max-height: 100px; border-radius: 8px;">
            </div>
          </div>
        </div>

        <div class="stok-grid">
          <div class="stok-item">
            <label>Golongan A+</label>
            <input type="number" id="stokA+" min="0" placeholder="0" required>
          </div>

        <div class="stok-grid">
          <div class="stok-item">
            <label>Golongan A-</label>
            <input type="number" id="stokA-" min="0" placeholder="0" required>
          </div>


          <div class="stok-item">
            <label>Golongan B+</label>
            <input type="number" id="stokB+" min="0" placeholder="0" required>
          </div>

          <div class="stok-item">
            <label>Golongan B-</label>
            <input type="number" id="stokB-" min="0" placeholder="0" required>
          </div>


          <div class="stok-item">
            <label>Golongan AB+</label>
            <input type="number" id="stokAB+" min="0" placeholder="0" required>
          </div>

          <div class="stok-item">
            <label>Golongan AB-</label>
            <input type="number" id="stokAB-" min="0" placeholder="0" required>
          </div>


          <div class="stok-item">
            <label>Golongan O+</label>
            <input type="number" id="stokO+" min="0" placeholder="0" required>
          </div>
        </div>

          <div class="stok-item">
            <label>Golongan O-</label>
            <input type="number" id="stokO-" min="0" placeholder="0" required>
          </div>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-primary" id="submitBtn">
            Simpan Data
          </button>
          <button type="button" class="btn btn-secondary" id="resetBtn" onclick="resetForm()">
            Reset
          </button>
        </div>
      </form>
    </div>

    <!-- Table Section -->
    <div class="table-container">
      <div class="table-header">
        <h2>
           Daftar Stok Darah Rumah Sakit
        </h2>
        <div style="display: flex; gap: 8px;">
          <button class="btn-refresh" onclick="loadData()" title="Refresh Data">
            
          </button>
        </div>
      </div>

      <div style="overflow-x: auto;">
        <table>
          <thead>
            <tr>
              <th>Foto</th>
              <th>Nama RS</th>
              <th>A+</th>
              <th>A-</th>
              <th>B+</th>
              <th>B-</th>
              <th>AB+</th>
              <th>AB-</th>
              <th>O+</th>
              <th>O-</th>
              <th>Total</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody id="tableStok">
            <tr>
              <td colspan="13" class="empty-state">
                <div style="text-align: center; padding: 40px;">
                  <div style="font-size: 48px; margin-bottom: 16px;">⏳</div>
                  <p>Memuat data...</p>
                </div>
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
      const errorMsg = error.response?.data?.message || 'Gagal menyimpan data';
      const errors = error.response?.data?.errors;
      
      if (errors) {
        // Tampilkan error validasi
        const errorList = Object.values(errors).flat().join(', ');
        showAlert('error', `${errorMsg}: ${errorList}`);
      } else {
        showAlert('error', errorMsg);
      }
    }
  });

  // Fungsi load data
  async function loadData() {
    try {
      const response = await axios.get(API_URL);
      // Akses data dari response.data.data (sesuai struktur response API)
      const data = response.data.data || response.data;
      renderTable(data);
    } catch (error) {
      console.error('Error loading data:', error);
      showAlert('error', 'Gagal memuat data');
      
      // Tampilkan empty state
      document.getElementById("tableStok").innerHTML = `
        <tr>
          <td colspan="13" class="empty-state">
            <div style="text-align: center; padding: 40px;">
              <div style="font-size: 48px; margin-bottom: 16px;">❌</div>
              <p>Gagal memuat data. Silakan refresh halaman.</p>
            </div>
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
                <td colspan="13" class="empty-state">
                    <div style="text-align: center; padding: 40px;">
                        <div style="font-size: 48px; margin-bottom: 16px;">📭</div>
                        <p>Belum ada data stok darah</p>
                    </div>
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = "";

    data.forEach((item) => {
        // Hitung total (gunakan field yang sudah disediakan)
        const total = item.total || 0;
        
        // Tentukan status
        let status = item.status || "Aman";
        let statusClass = "status-aman";
        
        if (status === "Kritis" || status.toLowerCase() === "kritis") {
            statusClass = "status-kritis";
        } else if (status === "Sedang" || status.toLowerCase() === "sedang") {
            statusClass = "status-sedang";
        }

        // Foto URL
        const fotoUrl = item.foto || null;

        // DEBUG: console log untuk cek data
        console.log('Data item:', item);

        tbody.innerHTML += `
            <tr>
                <td>
                    ${fotoUrl ? 
                        `<img src="${fotoUrl}" alt="${item.nama_rs}" class="hospital-image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">` : 
                        `<div class="no-image" style="width: 50px; height: 50px; background: #f3f4f6; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 24px;">🏥</div>`
                    }
                </td>
                <td><strong>${item.nama_rs || '-'}</strong></td>
                <td class="stok-cell stok-a-plus"><strong>${item.stok_a_plus || 0}</strong></td>
                <td class="stok-cell stok-a-minus"><strong>${item.stok_a_minus || 0}</strong></td>
                <td class="stok-cell stok-b-plus"><strong>${item.stok_b_plus || 0}</strong></td>
                <td class="stok-cell stok-b-minus"><strong>${item.stok_b_minus || 0}</strong></td>
                <td class="stok-cell stok-ab-plus"><strong>${item.stok_ab_plus || 0}</strong></td>
                <td class="stok-cell stok-ab-minus"><strong>${item.stok_ab_minus || 0}</strong></td>
                <td class="stok-cell stok-o-plus"><strong>${item.stok_o_plus || 0}</strong></td>
                <td class="stok-cell stok-o-minus"><strong>${item.stok_o_minus || 0}</strong></td>
                <td class="total-stok"><strong>${total}</strong></td>
                <td><span class="status-badge ${statusClass}">${status}</span></td>
                <td>
                    <div class="action-buttons" style="display: flex; gap: 4px;">
                        <button class="btn-icon edit" onclick="editData(${item.id})" title="Edit" style="padding: 4px 8px; border: none; border-radius: 4px; cursor: pointer;">✏️</button>
                        <button class="btn-icon delete" onclick="hapusData(${item.id})" title="Hapus" style="padding: 4px 8px; border: none; border-radius: 4px; cursor: pointer;">🗑️</button>
                    </div>
                </td>
            </tr>
        `;
    });
}
      // Hitung total (gunakan field yang sudah disediakan oleh controller)
      const total = item.total || 
                   (item.stok_a_plus + item.stok_a_minus + 
                    item.stok_b_plus + item.stok_b_minus + 
                    item.stok_ab_plus + item.stok_ab_minus + 
                    item.stok_o_plus + item.stok_o_minus);
      
      // Tentukan status
      let status = item.status || "Aman";
      let statusClass = "status-aman";
      
      if (status === "Kritis") {
        statusClass = "status-kritis";
      } else if (status === "Sedang") {
        statusClass = "status-sedang";
      }

      // Foto URL
      const fotoUrl = item.foto || null;

      tbody.innerHTML += `
        <tr>
          <td>
            ${fotoUrl ? 
              `<img src="${fotoUrl}" alt="${item.nama_rs}" class="hospital-image">` : 
              `<div class="no-image">🏥</div>`
            }
          </td>
          <td><strong>${item.nama_rs}</strong></td>
          <td class="stok-cell stok-a-plus">${item.stok_a_plus || 0}</td>
          <td class="stok-cell stok-a-minus">${item.stok_a_minus || 0}</td>
          <td class="stok-cell stok-b-plus">${item.stok_b_plus || 0}</td>
          <td class="stok-cell stok-b-minus">${item.stok_b_minus || 0}</td>
          <td class="stok-cell stok-ab-plus">${item.stok_ab_plus || 0}</td>
          <td class="stok-cell stok-ab-minus">${item.stok_ab_minus || 0}</td>
          <td class="stok-cell stok-o-plus">${item.stok_o_plus || 0}</td>
          <td class="stok-cell stok-o-minus">${item.stok_o_minus || 0}</td>
          <td class="total-stok"><strong>${total}</strong></td>
          <td><span class="status-badge ${statusClass}">${status}</span></td>
          <td>
            <div class="action-buttons">
              <button class="btn-icon edit" onclick="editData(${item.id})" title="Edit">
                ✏️
              </button>
              <button class="btn-icon delete" onclick="hapusData(${item.id})" title="Hapus">
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
      const result = response.data;
      const item = result.data || result; // Sesuaikan dengan struktur response

      // Isi form dengan data
      document.getElementById("editId").value = item.id;
      document.getElementById("namaRS").value = item.nama_rs;
      
      // Hitung total per golongan dari detail + dan -
      document.getElementById("stokA").value = item.total_a || (item.stok_a_plus + item.stok_a_minus);
      document.getElementById("stokB").value = item.total_b || (item.stok_b_plus + item.stok_b_minus);
      document.getElementById("stokAB").value = item.total_ab || (item.stok_ab_plus + item.stok_ab_minus);
      document.getElementById("stokO").value = item.total_o || (item.stok_o_plus + item.stok_o_minus);
      
      // Tampilkan preview foto jika ada
      if (item.foto) {
        const preview = document.getElementById('fotoPreview');
        const previewImg = preview.querySelector('img');
        previewImg.src = item.foto;
        preview.style.display = 'block';
      }
      
      // Update form title dan button
      document.getElementById("formTitle").innerText = "Edit Data Stok Darah";
      document.getElementById("submitBtn").innerHTML = '✏️ Update Data';

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
    document.getElementById("fotoPreview").style.display = 'none';
    document.getElementById("formTitle").innerText = "Tambah Data Stok Darah";
    document.getElementById("submitBtn").innerHTML = '💾 Simpan Data';
  }

  // Fungsi show alert
  function showAlert(type, message) {
    const alertContainer = document.getElementById("alertContainer");
    const alertClass = type === 'success' ? 'alert-success' : 'alert-error';
    const icon = type === 'success' ? '✅' : '❌';

    alertContainer.innerHTML = `
      <div class="alert ${alertClass}" style="
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
        ${type === 'success' ? 'background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0;' : 
                                'background: #fee2e2; color: #991b1b; border: 1px solid #fecaca;'}
      ">
        <span style="font-size: 20px;">${icon}</span>
        <span>${message}</span>
      </div>
    `;

    // Auto hide setelah 3 detik
    setTimeout(() => {
      alertContainer.innerHTML = '';
    }, 3000);
  }

  // Fungsi untuk format angka (opsional)
  function formatNumber(num) {
    return new Intl.NumberFormat('id-ID').format(num);
  }

  // Debug: Lihat response API
async function testAPI() {
    try {
        const response = await axios.get(API_URL);
        console.log('Full API Response:', response);
        console.log('Data yang diterima:', response.data);
        console.log('Data pertama:', response.data.data?.[0] || response.data?.[0]);
    } catch (error) {
        console.error('Error test API:', error);
    }
}

// Panggil test saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    loadData();
    testAPI(); // Untuk debugging
});

</script>

</body>
</html>