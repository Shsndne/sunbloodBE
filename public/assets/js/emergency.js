// DOM Elements
const bloodForm = document.getElementById('bloodForm');
const progressFill = document.getElementById('progressFill');
const urgencyDisplay = document.getElementById('urgencyDisplay');
const steps = document.querySelectorAll('.form-step');
const stepButtons = document.querySelectorAll('.btn-next, .btn-prev');
const bloodTypeCards = document.querySelectorAll('.blood-type-card');
const bloodSelect = document.getElementById('goldar');
const jumlahInput = document.getElementById('jumlah');
const qtyButtons = document.querySelectorAll('.qty-btn');
const getLocationBtn = document.getElementById('getLocation');
const emergencyCallBtn = document.getElementById('emergencyCall');
const confirmCheckbox = document.getElementById('confirmData');
const resultDiv = document.getElementById('result');

// Current step tracking
let currentStep = 1;
const totalSteps = 4;

// Initialize form
document.addEventListener('DOMContentLoaded', function() {
  updateProgress();
  setupBloodTypeSelection();
  setupQuantitySelector();
  setupStepNavigation();
  setupFormValidation();
  setupEmergencyCall();
  setupLocationDetection();
  
  // Set default deadline to 2 hours from now
  const now = new Date();
  const twoHoursLater = new Date(now.getTime() + 2 * 60 * 60 * 1000);
  document.getElementById('deadline').value = formatDateTimeLocal(twoHoursLater);
  
  // Set default phone pattern
  document.getElementById('kontak').addEventListener('input', function(e) {
    this.value = this.value.replace(/\D/g, '').substring(0, 13);
  });
});

// Update progress bar
function updateProgress() {
  const progress = (currentStep / totalSteps) * 100;
  progressFill.style.width = `${progress}%`;
  
  // Update step indicators
  document.querySelectorAll('.step').forEach((step, index) => {
    if (index + 1 <= currentStep) {
      step.classList.add('active');
    } else {
      step.classList.remove('active');
    }
  });
}

// Format date for datetime-local input
function formatDateTimeLocal(date) {
  return date.toISOString().slice(0, 16);
}

// Blood type selection
function setupBloodTypeSelection() {
  bloodTypeCards.forEach(card => {
    card.addEventListener('click', function() {
      const type = this.getAttribute('data-type');
      bloodSelect.value = type;
      
      // Update UI
      bloodTypeCards.forEach(c => c.classList.remove('selected'));
      this.classList.add('selected');
      
      // Update urgency display
      const status = this.querySelector('.blood-status').textContent;
      updateUrgencyDisplay(type, status);
    });
  });
  
  // Sync select with cards
  bloodSelect.addEventListener('change', function() {
    const selectedType = this.value;
    bloodTypeCards.forEach(card => {
      card.classList.remove('selected');
      if (card.getAttribute('data-type') === selectedType) {
        card.classList.add('selected');
        const status = card.querySelector('.blood-status').textContent;
        updateUrgencyDisplay(selectedType, status);
      }
    });
  });
}

// Update urgency display
function updateUrgencyDisplay(type, status) {
  let urgencyText = '';
  let urgencyClass = '';
  
  if (status === 'Kritis') {
    urgencyText = `DARURAT! Golongan ${type} dalam kondisi KRITIS`;
    urgencyClass = 'critical';
  } else if (status === 'Sedang') {
    urgencyText = `Golongan ${type} dalam kondisi SEDANG`;
    urgencyClass = 'moderate';
  } else {
    urgencyText = `Golongan ${type} dalam kondisi AMAN`;
    urgencyClass = 'safe';
  }
  
  urgencyDisplay.innerHTML = `
    <i class="fas fa-exclamation-circle"></i>
    <span>${urgencyText}</span>
  `;
  urgencyDisplay.className = `urgency-badge ${urgencyClass}`;
}

// Quantity selector
function setupQuantitySelector() {
  qtyButtons.forEach(button => {
    button.addEventListener('click', function() {
      const action = this.getAttribute('data-action');
      let value = parseInt(jumlahInput.value);
      
      if (action === 'increase' && value < 10) {
        value++;
      } else if (action === 'decrease' && value > 1) {
        value--;
      }
      
      jumlahInput.value = value;
    });
  });
}

