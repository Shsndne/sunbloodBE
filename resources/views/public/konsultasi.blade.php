<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konsultasi - Sunblood</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/consultation.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="{{ route('home') }}" class="nav-logo"><i class="fas fa-tint"></i> Sun<span>Blood</span></a>
            <ul class="nav-menu" id="navMenu">
                <li><a href="{{ route('konsultasi') }}" class="nav-link active">Konsultasi</a></li>
                <li><a href="{{ route('stok-darah') }}" class="nav-link">Ketersediaan Darah</a></li>
                <li><a href="{{ route('darurat') }}" class="nav-link nav-emergency">🆘 Darurat</a></li>
                <li><a href="{{ route('feedback.page') }}" class="nav-link">Feedback</a></li>
            </ul>
            <button class="nav-toggle" id="navToggle"><i class="fas fa-bars"></i></button>
        </div>
    </nav>

    <main class="page-content">
        <div class="container">
            <div class="page-header">
                <h1><i class="fas fa-comments"></i> Konsultasi Donor Darah</h1>
                <p>Tanyakan apa saja seputar donor darah kepada asisten kami</p>
            </div>

            <div class="chatbot-wrapper">
                <div class="chatbot-container">
                    <div class="chat-header">
                        <div class="bot-avatar"><i class="fas fa-robot"></i></div>
                        <div class="bot-info">
                            <strong>Asisten Sunblood</strong>
                            <span class="bot-status"><span class="dot"></span> Online</span>
                        </div>
                    </div>

                    <div class="chat-messages" id="chatMessages">
                        <div class="message bot-message">
                            <div class="message-avatar"><i class="fas fa-robot"></i></div>
                            <div class="message-content">
                                Halo! Saya asisten Sunblood 👋<br>
                                Saya siap membantu Anda dengan informasi seputar <strong>donor darah</strong>.<br><br>
                                Anda bisa bertanya tentang:
                                <ul>
                                    <li>Syarat dan ketentuan donor</li>
                                    <li>Golongan darah & kompatibilitas</li>
                                    <li>Kondisi kesehatan yang diperbolehkan</li>
                                    <li>Manfaat dan prosedur donor</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="quick-replies" id="quickReplies">
                        <button onclick="sendQuick('Apa syarat donor darah?')">Syarat donor darah</button>
                        <button onclick="sendQuick('Golongan darah apa yang paling langka?')">Golongan langka</button>
                        <button onclick="sendQuick('Berapa sering boleh donor darah?')">Frekuensi donor</button>
                        <button onclick="sendQuick('Apakah donor darah menyakitkan?')">Apakah sakit?</button>
                    </div>

                    <div class="chat-input-area">
                        <input type="text" id="chatInput" placeholder="Ketik pertanyaan Anda..." autocomplete="off">
                        <button id="sendBtn" onclick="sendMessage()">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer"><div class="container"><p>© {{ date('Y') }} SunBlood</p></div></footer>

    <script src="{{ asset('assets/js/consultation.js') }}"></script>
    <script src="{{ asset('assets/js/navbar.js') }}"></script>
</body>
</html>