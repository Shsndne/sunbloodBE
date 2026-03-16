// emergency.js

document.addEventListener('DOMContentLoaded', function() {
    // ==================== ELEMEN DOM ====================
    const form = document.getElementById('bloodForm');
    const steps = document.querySelectorAll('.form-step');
    const progressSteps = document.querySelectorAll('.step');
    const progressFill = document.getElementById('progressFill');
    const urgencyDisplay = document.getElementById('urgencyDisplay');
    const getLocationBtn = document.getElementById('getLocation');
    const emergencyCallBtn = document.getElementById('emergencyCall');
    const submitBtn = document.getElementById('submitBtn');
    const resultDiv = document.getElementById('result');

    // Form Input Elements
    const namaInput = document.getElementById('nama');
    const usiaInput = document.getElementById('usia');
    const genderInputs = document.querySelectorAll('input[name="gender"]');
    const diagnosisInput = document.getElementById('diagnosis');
    const goldarSelect = document.getElementById('goldar');
    const jumlahInput = document.getElementById('jumlah');
    const deadlineInput = document.getElementById('deadline');
    const statusInputs = document.querySelectorAll('input[name="status"]');
    const rsInput = document.getElementById('rs');
    const alamatInput = document.getElementById('alamat');
    const kontakInput = document.getElementById('kontak');
    const namaKontakInput = document.getElementById('namaKontak');
    const confirmCheckbox = document.getElementById('confirmData');

    // Summary Elements
    const summaryNama = document.getElementById('summaryNama');
    const summaryUsia = document.getElementById('summaryUsia');
    const summaryGender = document.getElementById('summaryGender');
    const summaryDiagnosis = document.getElementById('summaryDiagnosis');
    const summaryGoldar = document.getElementById('summaryGoldar');
    const summaryJumlah = document.getElementById('summaryJumlah');
    const summaryDeadline = document.getElementById('summaryDeadline');
    const summaryStatus = document.getElementById('summaryStatus');
    const summaryRS = document.getElementById('summaryRS');
    const summaryAlamat = document.getElementById('summaryAlamat');
    const summaryKontak = document.getElementById('summaryKontak');
    const summaryNamaKontak = document.getElementById('summaryNamaKontak');

// state
    let currentStep = 1;
    const totalSteps = 4;

// inisialisasi
    updateProgress();
    updateUrgencyBadge();

    // Set default deadline to tomorrow
    setDefaultDeadline();

// fungsi utiity
    function setDefaultDeadline() {
        if (!deadlineInput.value) {
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            tomorrow.setHours(10, 0, 0, 0); // Set to 10:00 AM
            
            const year = tomorrow.getFullYear();
            const month = String(tomorrow.getMonth() + 1).padStart(2, '0');
            const day = String(tomorrow.getDate()).padStart(2, '0');
            const hours = String(tomorrow.getHours()).padStart(2, '0');
            const minutes = String(tomorrow.getMinutes()).padStart(2, '0');
            
            deadlineInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;
        }
    }

    function updateProgress() {
        // Update progress steps
        progressSteps.forEach(step => {
            const stepNum = parseInt(step.dataset.step);
            if (stepNum === currentStep) {
                step.classList.add('active');
            } else {
                step.classList.remove('active');
            }
        });

        // Update progress bar
        const progressPercentage = (currentStep / totalSteps) * 100;
        progressFill.style.width = `${progressPercentage}%`;
    }

    function showStep(stepNumber) {
        steps.forEach(step => {
            step.classList.remove('active');
        });
        
        const activeStep = document.getElementById(`step${stepNumber}`);
        if (activeStep) {
            activeStep.classList.add('active');
        }
        
        currentStep = stepNumber;
        updateProgress();
        
        // If step 4, update summary
        if (currentStep === 4) {
            updateSummary();
        }
    }

    function updateUrgencyBadge() {
        let selectedStatus = document.querySelector('input[name="status"]:checked');
        
        if (selectedStatus) {
            const statusValue = selectedStatus.value;
            urgencyDisplay.innerHTML = `<i class="fas fa-exclamation-circle"></i> <span>Status: ${statusValue}</span>`;
            
            // Update class for styling
            urgencyDisplay.className = 'urgency-badge';
            if (statusValue === 'DARURAT') {
                urgencyDisplay.classList.add('badge-emergency');
            } else if (statusValue === 'NORMAL') {
                urgencyDisplay.classList.add('badge-normal');
            } else if (statusValue === 'TERENCANA') {
                urgencyDisplay.classList.add('badge-planned');
            }
        } else {
            urgencyDisplay.innerHTML = '<i class="fas fa-exclamation-circle"></i> <span>Silakan isi formulir</span>';
            urgencyDisplay.className = 'urgency-badge';
        }
    }

    function updateSummary() {
        // Data Pasien
        summaryNama.textContent = namaInput.value || '-';
        summaryUsia.textContent = usiaInput.value || '-';
        
        let gender = 'Laki-laki';
        genderInputs.forEach(input => {
            if (input.checked) gender = input.value;
        });
        summaryGender.textContent = gender;
        summaryDiagnosis.textContent = diagnosisInput.value || '-';
        
        // Kebutuhan Darah
        summaryGoldar.textContent = goldarSelect.value || '-';
        summaryJumlah.textContent = jumlahInput.value || '0';
        
        // Format deadline
        if (deadlineInput.value) {
            const deadlineDate = new Date(deadlineInput.value);
            summaryDeadline.textContent = deadlineDate.toLocaleString('id-ID', {
                dateStyle: 'medium',
                timeStyle: 'short'
            });
        } else {
            summaryDeadline.textContent = '-';
        }
        
        let status = 'Tidak ditentukan';
        statusInputs.forEach(input => {
            if (input.checked) status = input.value;
        });
        summaryStatus.textContent = status;
        
        // Lokasi & Kontak
        summaryRS.textContent = rsInput.value || '-';
        summaryAlamat.textContent = alamatInput.value || '-';
        summaryKontak.textContent = kontakInput.value || '-';
        summaryNamaKontak.textContent = namaKontakInput.value || '-';
    }

    function validateStep(stepNumber) {
        switch(stepNumber) {
            case 1:
                if (!namaInput.value.trim()) {
                    alert('Nama pasien harus diisi');
                    namaInput.focus();
                    return false;
                }
                return true;
                
            case 2:
                if (!goldarSelect.value) {
                    alert('Pilih golongan darah');
                    goldarSelect.focus();
                    return false;
                }
                if (!deadlineInput.value) {
                    alert('Tentukan waktu dibutuhkan');
                    deadlineInput.focus();
                    return false;
                }
                if (!document.querySelector('input[name="status"]:checked')) {
                    alert('Pilih tingkat urgensi');
                    return false;
                }
                return true;
                
            case 3:
                if (!rsInput.value.trim()) {
                    alert('Nama rumah sakit harus diisi');
                    rsInput.focus();
                    return false;
                }
                if (!kontakInput.value.trim()) {
                    alert('Nomor kontak darurat harus diisi');
                    kontakInput.focus();
                    return false;
                }
                // Simple phone validation
                const phoneRegex = /^[0-9]{10,13}$/;
                const cleanPhone = kontakInput.value.replace(/\D/g, '');
                if (!phoneRegex.test(cleanPhone)) {
                    alert('Nomor kontak tidak valid (10-13 digit)');
                    kontakInput.focus();
                    return false;
                }
                return true;
                
            default:
                return true;
        }
    }

    function resetForm() {
        // Reset to step 1
        showStep(1);
        
        // Clear all inputs (except default values)
        namaInput.value = '';
        usiaInput.value = '';
        genderInputs[0].checked = true;
        diagnosisInput.value = '';
        goldarSelect.value = '';
        jumlahInput.value = '1';
        setDefaultDeadline();
        statusInputs[0].checked = false;
        statusInputs[1].checked = false;
        statusInputs[2].checked = false;
        rsInput.value = '';
        alamatInput.value = '';
        kontakInput.value = '';
        namaKontakInput.value = '';
        confirmCheckbox.checked = false;
        
        updateUrgencyBadge();
    }

    // ==================== EVENT HANDLERS ====================
    
    // Navigation buttons
    document.querySelectorAll('.btn-next').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const nextStep = this.dataset.next;
            const nextStepNum = parseInt(nextStep.replace('step', ''));
            
            if (validateStep(currentStep)) {
                showStep(nextStepNum);
            }
        });
    });

    document.querySelectorAll('.btn-prev').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const prevStep = this.dataset.prev;
            const prevStepNum = parseInt(prevStep.replace('step', ''));
            showStep(prevStepNum);
        });
    });

    // Quantity buttons
    document.querySelectorAll('.qty-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const action = this.dataset.action;
            let currentValue = parseInt(jumlahInput.value) || 1;
            
            if (action === 'increase' && currentValue < 10) {
                jumlahInput.value = currentValue + 1;
            } else if (action === 'decrease' && currentValue > 1) {
                jumlahInput.value = currentValue - 1;
            }
        });
    });

    // Blood type cards (visual selection)
    document.querySelectorAll('.blood-type-card').forEach(card => {
        card.addEventListener('click', function() {
            const bloodType = this.dataset.type;
            goldarSelect.value = bloodType;
            
            // Highlight selected card
            document.querySelectorAll('.blood-type-card').forEach(c => {
                c.classList.remove('selected');
            });
            this.classList.add('selected');
        });
    });

    // Update urgency badge when status changes
    statusInputs.forEach(input => {
        input.addEventListener('change', updateUrgencyBadge);
    });

    // Get location button
    getLocationBtn.addEventListener('click', function() {
        if (navigator.geolocation) {
            getLocationBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mendeteksi...';
            getLocationBtn.disabled = true;
            
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    // In a real app, you'd reverse geocode here
                    // For demo, we'll just show a message
                    alert(`Lokasi terdeteksi! (Lat: ${position.coords.latitude.toFixed(4)}, Long: ${position.coords.longitude.toFixed(4)})\nSilakan isi alamat rumah sakit secara manual.`);
                    
                    getLocationBtn.innerHTML = '<i class="fas fa-location-dot"></i> Deteksi Lokasi';
                    getLocationBtn.disabled = false;
                },
                function(error) {
                    let errorMessage = 'Gagal mendeteksi lokasi. ';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage += 'Izin lokasi ditolak.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage += 'Informasi lokasi tidak tersedia.';
                            break;
                        case error.TIMEOUT:
                            errorMessage += 'Waktu permintaan habis.';
                            break;
                        default:
                            errorMessage += 'Terjadi kesalahan.';
                    }
                    alert(errorMessage);
                    
                    getLocationBtn.innerHTML = '<i class="fas fa-location-dot"></i> Deteksi Lokasi';
                    getLocationBtn.disabled = false;
                }
            );
        } else {
            alert('Browser Anda tidak mendukung geolokasi.');
        }
    });

    // Emergency call button
    emergencyCallBtn.addEventListener('click', function() {
        if (confirm('Anda akan menghubungi layanan darurat. Lanjutkan?')) {
            // In a real app, this would initiate a call to emergency services
            // For demo, we'll just simulate
            alert('Menghubungi layanan darurat (simulasi)...\nDalam aplikasi nyata, ini akan memanggil nomor darurat.');
        }
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate final step
        if (!confirmCheckbox.checked) {
            alert('Anda harus menyetujui pernyataan bahwa data yang diisi benar.');
            return;
        }
        
        if (!validateStep(1) || !validateStep(2) || !validateStep(3)) {
            // If any step is invalid, go to that step
            return;
        }
        
        // Disable submit button
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
        
        // Collect form data
        const formData = {
            pasien: {
                nama: namaInput.value,
                usia: usiaInput.value,
                gender: document.querySelector('input[name="gender"]:checked').value,
                diagnosis: diagnosisInput.value
            },
            darah: {
                goldar: goldarSelect.value,
                jumlah: jumlahInput.value,
                deadline: deadlineInput.value,
                status: document.querySelector('input[name="status"]:checked').value
            },
            lokasi: {
                rs: rsInput.value,
                alamat: alamatInput.value,
                kontak: kontakInput.value,
                namaKontak: namaKontakInput.value
            },
            timestamp: new Date().toISOString()
        };
        
        // Simulate API call
        setTimeout(() => {
            // Show success message
            resultDiv.classList.remove('hidden');
            resultDiv.innerHTML = `
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    <h3>Permintaan Darurat Terkirim!</h3>
                    <p>Permintaan darah untuk <strong>${formData.pasien.nama}</strong> (${formData.darah.goldar}, ${formData.darah.jumlah} kantong) telah diterima.</p>
                    <p>Tim PMI akan segera menghubungi ${formData.lokasi.kontak} dalam waktu 5-10 menit.</p>
                    <button class="btn" onclick="window.location.href='../index.html'">Kembali ke Beranda</button>
                </div>
            `;
            
            // Reset form (optional)
            resetForm();
            
            // Scroll to result
            resultDiv.scrollIntoView({ behavior: 'smooth' });
        }, 2000);
    });

    // Live validation for phone number
    kontakInput.addEventListener('input', function() {
        // Only allow numbers
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Auto-update summary when inputs change (optional)
    const inputsForSummary = [namaInput, usiaInput, diagnosisInput, goldarSelect, 
                             jumlahInput, deadlineInput, rsInput, alamatInput, 
                             kontakInput, namaKontakInput];
    
    inputsForSummary.forEach(input => {
        if (input) {
            input.addEventListener('input', function() {
                if (currentStep === 4) {
                    updateSummary();
                }
            });
        }
    });

    genderInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (currentStep === 4) {
                updateSummary();
            }
        });
    });

    statusInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (currentStep === 4) {
                updateSummary();
            }
        });
    });
});