// Step navigation
function setupStepNavigation() {
  stepButtons.forEach(button => {
    button.addEventListener('click', function() {
      const targetStep = this.getAttribute('data-next') || this.getAttribute('data-prev');
      
      if (targetStep && validateCurrentStep()) {
        // Update summary for step 4
        if (targetStep === 'step4') {
          updateSummary();
        }
        
        // Navigate to step
        changeStep(targetStep);
      }
    });
  });
}

// Validate current step
function validateCurrentStep() {
  const currentStepEl = document.getElementById(`step${currentStep}`);
  const requiredInputs = currentStepEl.querySelectorAll('[required]');
  let isValid = true;
  
  requiredInputs.forEach(input => {
    if (!input.value.trim()) {
      isValid = false;
      showFieldError(input, 'Field ini wajib diisi');
    }
  });
  
  // Additional validation for step 1
  if (currentStep === 1) {
    const usia = document.getElementById('usia').value;
    if (usia && (usia < 0 || usia > 120)) {
      isValid = false;
      showFieldError(document.getElementById('usia'), 'Usia harus antara 0-120 tahun');
    }
  }
  
  // Additional validation for step 3
  if (currentStep === 3) {
    const phone = document.getElementById('kontak').value;
    if (phone && phone.length < 10) {
      isValid = false;
      showFieldError(document.getElementById('kontak'), 'Nomor telepon minimal 10 digit');
    }
  }
  
  return isValid;
}

// Show field error
function showFieldError(input, message) {
  input.style.borderColor = '#c1121f';
  input.style.boxShadow = '0 0 0 3px rgba(193, 18, 31, 0.2)';
  
  // Show tooltip
  const tooltip = document.createElement('div');
  tooltip.className = 'field-error';
  tooltip.textContent = message;
  tooltip.style.cssText = `
    position: absolute;
    background: #c1121f;
    color: white;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 12px;
    margin-top: 5px;
    z-index: 1000;
  `;
  
  input.parentNode.appendChild(tooltip);
  
  // Remove error after 3 seconds
  setTimeout(() => {
    input.style.borderColor = '#e0e0e0';
    input.style.boxShadow = 'none';
    if (tooltip.parentNode) {
      tooltip.parentNode.removeChild(tooltip);
    }
  }, 3000);
}

// Change step
function changeStep(stepId) {
  // Hide all steps
  steps.forEach(step => step.classList.remove('active'));
  
  // Show target step
  document.getElementById(stepId).classList.add('active');
  
  // Update current step
  currentStep = parseInt(stepId.replace('step', ''));
  updateProgress();
  
  // Scroll to top of form
  document.querySelector('.container').scrollIntoView({ 
    behavior: 'smooth',
    block: 'start'
  });
}

// Update summary
function updateSummary() {
  // Patient data
  document.getElementById('summaryNama').textContent = document.getElementById('nama').value || '-';
  document.getElementById('summaryUsia').textContent = document.getElementById('usia').value || '-';
  document.getElementById('summaryGender').textContent = document.querySelector('input[name="gender"]:checked')?.value || '-';
  document.getElementById('summaryDiagnosis').textContent = document.getElementById('diagnosis').value || '-';
  
  // Blood needs
  document.getElementById('summaryGoldar').textContent = bloodSelect.value || '-';
  document.getElementById('summaryJumlah').textContent = jumlahInput.value || '1';
  
  const deadline = document.getElementById('deadline').value;
  if (deadline) {
    const deadlineDate = new Date(deadline);
    document.getElementById('summaryDeadline').textContent = deadlineDate.toLocaleString('id-ID', {
      day: 'numeric',
      month: 'long',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    });
  } else {
    document.getElementById('summaryDeadline').textContent = '-';
  }
  
  const status = document.querySelector('input[name="status"]:checked');
  document.getElementById('summaryStatus').textContent = status ? status.value : '-';
  
  // Location & contact
  document.getElementById('summaryRS').textContent = document.getElementById('rs').value || '-';
  document.getElementById('summaryAlamat').textContent = document.getElementById('alamat').value || '-';
  document.getElementById('summaryKontak').textContent = document.getElementById('kontak').value || '-';
  document.getElementById('summaryNamaKontak').textContent = document.getElementById('namaKontak').value || '-';
}

