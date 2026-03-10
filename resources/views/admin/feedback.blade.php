
@section('title', 'Admin - Kelola Feedback')

@push('styles')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 20px;
    }

    .admin-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .admin-header {
        background: white;
        padding: 25px;
        border-radius: 15px;
        margin-bottom: 25px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }

    .admin-header h1 {
        color: #333;
        margin-bottom: 10px;
        font-size: 28px;
    }

    .admin-header h1 i {
        color: #764ba2;
        margin-right: 10px;
    }

    .admin-header p {
        color: #666;
        font-size: 16px;
    }

    .filter-section {
        background: white;
        padding: 20px;
        border-radius: 15px;
        margin-bottom: 25px;
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 10px 25px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-btn i {
        font-size: 14px;
    }

    .filter-btn[data-filter="all"] {
        background: #667eea;
        color: white;
    }

    .filter-btn[data-filter="pending"] {
        background: #f6c23e;
        color: white;
    }

    .filter-btn[data-filter="read"] {
        background: #36b9cc;
        color: white;
    }

    .filter-btn[data-filter="responded"] {
        background: #1cc88a;
        color: white;
    }

    .filter-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 25px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .stat-card i {
        font-size: 30px;
        color: #667eea;
        margin-bottom: 10px;
    }

    .stat-card .stat-number {
        font-size: 32px;
        font-weight: bold;
        color: #333;
    }

    .stat-card .stat-label {
        color: #666;
        font-size: 14px;
    }

    .feedback-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 20px;
    }

    .feedback-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border-left: 4px solid;
    }

    .feedback-card[data-status="pending"] {
        border-left-color: #f6c23e;
    }

    .feedback-card[data-status="read"] {
        border-left-color: #36b9cc;
    }

    .feedback-card[data-status="responded"] {
        border-left-color: #1cc88a;
    }

    .feedback-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .feedback-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }

    .feedback-date {
        font-size: 12px;
        color: #999;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .feedback-date i {
        font-size: 12px;
    }

    .status-badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-badge.pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-badge.read {
        background: #d1ecf1;
        color: #0c5460;
    }

    .status-badge.responded {
        background: #d4edda;
        color: #155724;
    }

    .feedback-content {
        margin-bottom: 20px;
    }

    .feedback-content p {
        color: #555;
        line-height: 1.6;
        font-size: 14px;
    }

    .admin-response-section {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        margin-top: 15px;
    }

    .admin-response-section h4 {
        color: #333;
        font-size: 14px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .admin-response-section h4 i {
        color: #667eea;
    }

    .admin-response-text {
        background: white;
        padding: 12px;
        border-radius: 8px;
        border-left: 3px solid #667eea;
        margin-bottom: 10px;
        font-size: 13px;
        color: #555;
    }

    .response-date {
        font-size: 11px;
        color: #999;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .admin-input {
        width: 100%;
        padding: 12px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 13px;
        margin-bottom: 10px;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .admin-input:focus {
        outline: none;
        border-color: #667eea;
    }

    .feedback-actions {
        display: flex;
        gap: 10px;
        margin-top: 15px;
        flex-wrap: wrap;
    }

    .action-btn {
        padding: 8px 15px;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .action-btn.respond {
        background: #1cc88a;
        color: white;
    }

    .action-btn.read {
        background: #36b9cc;
        color: white;
    }

    .action-btn.delete {
        background: #e74a3b;
        color: white;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        filter: brightness(90%);
    }

    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        background: white;
        border-radius: 10px;
        padding: 15px 25px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        transform: translateX(400px);
        transition: transform 0.3s ease;
        z-index: 1000;
    }

    .notification.show {
        transform: translateX(0);
    }

    .notification-content {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .notification-content i {
        font-size: 20px;
    }

    .notification.success i {
        color: #1cc88a;
    }

    .notification.error i {
        color: #e74a3b;
    }

    @media (max-width: 768px) {
        .feedback-grid {
            grid-template-columns: 1fr;
        }
        
        .filter-section {
            flex-direction: column;
        }
        
        .filter-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

<div class="admin-container">
    <div class="admin-header">
        <h1><i class="fas fa-tachometer-alt"></i> Dashboard Admin - Kelola Feedback</h1>
        <p>Kelola, beri tanggapan, dan analisis feedback dari pelanggan</p>
    </div>

    <div class="stats-container" id="statsContainer">
        <!-- Stats will be loaded here -->
    </div>

    <div class="filter-section">
        <button class="filter-btn" data-filter="all" onclick="filterFeedback('all')">
            <i class="fas fa-list"></i> Semua
        </button>
        <button class="filter-btn" data-filter="pending" onclick="filterFeedback('pending')">
            <i class="fas fa-clock"></i> Pending
        </button>
        <button class="filter-btn" data-filter="read" onclick="filterFeedback('read')">
            <i class="fas fa-check-circle"></i> Dibaca
        </button>
        <button class="filter-btn" data-filter="responded" onclick="filterFeedback('responded')">
            <i class="fas fa-reply"></i> Direspon
        </button>
    </div>

    <div class="feedback-grid" id="feedbackGrid">
        <!-- Feedback cards will be loaded here -->
    </div>
</div>

<div class="notification" id="notification">
    <div class="notification-content">
        <i class="fas" id="notificationIcon"></i>
        <span id="notificationMessage"></span>
    </div>
</div>

@push('scripts')
<script>
    let currentFilter = 'all';

    function showNotification(message, type = 'success') {
        const notification = document.getElementById('notification');
        const icon = document.getElementById('notificationIcon');
        const messageEl = document.getElementById('notificationMessage');
        
        icon.className = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
        notification.className = `notification show ${type}`;
        messageEl.textContent = message;
        
        setTimeout(() => {
            notification.classList.remove('show');
        }, 3000);
    }

    async function loadStats() {
        try {
            const response = await fetch('/api/feedback/stats');
            const data = await response.json();
            
            if (data.success) {
                displayStats(data.data);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function displayStats(stats) {
        const statsContainer = document.getElementById('statsContainer');
        
        statsContainer.innerHTML = `
            <div class="stat-card">
                <i class="fas fa-envelope"></i>
                <div class="stat-number">${stats.total}</div>
                <div class="stat-label">Total Feedback</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-clock"></i>
                <div class="stat-number">${stats.pending}</div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-check-circle"></i>
                <div class="stat-number">${stats.read}</div>
                <div class="stat-label">Dibaca</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-reply"></i>
                <div class="stat-number">${stats.responded}</div>
                <div class="stat-label">Direspon</div>
            </div>
        `;
    }

    async function loadFeedback(filter = 'all') {
        try {
            let url = '/api/feedback';
            if (filter !== 'all') {
                url += '?status=' + filter;
            }
            
            const response = await fetch(url);
            const data = await response.json();
            
            if (data.success) {
                displayFeedback(data.data);
            } else {
                showNotification('Gagal memuat feedback', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan', 'error');
        }
    }

    function displayFeedback(feedbacks) {
        const grid = document.getElementById('feedbackGrid');
        
        if (feedbacks.length === 0) {
            grid.innerHTML = `
                <div style="grid-column: 1/-1; text-align: center; padding: 50px; background: white; border-radius: 15px;">
                    <i class="fas fa-inbox" style="font-size: 50px; color: #ccc; margin-bottom: 15px;"></i>
                    <p style="color: #999;">Belum ada feedback</p>
                </div>
            `;
            return;
        }

        grid.innerHTML = feedbacks.map(feedback => `
            <div class="feedback-card" data-id="${feedback.id}" data-status="${feedback.status}">
                <div class="feedback-header">
                    <div class="feedback-date">
                        <i class="far fa-calendar-alt"></i>
                        ${new Date(feedback.created_at).toLocaleDateString('id-ID', {
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        })}
                    </div>
                    <span class="status-badge ${feedback.status}">
                        ${feedback.status === 'pending' ? 'Menunggu' : 
                          feedback.status === 'read' ? 'Dibaca' : 'Direspon'}
                    </span>
                </div>
                
                <div class="feedback-content">
                    <p>${feedback.feedback_text}</p>
                </div>

                ${feedback.admin_response ? `
                    <div class="admin-response-section">
                        <h4><i class="fas fa-reply"></i> Respons Admin:</h4>
                        <div class="admin-response-text">
                            ${feedback.admin_response}
                        </div>
                        <div class="response-date">
                            <i class="far fa-clock"></i>
                            Direspon: ${new Date(feedback.responded_at).toLocaleDateString('id-ID', {
                                day: 'numeric',
                                month: 'long',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            })}
                        </div>
                    </div>
                ` : feedback.status !== 'responded' ? `
                    <div class="admin-response-section">
                        <h4><i class="fas fa-pen"></i> Berikan Tanggapan:</h4>
                        <textarea class="admin-input" id="response-${feedback.id}" 
                                  placeholder="Tulis kritik, saran, atau tanggapan Anda di sini..." rows="3"></textarea>
                        <div class="feedback-actions">
                            <button class="action-btn respond" onclick="submitResponse(${feedback.id})">
                                <i class="fas fa-paper-plane"></i> Kirim Tanggapan
                            </button>
                        </div>
                    </div>
                ` : ''}

                <div class="feedback-actions">
                    ${feedback.status === 'pending' ? `
                        <button class="action-btn read" onclick="markAsRead(${feedback.id})">
                            <i class="fas fa-check"></i> Tandai Dibaca
                        </button>
                    ` : ''}
                    <button class="action-btn delete" onclick="deleteFeedback(${feedback.id})">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
            </div>
        `).join('');
    }

    function filterFeedback(status) {
        currentFilter = status;
        loadFeedback(status);
    }

    async function submitResponse(id) {
        const response = document.getElementById(`response-${id}`).value;
        
        if (!response.trim()) {
            showNotification('Harap isi tanggapan terlebih dahulu', 'error');
            return;
        }

        try {
            const response = await fetch(`/api/feedback/${id}/respond`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    admin_response: response
                })
            });

            const data = await response.json();
            
            if (data.success) {
                showNotification('Tanggapan berhasil dikirim');
                loadFeedback(currentFilter);
                loadStats();
            } else {
                showNotification('Gagal mengirim tanggapan', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan', 'error');
        }
    }

    async function markAsRead(id) {
        try {
            const response = await fetch(`/api/feedback/${id}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    status: 'read'
                })
            });

            const data = await response.json();
            
            if (data.success) {
                showNotification('Status diperbarui');
                loadFeedback(currentFilter);
                loadStats();
            } else {
                showNotification('Gagal memperbarui status', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan', 'error');
        }
    }

    async function deleteFeedback(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus feedback ini?')) {
            return;
        }

        try {
            const response = await fetch(`/api/feedback/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const data = await response.json();
            
            if (data.success) {
                showNotification('Feedback berhasil dihapus');
                loadFeedback(currentFilter);
                loadStats();
            } else {
                showNotification('Gagal menghapus feedback', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan', 'error');
        }
    }

    // Load initial data
    loadStats();
    loadFeedback();
</script>
