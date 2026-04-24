// consultation.js - Chatbot donor darah
document.addEventListener('DOMContentLoaded', function () {
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.content;
    const chatInput = document.getElementById('chatInput');
    const sendBtn = document.getElementById('sendBtn');
    const chatMessages = document.getElementById('chatMessages');

    // Knowledge base chatbot
    const kb = [
        { keywords: ['syarat', 'persyaratan', 'boleh donor', 'bisa donor'],
          answer: '🩸 <strong>Syarat Donor Darah:</strong><br>✅ Usia 17–65 tahun<br>✅ Berat badan minimal 45 kg<br>✅ Tekanan darah normal (sistolik 90–160, diastolik 70–100)<br>✅ Hemoglobin ≥ 12,5 g/dL<br>✅ Sehat jasmani dan rohani<br>✅ Tidak sedang hamil/menyusui' },
        { keywords: ['frekuensi', 'berapa kali', 'berapa sering', 'interval'],
          answer: '📅 Donor darah dapat dilakukan setiap <strong>3 bulan sekali</strong> (12 minggu) untuk pria dan wanita. Jangan donor jika belum melewati masa interval tersebut.' },
        { keywords: ['sakit', 'nyeri', 'menyakitkan', 'rasa sakit'],
          answer: '💉 Proses donor darah hanya terasa seperti cubitan kecil saat jarum masuk. Setelah itu hampir tidak ada rasa sakit. Seluruh proses hanya 10–15 menit.' },
        { keywords: ['manfaat', 'keuntungan', 'dampak positif'],
          answer: '❤️ <strong>Manfaat Donor Darah:</strong><br>• Membantu menyelamatkan nyawa<br>• Memperbarui sel darah merah<br>• Mengurangi risiko penyakit jantung<br>• Mengetahui kondisi kesehatan gratis (tes darah)' },
        { keywords: ['golongan', 'AB', 'O', 'rhesus', 'langka'],
          answer: '🩸 <strong>Golongan darah langka:</strong> AB– paling langka (~1%). O– disebut donor universal karena bisa diterima semua golongan. AB+ adalah penerima universal.' },
        { keywords: ['setelah donor', 'pasca donor', 'setelah selesai'],
          answer: '✅ <strong>Setelah donor:</strong><br>• Istirahat 10–15 menit<br>• Minum air putih yang banyak<br>• Makan makanan bergizi<br>• Hindari aktivitas berat 24 jam<br>• Jangan merokok 2 jam setelah donor' },
        { keywords: ['tidak boleh', 'larangan', 'dilarang donor'],
          answer: '⚠️ <strong>Tidak boleh donor jika:</strong><br>• Sedang sakit atau demam<br>• Baru saja operasi<br>• Sedang hamil/menyusui<br>• Mengonsumsi obat tertentu<br>• Berat badan di bawah 45 kg<br>• Pernah hepatitis B/C atau HIV' },
    ];

    function getAnswer(text) {
        const lower = text.toLowerCase();
        for (const item of kb) {
            if (item.keywords.some(k => lower.includes(k))) {
                return item.answer;
            }
        }
        return '🤔 Saya belum memiliki informasi tentang itu. Untuk pertanyaan lebih lanjut, silakan hubungi PMI terdekat atau kunjungi <a href="https://pmi.or.id" target="_blank">pmi.or.id</a>.';
    }

    function addMessage(content, isUser = false) {
        const div = document.createElement('div');
        div.className = 'message ' + (isUser ? 'user-message' : 'bot-message');
        div.innerHTML = isUser
            ? `<div class="message-content">${content}</div>`
            : `<div class="message-avatar"><i class="fas fa-robot"></i></div><div class="message-content">${content}</div>`;
        chatMessages.appendChild(div);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function addTyping() {
        const div = document.createElement('div');
        div.className = 'message bot-message typing-indicator';
        div.id = 'typing';
        div.innerHTML = '<div class="message-avatar"><i class="fas fa-robot"></i></div><div class="message-content"><span></span><span></span><span></span></div>';
        chatMessages.appendChild(div);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function sendMessage() {
        const text = chatInput.value.trim();
        if (!text) return;
        addMessage(text, true);
        chatInput.value = '';
        addTyping();
        setTimeout(() => {
            document.getElementById('typing')?.remove();
            addMessage(getAnswer(text));
        }, 800);
    }

    window.sendQuick = function (text) {
        chatInput.value = text;
        sendMessage();
    };

    sendBtn.addEventListener('click', sendMessage);
    chatInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') sendMessage();
    });
});