// Form validation setup
function setupFormValidation() {
  // Real-time validation for phone number
  const phoneInput = document.getElementById('kontak');
  phoneInput.addEventListener('blur', function() {
    if (this.value && this.value.length < 10) {
      this.style.borderColor = '#c1121f';
    } else {
      this.style.borderColor = '#e0e0e0';
    }
  });
  
  // Real-time validation for age
  const ageInput = document.getElementById('usia');
  ageInput.addEventListener('blur', function() {
    const age = parseInt(this.value);
    if (age && (age < 0 || age > 120)) {
      this.style.borderColor = '#c1121f';
    } else {
      this.style.borderColor = '#e0e0e0';
    }
  });
}

// Emergency call setup
function setupEmergencyCall() {
  emergencyCallBtn.addEventListener('click', function() {
    showNotification('Memanggil layanan darurat...', 'warning');
    
    // Simulate emergency call
    setTimeout(() => {
      showNotification('Terhubung dengan operator darurat. Siapkan informasi pasien.', 'success');
      
      // Pre-fill emergency info
      const nama = document.getElementById('nama').value;
      const goldar = bloodSelect.value;
      const rs = document.getElementById('rs').value;
      
      if (nama || goldar || rs) {
        const message = `Info Darurat: ${nama ? `Pasien: ${nama}` : ''}${goldar ? ` | Gol. Darah: ${goldar}` : ''}${rs ? ` | RS: ${rs}` : ''}`;
        showNotification(message, 'info');
      }
    }, 1500);
  });
}

// Location detection
function setupLocationDetection() {
  if (!getLocationBtn) return;
  
  getLocationBtn.addEventListener('click', function() {
    if (navigator.geolocation) {
      showNotification('Mendeteksi lokasi Anda...', 'info');
      
      navigator.geolocation.getCurrentPosition(
        function(position) {
          const lat = position.coords.latitude;
          const lon = position.coords.longitude;
          
          // For demo purposes, show mock location
          showNotification('Lokasi berhasil dideteksi', 'success');
          document.getElementById('rs').value = 'Rumah Sakit Umum Terdekat';
          document.getElementById('alamat').value = `Jl. Contoh No. 123, Kec. Semarang Tengah, Kota Semarang`;
        },
        function(error) {
          showNotification('Gagal mendeteksi lokasi. Silakan masukkan manual.', 'error');
        }
      );
    } else {
      showNotification('Browser tidak mendukung deteksi lokasi', 'error');
    }
  });
}

// Show notification
function showNotification(message, type = 'info') {
  // Remove existing notification
  const existingNotification = document.querySelector('.notification-temp');
  if (existingNotification) {
    existingNotification.remove();
  }
  
  // Create notification
  const notification = document.createElement('div');
  notification.className = `notification-temp notification-${type}`;
  notification.textContent = message;
  notification.style.cssText = `
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 12px 20px;
    border-radius: 8px;
    background: ${type === 'error' ? '#c1121f' : type === 'warning' ? '#f77f00' : type === 'success' ? '#2a9d8f' : '#0077b6'};
    color: white;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 9999;
    animation: slideIn 0.3s ease;
  `;
  
  document.body.appendChild(notification);
  
  // Remove after 5 seconds
  setTimeout(() => {
    notification.style.animation = 'slideOut 0.3s ease';
    setTimeout(() => {
      if (notification.parentNode) {
        notification.parentNode.removeChild(notification);
      }
    }, 300);
  }, 5000);
}

