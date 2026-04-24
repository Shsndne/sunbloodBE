// feedback.js
document.addEventListener('DOMContentLoaded', function () {
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;

    // Star rating
    let selectedRating = null;
    document.querySelectorAll('#starRating span').forEach(star => {
        star.addEventListener('click', function () {
            selectedRating = parseInt(this.dataset.value);
            document.getElementById('fbRating').value = selectedRating;
            document.querySelectorAll('#starRating span').forEach((s, i) => {
                s.classList.toggle('active', i < selectedRating);
            });
        });
        star.addEventListener('mouseover', function () {
            const val = parseInt(this.dataset.value);
            document.querySelectorAll('#starRating span').forEach((s, i) => {
                s.classList.toggle('hover', i < val);
            });
        });
        star.addEventListener('mouseout', function () {
            document.querySelectorAll('#starRating span').forEach(s => s.classList.remove('hover'));
        });
    });

    document.getElementById('feedbackForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const pesan = document.getElementById('fbPesan').value.trim();
        if (!pesan) return alert('Pesan feedback wajib diisi!');

        const btn = document.getElementById('fbSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';

        try {
            const resp = await fetch('/api/feedback', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    nama:   document.getElementById('fbNama').value || null,
                    email:  document.getElementById('fbEmail').value || null,
                    pesan:  pesan,
                    rating: selectedRating || null,
                }),
            });
            const data = await resp.json();
            if (data.success) {
                document.getElementById('feedbackForm').reset();
                selectedRating = null;
                document.querySelectorAll('#starRating span').forEach(s => s.classList.remove('active'));
                document.getElementById('feedbackSuccess').style.display = 'block';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                alert('Gagal mengirim: ' + (data.message || 'Coba lagi.'));
            }
        } catch (err) {
            alert('Koneksi bermasalah. Coba lagi.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane"></i> Kirim Feedback';
        }
    });
});