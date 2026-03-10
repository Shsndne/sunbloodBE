<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('permintaan_darah', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel stok_darah (rumah sakit)
            $table->foreignId('rumah_sakit_id')
                  ->constrained('stok_darah')
                  ->onDelete('cascade');
            
            // Golongan darah yang diminta
            $table->enum('golongan_darah', [
                'A+', 'A-', 'B+', 'B-', 
                'AB+', 'AB-', 'O+', 'O-'
            ]);
            
            // Jumlah kantong yang diminta
            $table->integer('jumlah_kantong');
            
            // Tingkat kebutuhan (lebih deskriptif dari 'status')
            $table->enum('tingkat_kebutuhan', [
                'darurat',    // butuh segera (hitungan jam)
                'mendesak',   // butuh cepat (hitungan hari)
                'biasa'       // butuh dalam waktu dekat
            ])->default('biasa');
            
            // Status pemenuhan permintaan
            $table->enum('status_pemenuhan', [
                'belum',      // belum diproses
                'diproses',   // sedang diproses
                'terpenuhi',  // sudah terpenuhi semua
                'sebagian',   // terpenuhi sebagian
                'dibatalkan'  // dibatalkan
            ])->default('belum');
            
            // Informasi tambahan
            $table->text('keterangan')->nullable();
            $table->date('tanggal_dibutuhkan');
            $table->integer('jumlah_terpenuhi')->default(0);
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes(); // Optional: untuk soft delete
            
            // Index untuk optimasi query
            $table->index('rumah_sakit_id');
            $table->index('golongan_darah');
            $table->index('status_pemenuhan');
            $table->index('tingkat_kebutuhan');
            $table->index('tanggal_dibutuhkan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_darah');
    }
};