// Form submission
bloodForm.addEventListener('submit', async function(e) {
  e.preventDefault();
  
  if (!confirmCheckbox.checked) {
    showNotification('Harap konfirmasi bahwa data yang diisi benar', 'warning');
    confirmCheckbox.focus();
    return;
  }
  
  // Show loading state
  const submitBtn = document.getElementById('submitBtn');
  const originalText = submitBtn.innerHTML;
  submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
  submitBtn.disabled = true;
  
  // Collect form data
  const formData = {
    patient: {
      nama: document.getElementById('nama').value,
      usia: document.getElementById('usia').value,
      gender: document.querySelector('input[name="gender"]:checked')?.value,
      diagnosis: document.getElementById('diagnosis').value
    },
    blood: {
      golongan: bloodSelect.value,
      jumlah: jumlahInput.value,
      deadline: document.getElementById('deadline').value,
      status: document.querySelector('input[name="status"]:checked')?.value
    },
    location: {
      rs: document.getElementById('rs').value,
      alamat: document.getElementById('alamat').value,
      dokter: document.getElementById('dokter')?.value,
      ruang: document.getElementById('ruang')?.value
    },
    contact: {
      nomor: document.getElementById('kontak').value,
      nama: document.getElementById('namaKontak').value
    },
    timestamp: new Date().toISOString()
  };
  
  // Simulate API call
  try {
    await simulateSubmission(formData);
    
    // Show success result
    showRequestResult(formData);
    
    // Reset button
    submitBtn.innerHTML = originalText;
    submitBtn.disabled = false;
    
  } catch (error) {
    showNotification('Gagal mengirim permintaan. Coba lagi.', 'error');
    submitBtn.innerHTML = originalText;
    submitBtn.disabled = false;
  }
});

// Simulate submission
function simulateSubmission(formData) {
  return new Promise((resolve, reject) => {
    setTimeout(() => {
      // Simulate 90% success rate
      const isSuccess = Math.random() > 0.1;
      
      if (isSuccess) {
        // Save to localStorage for demo
        const requests = JSON.parse(localStorage.getItem('bloodRequests') || '[]');
        requests.push({
          ...formData,
          id: 'BLD-' + Date.now().toString().slice(-6),
          status: 'PENDING'
        });
        localStorage.setItem('bloodRequests', JSON.stringify(requests));
        
        resolve(formData);
      } else {
        reject(new Error('Network error'));
      }
    }, 1500);
  });
}

