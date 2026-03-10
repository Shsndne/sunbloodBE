<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Permintaan Darurat - Admin Sunblood</title>
  
  <!-- CSS -->
  <link rel="stylesheet" href="{{ asset('css/config.css') }}">
  <link rel="stylesheet" href="{{ asset('css/darurat.css') }}">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Axios -->
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    <h1>
      
      Manajemen Permintaan Darurat
    </h1>

    <!-- Alert Container -->
    <div id="alertContainer"></div>

    <!-- Summary Cards -->
    <div class="summary-cards" id="summaryCards">
      <div class="summary-card emergency loading">
        <div class="card-icon">
          <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="card-info">
          <h3>Permintaan Darurat</h3>
          <div class="number" id="totalDarurat">-</div>
        </div>
      </div>
      
      <div class="summary-card pending loading">
        <div class="card-icon">
          <i class="fas fa-clock"></i>
        </div>
        <div class="card-info">
          <h3>Belum Diproses</h3>
          <div class="number" id="totalBelum">-</div>
        </div>
      </div>
      
      <div class="summary-card progress loading">
        <div class="card-icon">
          <i class="fas fa-spinner"></i>
        </div>
        <div class="card-info">
          <h3>Sedang Diproses</h3>
          <div class="number" id="totalDiproses">-</div>
        </div>
      </div>
      
      <div class="summary-card done loading">
        <div class="card-icon">
          <i class="fas fa-check-circle"></i>
        </div>
        <div class="card-info">
          <h3>Terpenuhi</h3>
          <div class="number" id="totalTerpenuhi">-</div>
        </div>
      </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
      <div class="filter-group">
        <select class="filter-select" id="filterStatus">
          <option value="">Semua Status</option>
          <option value="darurat">Darurat</option>
          <option value="normal">Normal</option>
          <option value="terencana">Terencana</option>
        </select>
        
        <select class="filter-select" id="filterPemenuhan">
          <option value="">Semua Pemenuhan</option>
          <option value="belum">Belum Diproses</option>
          <option value="diproses">Sedang Diproses</option>
          <option value="terpenuhi">Terpenuhi</option>
        </select>
        
        <select class="filter-select" id="filterGolongan">
          <option value="">Semua Golongan</option>
          <option value="A+">A+</option>
          <option value="A-">A-</option>
          <option value="B+">B+</option>
          <option value="B-">B-</option>
          <option value="AB+">AB+</option>
          <option value="AB-">AB-</option>
          <option value="O+">O+</option>
          <option value="O-">O-</option>
        </select>
      </div>
      
      <div class="search-box">
        <input type="text" id="searchInput" placeholder="Cari nama pasien/RS...">
        <button id="searchBtn"><i class="fas fa-search"></i></button>
      </div>
      
      <button class="btn-refresh" id="refreshBtn" onclick="loadData()">
        <i class="fas fa-sync-alt"></i>
      </button>
    </div>

    <!-- Table Section -->
    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Pasien</th>
            <th>Gol Darah</th>
            <th>Jumlah</th>
            <th>Rumah Sakit</th>
            <th>Deadline</th>
            <th>Status</th>
            <th>Pemenuhan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody id="tableBody">
          <tr>
            <td colspan="10" class="empty-state">
              <i class="fas fa-spinner fa-spin"></i>
              <p>Memuat data...</p>
            </td>
          </tr>
        </tbody>
      </table>
      
      <!-- Pagination -->
      <div class="pagination" id="pagination">
        <button id="prevPage" disabled><i class="fas fa-chevron-left"></i></button>
        <span class="page-info" id="pageInfo">Halaman 1 dari 1</span>
        <button id="nextPage" disabled><i class="fas fa-chevron-right"></i></button>
      </div>
    </div>
  </main>
</div>

<!-- Modal Detail -->
<div class="modal" id="detailModal">
  <div class="modal-content">
    <div class="modal-header">
      <h3><i class="fas fa-info-circle"></i> Detail Permintaan</h3>
      <button class="modal-close" onclick="closeModal('detailModal')">&times;</button>
    </div>
    <div class="modal-body" id="detailBody">
      <!-- Detail akan diisi via JS -->
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('detailModal')">Tutup</button>
    </div>
  </div>
</div>

