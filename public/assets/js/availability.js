// availability.js — Terhubung ke API Laravel /api/stok-darah

let currentSlide = 0;
let slidesData = [];

const API_URL = '/api/stok-darah';

function loadSlides() {
  fetch(API_URL)
    .then(res => res.json())
    .then(response => {
      if (!response.success || !response.data.length) {
        document.getElementById('slides-container').innerHTML =
          '<p style="text-align:center;padding:20px;">Data stok darah tidak tersedia.</p>';
        return;
      }

      slidesData = response.data;
      renderSlides();
      showSlide(0);
      updateNavButtons();

      // Update last updated
      const now = new Date();
      document.getElementById('lastUpdate').textContent =
        'Terakhir diperbarui ' + now.toLocaleDateString('id-ID', {
          day: 'numeric', month: 'long', year: 'numeric',
          hour: '2-digit', minute: '2-digit'
        });
    })
    .catch(() => {
      document.getElementById('slides-container').innerHTML =
        '<p style="text-align:center;padding:20px;color:red;">Gagal memuat data stok darah. Pastikan server berjalan.</p>';
    });
}

function renderSlides() {
  const $container = document.getElementById('slides-container');
  $container.innerHTML = '';

  slidesData.forEach((rs, index) => {
    // Map kolom database ke tampilan
    const stokList = [
      { gol: 'A+',  jumlah: rs.stok_a_plus },
      { gol: 'A-',  jumlah: rs.stok_a_minus },
      { gol: 'B+',  jumlah: rs.stok_b_plus },
      { gol: 'B-',  jumlah: rs.stok_b_minus },
      { gol: 'AB+', jumlah: rs.stok_ab_plus },
      { gol: 'AB-', jumlah: rs.stok_ab_minus },
      { gol: 'O+',  jumlah: rs.stok_o_plus },
      { gol: 'O-',  jumlah: rs.stok_o_minus },
    ];

    const stocksHtml = stokList.map(s => {
      const statusClass = s.jumlah === 0 ? 'kritis' : s.jumlah < 10 ? 'sedang' : 'aman';
      return `<div class="stock-card ${statusClass}">
        <span class="gol-label">${s.gol}</span>
        <span class="jumlah">${s.jumlah}</span>
        <small>kantong</small>
      </div>`;
    }).join('');

    // Gambar rumah sakit (pakai foto dari DB atau fallback)
    const gambar = rs.foto
      ? `/storage/${rs.foto}`
      : `https://raw.githubusercontent.com/Shsndne/sunbloodBE/refs/heads/master/imgs/kariadi.jpg`;

    const slide = `
      <div class="slide ${index === 0 ? 'active' : ''}" id="slide-${index}">
        <h2>${rs.nama_rs}</h2>
        <div class="hospital">
          <div class="image-container">
            <img src="${gambar}" alt="${rs.nama_rs}" onerror="this.src='https://via.placeholder.com/300x200?text=RS'">
          </div>
          <div class="stocks">
            ${stocksHtml}
          </div>
        </div>
        <div class="total-stok">
          Total Stok: <strong>${rs.total_stok ?? stokList.reduce((a, b) => a + b.jumlah, 0)} kantong</strong>
        </div>
      </div>
    `;
    $container.insertAdjacentHTML('beforeend', slide);
  });
}

function showSlide(index) {
  if (index >= 0 && index < slidesData.length) {
    currentSlide = index;
    document.querySelectorAll('.slide').forEach((el, i) => {
      el.classList.toggle('active', i === index);
    });
    updateNavButtons();
  }
}

function updateNavButtons() {
  const prevButton = document.querySelector(".carousel-btn[onclick='prevSlide()']");
  const nextButton = document.querySelector(".carousel-btn[onclick='nextSlide()']");

  if (prevButton) {
    prevButton.disabled = currentSlide === 0;
    prevButton.style.opacity = currentSlide === 0 ? '0.7' : '1';
    prevButton.style.cursor = currentSlide === 0 ? 'not-allowed' : 'pointer';
  }

  if (nextButton) {
    nextButton.disabled = currentSlide === slidesData.length - 1;
    nextButton.style.opacity = currentSlide === slidesData.length - 1 ? '0.7' : '1';
    nextButton.style.cursor = currentSlide === slidesData.length - 1 ? 'not-allowed' : 'pointer';
  }
}

function nextSlide() {
  if (currentSlide < slidesData.length - 1) {
    showSlide(currentSlide + 1);
  }
}

function prevSlide() {
  if (currentSlide > 0) {
    showSlide(currentSlide - 1);
  }
}

document.addEventListener('DOMContentLoaded', function () {
  loadSlides();
});