// Show request result
function showRequestResult(formData) {
  const status = formData.blood.status;
  const isEmergency = status === 'DARURAT';
  
  // Create WhatsApp message
  const waMessage = `
🩸 PERMINTAAN DARAH ${status} 🩸

👤 DATA PASIEN
Nama: ${formData.patient.nama}
Usia: ${formData.patient.usia} tahun
Jenis Kelamin: ${formData.patient.gender}
Diagnosis: ${formData.patient.diagnosis}

💉 KEBUTUHAN DARAH
Golongan Darah: ${formData.blood.golongan}
Jumlah: ${formData.blood.jumlah} kantong
Dibutuhkan Sebelum: ${new Date(formData.blood.deadline).toLocaleString('id-ID')}
Status: ${formData.blood.status}

🏥 LOKASI
Rumah Sakit: ${formData.location.rs}
Alamat: ${formData.location.alamat}

📞 KONTAK DARURAT
Nama: ${formData.contact.nama}
Nomor: ${formData.contact.nomor}

⏰ Dikirim pada: ${new Date(formData.timestamp).toLocaleString('id-ID')}

#DaruratDarah #PMI #DonorDarah
  `.trim();
  
  const waLink = `https://wa.me/?text=${encodeURIComponent(waMessage)}`;
  
  // Display result
  resultDiv.className = isEmergency ? 'darurat' : 'normal';
  resultDiv.classList.remove('hidden');
  
  resultDiv.innerHTML = `
    <div style="text-align: center; margin-bottom: 20px;">
      <i class="fas fa-check-circle" style="font-size: 48px; color: ${isEmergency ? '#c1121f' : '#0077b6'}"></i>
      <h2 style="color: ${isEmergency ? '#c1121f' : '#0077b6'}; margin: 15px 0;">Permintaan Berhasil Dikirim!</h2>
      <p>Permintaan darah ${formData.blood.golongan} telah dikirim ke sistem PMI.</p>
    </div>
    
    <div style="background: white; padding: 20px; border-radius: 10px; margin: 20px 0;">
      <h3><i class="fas fa-info-circle"></i> Detail Permintaan</h3>
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 15px;">
        <div><strong>Kode Permintaan:</strong><br>BLD-${Date.now().toString().slice(-6)}</div>
        <div><strong>Estimasi Respons:</strong><br>${isEmergency ? '15-30 menit' : '1-3 jam'}</div>
        <div><strong>Status Stok:</strong><br>${getStockStatus(formData.blood.golongan)}</div>
      </div>
    </div>
    
    <div style="background: rgba(255,255,255,0.5); padding: 20px; border-radius: 10px; margin: 20px 0;">
      <h3><i class="fab fa-whatsapp"></i> Sebarkan via WhatsApp</h3>
      <p>Bantu temukan donor dengan menyebarkan permintaan ini:</p>
      <div style="display: flex; gap: 10px; margin-top: 15px; flex-wrap: wrap;">
        <a href="${waLink}" target="_blank" style="flex: 1; min-width: 200px;">
          <button style="width: 100%; padding: 15px; background: #25D366; color: white; border: none; border-radius: 10px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px;">
            <i class="fab fa-whatsapp"></i> Share ke WhatsApp
          </button>
        </a>
        <button onclick="copyToClipboard('${waMessage.replace(/'/g, "\\'")}')" style="flex: 1; min-width: 200px; padding: 15px; background: #667eea; color: white; border: none; border-radius: 10px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px;">
          <i class="fas fa-copy"></i> Salin Teks
        </button>
      </div>
    </div>
    
    ${isEmergency ? `
    <div style="background: #fff3cd; border: 2px solid #ffc107; padding: 20px; border-radius: 10px; margin: 20px 0;">
      <h3><i class="fas fa-exclamation-triangle"></i> Tindakan Darurat</h3>
      <p>Karena status <strong>DARURAT</strong>, kami telah:</p>
      <ul style="margin: 10px 0 10px 20px;">
        <li>Mengirim notifikasi ke semua donor ${formData.blood.golongan} di sekitar ${formData.location.rs}</li>
        <li>Memberikan prioritas tinggi pada permintaan ini</li>
        <li>Mengaktifkan jaringan rumah sakit terdekat</li>
      </ul>
      <p style="color: #c1121f; font-weight: bold;">
        <i class="fas fa-phone"></i> Tim PMI akan menghubungi Anda dalam 15 menit.
      </p>
    </div>
    ` : ''}
    
    <div style="text-align: center; margin-top: 30px;">
      <button onclick="resetForm()" style="padding: 12px 30px; background: #6c757d; color: white; border: none; border-radius: 8px; cursor: pointer; margin-right: 10px;">
        <i class="fas fa-plus"></i> Buat Permintaan Baru
      </button>
      <button onclick="window.print()" style="padding: 12px 30px; background: #2a9d8f; color: white; border: none; border-radius: 8px; cursor: pointer;">
        <i class="fas fa-print"></i> Cetak Ringkasan
      </button>
    </div>
  `;
  
  // Scroll to result
  resultDiv.scrollIntoView({ behavior: 'smooth' });
  
  // Show confirmation
  showNotification(`Permintaan darah ${formData.blood.golongan} berhasil dikirim!`, 'success');
}

// Get stock status based on blood type
function getStockStatus(bloodType) {
  const criticalTypes = ['A-', 'AB-', 'O-'];
  const moderateTypes = ['B-'];
  
  if (criticalTypes.includes(bloodType)) return 'KRITIS';
  if (moderateTypes.includes(bloodType)) return 'SEDANG';
  return 'AMAN';
}

// Reset form
function resetForm() {
  bloodForm.reset();
  resultDiv.classList.add('hidden');
  bloodTypeCards.forEach(card => card.classList.remove('selected'));
  changeStep('step1');
  
  // Reset default values
  jumlahInput.value = '1';
  const now = new Date();
  const twoHoursLater = new Date(now.getTime() + 2 * 60 * 60 * 1000);
  document.getElementById('deadline').value = formatDateTimeLocal(twoHoursLater);
}

// Copy to clipboard function
window.copyToClipboard = function(text) {
  navigator.clipboard.writeText(text)
    .then(() => showNotification('Teks berhasil disalin!', 'success'))
    .catch(() => showNotification('Gagal menyalin teks', 'error'));
};

// CSS for notifications
const style = document.createElement('style');
style.textContent = `
  @keyframes slideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
  }
  
  @keyframes slideOut {
    from { transform: translateX(0); opacity: 1; }
    to { transform: translateX(100%); opacity: 0; }
  }
`;
document.head.appendChild(style);