<!-- Modal Proses -->
<div class="modal" id="prosesModal">
  <div class="modal-content">
    <div class="modal-header">
      <h3><i class="fas fa-tasks"></i> Proses Permintaan</h3>
      <button class="modal-close" onclick="closeModal('prosesModal')">&times;</button>
    </div>
    <div class="modal-body">
      <form id="prosesForm">
        <input type="hidden" id="prosesId">
        
        <div class="form-group">
          <label>Status Pemenuhan</label>
          <select id="prosesStatus" required>
            <option value="diproses">Sedang Diproses</option>
            <option value="terpenuhi">Terpenuhi</option>
          </select>
        </div>
        
        <div class="form-group">
          <label>Catatan / Keterangan</label>
          <textarea id="prosesCatatan" placeholder="Tambahkan catatan..."></textarea>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('prosesModal')">Batal</button>
      <button class="btn btn-primary" onclick="prosesPermintaan()">
        <i class="fas fa-save"></i> Simpan
      </button>
    </div>
  </div>
</div>

<script>
  // Konfigurasi Axios
  axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
  axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // Base URL API
  const API_URL = '/api/permintaan-darurat';
  
  // State
  let currentPage = 1;
  let lastPage = 1;
  let totalData = 0;
  let filters = {
    status: '',
    pemenuhan: '',
    golongan: '',
    search: ''
  };

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
  document.addEventListener('DOMContentLoaded', function() {
    loadData();
    loadSummary();
    setupEventListeners();
  });

  // Setup Event Listeners
  function setupEventListeners() {
    document.getElementById('filterStatus').addEventListener('change', function(e) {
      filters.status = e.target.value;
      currentPage = 1;
      loadData();
    });
    
    document.getElementById('filterPemenuhan').addEventListener('change', function(e) {
      filters.pemenuhan = e.target.value;
      currentPage = 1;
      loadData();
    });
    
    document.getElementById('filterGolongan').addEventListener('change', function(e) {
      filters.golongan = e.target.value;
      currentPage = 1;
      loadData();
    });
    
    document.getElementById('searchBtn').addEventListener('click', function() {
      filters.search = document.getElementById('searchInput').value;
      currentPage = 1;
      loadData();
    });
    
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        filters.search = e.target.value;
        currentPage = 1;
        loadData();
      }
    });
    
    document.getElementById('prevPage').addEventListener('click', function() {
      if (currentPage > 1) {
        currentPage--;
        loadData();
      }
    });
    
    document.getElementById('nextPage').addEventListener('click', function() {
      if (currentPage < lastPage) {
        currentPage++;
        loadData();
      }
    });
  }

  // Load Summary Data
  async function loadSummary() {
    try {
      const response = await axios.get(`${API_URL}/summary`);
      const data = response.data;
      
      document.getElementById('totalDarurat').textContent = data.darurat || 0;
      document.getElementById('totalBelum').textContent = data.belum || 0;
      document.getElementById('totalDiproses').textContent = data.diproses || 0;
      document.getElementById('totalTerpenuhi').textContent = data.terpenuhi || 0;
      
      // Hapus loading class
      document.querySelectorAll('.summary-card').forEach(card => {
        card.classList.remove('loading');
      });
      
    } catch (error) {
      console.error('Error loading summary:', error);
      showAlert('error', 'Gagal memuat ringkasan data');
    }
  }

  // Load Data dengan Filter dan Pagination
  async function loadData() {
    try {
      // Build query string
      const params = new URLSearchParams({
        page: currentPage,
        status: filters.status,
        pemenuhan: filters.pemenuhan,
        golongan: filters.golongan,
        search: filters.search
      });
      
      const response = await axios.get(`${API_URL}?${params.toString()}`);
      const data = response.data;
      
      renderTable(data.data);
      updatePagination(data);
      
    } catch (error) {
      console.error('Error loading data:', error);
      showAlert('error', 'Gagal memuat data');
      
      document.getElementById('tableBody').innerHTML = `
        <tr>
          <td colspan="10" class="empty-state">
            <i class="fas fa-exclamation-circle"></i>
            <p>Gagal memuat data. Silakan refresh halaman.</p>
            <button class="btn btn-primary" onclick="loadData()">
              <i class="fas fa-sync-alt"></i> Muat Ulang
            </button>
          </td>
        </tr>
      `;
    }
  }

  // Render Table
  function renderTable(data) {
    const tbody = document.getElementById('tableBody');
    
    if (!data || data.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td colspan="10" class="empty-state">
            <i class="fas fa-inbox"></i>
            <p>Tidak ada data permintaan</p>
          </td>
        </tr>
      `;
      return;
    }

    let html = '';
    data.forEach((item, index) => {
      const no = ((currentPage - 1) * 10) + index + 1;
      
      // Format deadline
      const deadline = new Date(item.deadline).toLocaleString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
      
      // Status class
      const statusClass = {
        'DARURAT': 'darurat',
        'NORMAL': 'normal',
        'TERENCANA': 'terencana'
      }[item.status] || '';
      
      const pemenuhanClass = {
        'belum': 'belum',
        'diproses': 'diproses',
        'terpenuhi': 'terpenuhi'
      }[item.status_pemenuhan] || '';
      
      html += `
        <tr>
          <td><strong>${no}</strong></td>
          <td><span class="blood-type">${item.kode || 'BLD-' + item.id}</span></td>
          <td>
            <strong>${item.nama_pasien}</strong><br>
            <small style="color: #64748b;">${item.usia} th, ${item.gender}</small>
          </td>
          <td><span class="blood-type">${item.golongan_darah}</span></td>
          <td><strong>${item.jumlah}</strong> kantong</td>
          <td>
            <strong>${item.nama_rs}</strong><br>
            <small style="color: #64748b;">${item.kontak}</small>
          </td>
          <td>
            <strong>${deadline}</strong><br>
            <small style="color: ${isNearDeadline(item.deadline) ? '#dc2626' : '#64748b'};">
              ${getDeadlineStatus(item.deadline)}
            </small>
          </td>
          <td>
            <span class="status-badge ${statusClass}">${item.status}</span>
          </td>
          <td>
            <span class="pemenuhan-badge ${pemenuhanClass}">
              ${item.status_pemenuhan.toUpperCase()}
            </span>
          </td>
          <td>
            <div class="action-buttons">
              <button class="btn-icon view" onclick="showDetail(${item.id})" title="Lihat Detail">
                <i class="fas fa-eye"></i>
              </button>
              <button class="btn-icon edit" onclick="editData(${item.id})" title="Edit">
                <i class="fas fa-edit"></i>
              </button>
              <button class="btn-icon process" onclick="showProsesModal(${item.id})" title="Proses">
                <i class="fas fa-check-circle"></i>
              </button>
              <button class="btn-icon delete" onclick="hapusData(${item.id})" title="Hapus">
                <i class="fas fa-trash"></i>
              </button>
            </div>
          </td>
        </tr>
      `;
    });
    
    tbody.innerHTML = html;
  }

  // Update Pagination
  function updatePagination(data) {
    currentPage = data.current_page;
    lastPage = data.last_page;
    totalData = data.total;
    
    document.getElementById('pageInfo').textContent = 
      `Halaman ${currentPage} dari ${lastPage} (${totalData} data)`;
    
    document.getElementById('prevPage').disabled = currentPage === 1;
    document.getElementById('nextPage').disabled = currentPage === lastPage;
  }

  // Helper Functions
  function isNearDeadline(deadline) {
    const now = new Date();
    const deadlineDate = new Date(deadline);
    const diffHours = (deadlineDate - now) / (1000 * 60 * 60);
    return diffHours < 2 && diffHours > 0;
  }

  function getDeadlineStatus(deadline) {
    const now = new Date();
    const deadlineDate = new Date(deadline);
    
    if (deadlineDate < now) {
      return 'Terlewat';
    }
    
    const diffHours = (deadlineDate - now) / (1000 * 60 * 60);
    
    if (diffHours < 1) {
      return '< 1 jam lagi';
    } else if (diffHours < 2) {
      return '< 2 jam lagi';
    } else if (diffHours < 24) {
      return Math.round(diffHours) + ' jam lagi';
    } else {
      return Math.round(diffHours / 24) + ' hari lagi';
    }
  }

  // Show Detail
  async function showDetail(id) {
    try {
      const response = await axios.get(`${API_URL}/${id}`);
      const data = response.data;
      
      const deadline = new Date(data.deadline).toLocaleString('id-ID', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
      
      const html = `
        <div class="info-grid">
          <div>
            <div class="info-group">
              <div class="info-label">Data Pasien</div>
              <div class="info-value large">${data.nama_pasien}</div>
              <div class="info-value">${data.usia} tahun, ${data.gender}</div>
              <div class="info-value">Diagnosis: ${data.diagnosis || '-'}</div>
            </div>
            
            <div class="info-group">
              <div class="info-label">Kebutuhan Darah</div>
              <div class="info-value">Golongan: ${data.golongan_darah}</div>
              <div class="info-value">Jumlah: ${data.jumlah} kantong</div>
              <div class="info-value">Dibutuhkan: ${deadline}</div>
              <div class="info-value">Status: <span class="status-badge ${data.status.toLowerCase()}">${data.status}</span></div>
            </div>
          </div>
          
          <div>
            <div class="info-group">
              <div class="info-label">Lokasi & Kontak</div>
              <div class="info-value">RS: ${data.nama_rs}</div>
              <div class="info-value">Alamat: ${data.alamat_rs || '-'}</div>
              <div class="info-value">Kontak: ${data.kontak} (${data.nama_kontak})</div>
            </div>
            
            <div class="info-group">
              <div class="info-label">Status Pemenuhan</div>
              <div class="info-value">
                <span class="pemenuhan-badge ${data.status_pemenuhan}">
                  ${data.status_pemenuhan.toUpperCase()}
                </span>
              </div>
              <div class="info-value">Catatan: ${data.catatan || '-'}</div>
            </div>
            
            <div class="info-group">
              <div class="info-label">Informasi Sistem</div>
              <div class="info-value">Kode: ${data.kode || 'BLD-' + data.id}</div>
              <div class="info-value">Dibuat: ${new Date(data.created_at).toLocaleString('id-ID')}</div>
            </div>
          </div>
        </div>
      `;
      
      document.getElementById('detailBody').innerHTML = html;
      openModal('detailModal');
      
    } catch (error) {
      console.error('Error:', error);
      showAlert('error', 'Gagal memuat detail');
    }
  }

  // Show Proses Modal
  function showProsesModal(id) {
    document.getElementById('prosesId').value = id;
    document.getElementById('prosesStatus').value = 'diproses';
    document.getElementById('prosesCatatan').value = '';
    openModal('prosesModal');
  }

  // Proses Permintaan
  async function prosesPermintaan() {
    const id = document.getElementById('prosesId').value;
    const status = document.getElementById('prosesStatus').value;
    const catatan = document.getElementById('prosesCatatan').value;
    
    try {
      await axios.put(`${API_URL}/${id}/proses`, {
        status_pemenuhan: status,
        catatan: catatan
      });
      
      showAlert('success', 'Status permintaan berhasil diperbarui');
      closeModal('prosesModal');
      loadData();
      loadSummary();
      
    } catch (error) {
      console.error('Error:', error);
      showAlert('error', 'Gagal memperbarui status');
    }
  }

  // Edit Data
  async function editData(id) {
    try {
      const response = await axios.get(`${API_URL}/${id}/edit`);
      const data = response.data;
      
      // Isi form edit (implementasi sesuai kebutuhan)
      // Bisa redirect ke halaman form atau buat modal edit
      
    } catch (error) {
      console.error('Error:', error);
      showAlert('error', 'Gagal memuat data untuk diedit');
    }
  }

  // Hapus Data
  async function hapusData(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus permintaan ini?')) {
      return;
    }
    
    try {
      await axios.delete(`${API_URL}/${id}`);
      showAlert('success', 'Data berhasil dihapus');
      loadData();
      loadSummary();
      
    } catch (error) {
      console.error('Error:', error);
      showAlert('error', 'Gagal menghapus data');
    }
  }

  // Modal Functions
  function openModal(modalId) {
    document.getElementById(modalId).classList.add('active');
  }

  function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
  }

  // Show Alert
  function showAlert(type, message) {
    const alertContainer = document.getElementById('alertContainer');
    const alertClass = type === 'success' ? 'alert-success' : 
                       type === 'warning' ? 'alert-warning' : 'alert-error';
    const icon = type === 'success' ? '✅' : 
                 type === 'warning' ? '⚠️' : '❌';

    alertContainer.innerHTML = `
      <div class="alert ${alertClass}">
        <i>${icon}</i>
        <span>${message}</span>
      </div>
    `;

    setTimeout(() => {
      alertContainer.innerHTML = '';
    }, 3000);
  }
</script>

</body>
</html>