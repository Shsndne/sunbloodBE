// emergency.js — Terhubung ke API Laravel /api/permintaan-darurat

document.addEventListener('DOMContentLoaded', function () {

  // ==================== ELEMEN DOM ====================
  const form             = document.getElementById('bloodForm');
  const steps            = document.querySelectorAll('.form-step');
  const progressSteps    = document.querySelectorAll('.step');
  const progressFill     = document.getElementById('progressFill');
  const urgencyDisplay   = document.getElementById('urgencyDisplay');
  const getLocationBtn   = document.getElementById('getLocation');
  const emergencyCallBtn = document.getElementById('emergencyCall');
  const submitBtn        = document.getElementById('submitBtn');
  const resultDiv        = document.getElementById('result');

  const namaInput       = document.getElementById('nama');
  const usiaInput       = document.getElementById('usia');
  const genderInputs    = document.querySelectorAll('input[name="gender"]');
  const diagnosisInput  = document.getElementById('diagnosis');
  const goldarSelect    = document.getElementById('goldar');
  const jumlahInput     = document.getElementById('jumlah');
  const deadlineInput   = document.getElementById('deadline');
  const statusInputs    = document.querySelectorAll('input[name="status"]');
  const rsInput         = document.getElementById('rs');
  const alamatInput     = document.getElementById('alamat');
  const kontakInput     = document.getElementById('kontak');
  const namaKontakInput = document.getElementById('namaKontak');
  const confirmCheckbox = document.getElementById('confirmData');

  let currentStep = 1;
  const totalSteps = 4;

  // ==================== INISIALISASI ====================
  updateProgress();
  updateUrgencyBadge();
  setDefaultDeadline();

  // ==================== FUNGSI UTILITY ====================

  function setDefaultDeadline() {
    if (!deadlineInput.value) {
      const tomorrow = new Date();
      tomorrow.setDate(tomorrow.getDate() + 1);
      tomorrow.setHours(10, 0, 0, 0);
      const pad = n => String(n).padStart(2, '0');
      deadlineInput.value = `${tomorrow.getFullYear()}-${pad(tomorrow.getMonth()+1)}-${pad(tomorrow.getDate())}T${pad(tomorrow.getHours())}:${pad(tomorrow.getMinutes())}`;
    }
  }

  function updateProgress() {
    progressSteps.forEach(step => {
      step.classList.toggle('active', parseInt(step.dataset.step) === currentStep);
    });
    progressFill.style.width = `${(currentStep / totalSteps) * 100}%`;
  }

  function showStep(stepNumber) {
    steps.forEach(step => step.classList.remove('active'));
    const activeStep = document.getElementById(`step${stepNumber}`);
    if (activeStep) activeStep.classList.add('active');
    currentStep = stepNumber;
    updateProgress();
    if (currentStep === 4) updateSummary();
  }

  function updateUrgencyBadge() {
    const selected = document.querySelector('input[name="status"]:checked');
    urgencyDisplay.className = 'urgency-badge';
    if (selected) {
      const val = selected.value;
      urgencyDisplay.innerHTML = `<i class="fas fa-exclamation-circle"></i> <span>Status: ${val}</span>`;
      if (val === 'DARURAT')   urgencyDisplay.classList.add('badge-emergency');
      else if (val === 'NORMAL') urgencyDisplay.classList.add('badge-normal');
      else                       urgencyDisplay.classList.add('badge-planned');
    } else {
      urgencyDisplay.innerHTML = '<i class="fas fa-exclamation-circle"></i> <span>Silakan isi formulir</span>';
    }
  }

  function updateSummary() {
    document.getElementById('summaryNama').textContent      = namaInput.value || '-';
    document.getElementById('summaryUsia').textContent      = usiaInput.value || '-';
    document.getElementById('summaryDiagnosis').textContent = diagnosisInput.value || '-';
    document.getElementById('summaryGoldar').textContent    = goldarSelect.value || '-';
    document.getElementById('summaryJumlah').textContent    = jumlahInput.value || '0';
    document.getElementById('summaryRS').textContent        = rsInput.value || '-';
    document.getElementById('summaryAlamat').textContent    = alamatInput.value || '-';
    document.getElementById('summaryKontak').textContent    = kontakInput.value || '-';
    document.getElementById('summaryNamaKontak').textContent = namaKontakInput.value || '-';

    let gender = '-';
    genderInputs.forEach(i => { if (i.checked) gender = i.value; });
    document.getElementById('summaryGender').textContent = gender;

    if (deadlineInput.value) {
      document.getElementById('summaryDeadline').textContent =
        new Date(deadlineInput.value).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' });
    } else {
      document.getElementById('summaryDeadline').textContent = '-';
    }

    let status = '-';
    statusInputs.forEach(i => { if (i.checked) status = i.value; });
    document.getElementById('summaryStatus').textContent = status;
  }

  function validateStep(stepNumber) {
    switch (stepNumber) {
      case 1:
        if (!namaInput.value.trim()) { alert('Nama pasien harus diisi'); namaInput.focus(); return false; }
        return true;
      case 2:
        if (!goldarSelect.value) { alert('Pilih golongan darah'); goldarSelect.focus(); return false; }
        if (!deadlineInput.value) { alert('Tentukan waktu dibutuhkan'); deadlineInput.focus(); return false; }
        if (!document.querySelector('input[name="status"]:checked')) { alert('Pilih tingkat urgensi'); return false; }
        return true;
      case 3:
        if (!rsInput.value.trim()) { alert('Nama rumah sakit harus diisi'); rsInput.focus(); return false; }
        if (!kontakInput.value.trim()) { alert('Nomor kontak darurat harus diisi'); kontakInput.focus(); return false; }
        if (!/^[0-9]{10,13}$/.test(kontakInput.value.replace(/\D/g, ''))) {
          alert('Nomor kontak tidak valid (10-13 digit)'); kontakInput.focus(); return false;
        }
        return true;
      default:
        return true;
    }
  }

  // ==================== EVENT HANDLERS ====================

  // Tombol navigasi
  document.querySelectorAll('.btn-next').forEach(btn => {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      const nextStepNum = parseInt(this.dataset.next.replace('step', ''));
      if (validateStep(currentStep)) showStep(nextStepNum);
    });
  });

  document.querySelectorAll('.btn-prev').forEach(btn => {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      showStep(parseInt(this.dataset.prev.replace('step', '')));
    });
  });

  // Tombol qty
  document.querySelectorAll('.qty-btn').forEach(btn => {
    btn.addEventListener('click', function () {
      let val = parseInt(jumlahInput.value) || 1;
      if (this.dataset.action === 'increase' && val < 10) jumlahInput.value = val + 1;
      if (this.dataset.action === 'decrease' && val > 1)  jumlahInput.value = val - 1;
    });
  });

  // Kartu golongan darah
  document.querySelectorAll('.blood-type-card').forEach(card => {
    card.addEventListener('click', function () {
      goldarSelect.value = this.dataset.type;
      document.querySelectorAll('.blood-type-card').forEach(c => c.classList.remove('selected'));
      this.classList.add('selected');
    });
  });

  // Urgency badge update
  statusInputs.forEach(input => input.addEventListener('change', updateUrgencyBadge));

  // Hanya angka untuk kontak
  kontakInput.addEventListener('input', function () {
    this.value = this.value.replace(/[^0-9]/g, '');
  });

  // Deteksi lokasi
  if (getLocationBtn) {
    getLocationBtn.addEventListener('click', function () {
      if (!navigator.geolocation) { alert('Browser tidak mendukung geolokasi.'); return; }
      getLocationBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mendeteksi...';
      getLocationBtn.disabled = true;
      navigator.geolocation.getCurrentPosition(
        pos => {
          alert(`Lokasi terdeteksi!\nLat: ${pos.coords.latitude.toFixed(4)}, Long: ${pos.coords.longitude.toFixed(4)}\nSilakan isi nama rumah sakit secara manual.`);
          getLocationBtn.innerHTML = '<i class="fas fa-location-dot"></i> Deteksi Lokasi';
          getLocationBtn.disabled = false;
        },
        () => {
          alert('Gagal mendeteksi lokasi. Silakan isi manual.');
          getLocationBtn.innerHTML = '<i class="fas fa-location-dot"></i> Deteksi Lokasi';
          getLocationBtn.disabled = false;
        }
      );
    });
  }

  // Tombol darurat
  if (emergencyCallBtn) {
    emergencyCallBtn.addEventListener('click', function () {
      if (confirm('Anda akan menghubungi layanan darurat PMI. Lanjutkan?')) {
        window.location.href = 'tel:119';
      }
    });
  }

  // ==================== SUBMIT FORM KE API ====================

  form.addEventListener('submit', async function (e) {
    e.preventDefault();

    if (!confirmCheckbox.checked) {
      alert('Anda harus menyetujui pernyataan bahwa data yang diisi benar.');
      return;
    }

    if (!validateStep(1) || !validateStep(2) || !validateStep(3)) return;

    // Ambil golongan darah & rhesus dari value goldar (contoh: "A+", "AB-")
    const goldarValue = goldarSelect.value; // misal "A+"
    const golongan = goldarValue.replace(/[+-]/, ''); // "A"
    const rhesus   = goldarValue.includes('+') ? '+' : '-';

    // Ambil status urgensi dan konversi ke value backend
    const statusMap = { 'DARURAT': 'darurat', 'NORMAL': 'normal', 'TERENCANA': 'terjadwal' };
    const statusValue = document.querySelector('input[name="status"]:checked')?.value;

    // Format tanggal ke YYYY-MM-DD
    const deadlineDate = deadlineInput.value ? deadlineInput.value.split('T')[0] : '';

    const payload = {
      nama_pasien:        namaInput.value,
      usia_pasien:        parseInt(usiaInput.value) || 0,
      jenis_kelamin:      document.querySelector('input[name="gender"]:checked')?.value === 'Perempuan' ? 'perempuan' : 'laki-laki',
      diagnosis:          diagnosisInput.value || '-',
      golongan_darah:     golongan,
      rhesus:             rhesus,
      jumlah_kantong:     parseInt(jumlahInput.value) || 1,
      tanggal_dibutuhkan: deadlineDate,
      tingkat_urgensi:    statusMap[statusValue] || 'normal',
      nama_rumah_sakit:   rsInput.value,
      alamat_lengkap:     alamatInput.value || rsInput.value,
      nama_kontak:        namaKontakInput.value || namaInput.value,
      telepon_kontak:     kontakInput.value,
      pernyataan_setuju:  true,
    };

    // Loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';

    try {
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

      const response = await fetch('/api/permintaan-darurat', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken || '',
          'Accept': 'application/json',
        },
        body: JSON.stringify(payload),
      });

      const data = await response.json();

      if (response.ok && data.success) {
        // Tampilkan resi sukses
        resultDiv.classList.remove('hidden');
        resultDiv.innerHTML = `
          <div class="success-message" style="background:#e8f5e9;border:2px solid #2a9d8f;border-radius:12px;padding:30px;text-align:center;margin-top:20px;">
            <i class="fas fa-check-circle" style="font-size:48px;color:#2a9d8f;"></i>
            <h3 style="color:#2a9d8f;margin:15px 0;">Permintaan Darurat Terkirim!</h3>
            <p>Permintaan darah untuk <strong>${payload.nama_pasien}</strong>
               (${goldarValue}, ${payload.jumlah_kantong} kantong) telah diterima.</p>
            <div style="background:white;border-radius:8px;padding:15px;margin:20px 0;display:inline-block;">
              <p style="margin:0;font-size:14px;color:#666;">Nomor Resi Anda:</p>
              <h2 style="margin:5px 0;color:#c1121f;letter-spacing:2px;">${data.nomor_resi}</h2>
              <p style="margin:0;font-size:12px;color:#999;">Simpan nomor ini untuk mengecek status permintaan</p>
            </div>
            <p>Tim akan segera menghubungi <strong>${payload.telepon_kontak}</strong>.</p>
            <button onclick="window.location.href='/'" style="margin-top:15px;padding:12px 30px;background:#c1121f;color:white;border:none;border-radius:8px;cursor:pointer;font-size:16px;">
              <i class="fas fa-home"></i> Kembali ke Beranda
            </button>
          </div>
        `;
        resultDiv.scrollIntoView({ behavior: 'smooth' });
      } else {
        // Tampilkan error validasi dari Laravel
        const errors = data.errors ? Object.values(data.errors).flat().join('\n') : (data.message || 'Terjadi kesalahan.');
        alert('Gagal mengirim:\n' + errors);
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Kirim Permintaan Darurat';
      }
    } catch (err) {
      alert('Koneksi gagal. Pastikan server Laravel berjalan.');
      submitBtn.disabled = false;
      submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Kirim Permintaan Darurat';
    }
  });
});