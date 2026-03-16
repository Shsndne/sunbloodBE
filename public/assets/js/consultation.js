$(document).ready(function () {
  let mode = "";
  console.log("Script consultation.js loaded"); // Debug

  $(".disease-btn").on("click", function () {
    mode = $(this).data("disease");
    console.log("Mode selected:", mode); // Debug
    $("#chat-display").html("");

    if (mode === "darurat") {
      $("#chat-display").append(`
        <div class="message bot">
           <b>Darurat Darah</b><br>
          Apakah kondisi pasien darurat? (Ya / Tidak)
        </div>
      `);
    }

    if (mode === "kecocokan") {
      $("#chat-display").append(`
        <div class="message bot">
           <b>Cek Kecocokan Darah</b><br>
          Masukkan golongan darah pasien (contoh: A+, O-, AB+)
        </div>
      `);
    }

    if (mode === "transfusi") {
      $("#chat-display").append(`
        <div class="message bot">
           <b>Info Transfusi</b><br>
          Ketik salah satu:<br>
          • tanda transfusi<br>
          • persiapan transfusi<br>
          • risiko transfusi
        </div>
      `);
    }
    
    // Scroll ke bawah
    $("#chat-display").scrollTop($("#chat-display")[0].scrollHeight);
  });

  $("#send-btn").on("click", sendMessage);
  $("#user-input").keypress(function (e) {
    if (e.which === 13) sendMessage();
  });

  function sendMessage() {
    let userMsg = $("#user-input").val().trim();
    if (userMsg === "") return;

    $("#chat-display").append(`
      <div class="message user">${userMsg}</div>
    `);
    $("#user-input").val("");

    // Clear placeholder
    $(".placeholder").remove();

    setTimeout(() => {
      handleBotResponse(userMsg);
      $("#chat-display").scrollTop($("#chat-display")[0].scrollHeight);
    }, 600);
  }

  function handleBotResponse(userMsg) {
    console.log("Handling response for mode:", mode, "message:", userMsg); // Debug
    let response = "";

    // MODE DARURAT
    if (mode === "darurat") {
      if (userMsg.toLowerCase() === "ya") {
        response = `
           <b>Kondisi Darurat</b><br>
          Segera menuju rumah sakit terdekat atau hubungi 118!<br>
          Silakan lanjutkan dengan mengecek <b>ketersediaan darah</b> sesuai golongan pasien.
        `;
      } else if (userMsg.toLowerCase() === "tidak") {
        response = `
          Jika tidak darurat, Anda dapat melakukan pengecekan darah secara terjadwal di puskesmas atau rumah sakit.
        `;
      } else {
        response = "Silakan jawab: <b>Ya</b> atau <b>Tidak</b>";
      }
    }

    // MODE KECOCOKAN DARAH
    if (mode === "kecocokan") {
      const darah = userMsg.toUpperCase().trim();
      const map = {
        "A+": "A+, A-, O+, O-",
        "A-": "A-, O-",
        "B+": "B+, B-, O+, O-",
        "B-": "B-, O-",
        "AB+": "Semua golongan darah",
        "AB-": "AB-, A-, B-, O-",
        "O+": "O+, O-",
        "O-": "O- saja"
      };

      if (map[darah]) {
        response = `
           <b>Kecocokan Golongan Darah ${darah}</b><br>
          Pasien dengan golongan darah <b>${darah}</b> dapat menerima dari:<br>
          <b>${map[darah]}</b>
        `;
      } else {
        response = "Format golongan darah tidak dikenali. Contoh: A+, O-, AB+";
      }
    }

    // MODE INFO TRANSFUSI
    if (mode === "transfusi") {
      const msg = userMsg.toLowerCase();
      if (msg.includes("tanda")) {
        response = `
           <b>Tanda Membutuhkan Transfusi Darah:</b><br>
          • Wajah dan tubuh pucat<br>
          • Lemas berlebihan<br>
          • Sesak napas<br>
          • Denyut jantung cepat<br>
          • Kadar hemoglobin rendah (< 8 g/dL)<br>
          • Perdarahan hebat pasca operasi atau kecelakaan
        `;
      } else if (msg.includes("persiapan")) {
        response = `
           <b>Persiapan Sebelum Transfusi:</b><br>
          1. Cek golongan darah dan crossmatch<br>
          2. Pemeriksaan kesehatan umum<br>
          3. Tanda tangan persetujuan transfusi<br>
          4. Puasa 4 jam sebelum transfusi (jika diperlukan)<br>
          5. Pastikan pasien dalam kondisi stabil
        `;
      } else if (msg.includes("risiko")) {
        response = `
           <b>Risiko Transfusi Darah:</b><br>
          • Reaksi alergi ringan (gatal, kemerahan)<br>
          • Demam selama/setelah transfusi<br>
          • Ketidakcocokan darah (reaksi hemolitik)<br>
          • Kelebihan cairan (overload)<br>
          • Infeksi (sangat jarang dengan screening modern)
        `;
      } else {
        response = "Silakan ketik salah satu:<br>• <b>tanda transfusi</b><br>• <b>persiapan transfusi</b><br>• <b>risiko transfusi</b>";
      }
    }

    $("#chat-display").append(`
      <div class="message bot">${response}</div>
    `);
  }
});