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
        Schema::create('permintaan_darurats', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique()->nullable();
            $table->string('nama_pasien');
            $table->integer('usia');
            $table->enum('gender', ['Laki-laki', 'Perempuan']);
            $table->text('diagnosis')->nullable();
            $table->string('golongan_darah', 8); // A+, A-, B+, B-, AB+, AB-, O+, O-
            $table->integer('jumlah');
            $table->datetime('deadline');
            $table->enum('status', ['DARURAT', 'NORMAL', 'TERENCANA'])->default('NORMAL');
            $table->enum('status_pemenuhan', ['belum', 'diproses', 'terpenuhi'])->default('belum');
            $table->string('nama_rs');
            $table->text('alamat_rs')->nullable();
            $table->string('kontak', 20);
            $table->string('nama_kontak')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('rumah_sakit_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
            
            // Indexes
            $table->index('kode');
            $table->index('status');
            $table->index('status_pemenuhan');
            $table->index('golongan_darah');
            $table->index('deadline');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_darurats');
    }
};