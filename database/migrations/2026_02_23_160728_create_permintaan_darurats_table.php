<?php
// database/migrations/2026_02_23_160728_create_permintaan_darurats_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('permintaan_darurats');

        Schema::create('permintaan_darurats', function (Blueprint $table) {
            $table->id();

            // Nomor Resi (format: SB-YYYYMMDD-XXXXXX)
            $table->string('nomor_resi')->unique()->nullable();

            // Kode lama (backward compat)
            $table->string('kode')->nullable();

            // Data Pasien
            $table->string('nama_pasien');
            $table->integer('usia_pasien')->nullable();
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan'])->nullable();

            // Backward compat
            $table->integer('usia')->nullable();
            $table->string('gender')->nullable();

            // Diagnosis
            $table->text('diagnosis')->nullable();

            // Kebutuhan Darah
            $table->enum('golongan_darah', ['A', 'B', 'AB', 'O']);
            $table->enum('rhesus', ['+', '-'])->nullable();
            $table->integer('jumlah_kantong')->nullable();
            $table->integer('jumlah')->nullable(); // backward compat

            // Jadwal
            $table->date('tanggal_dibutuhkan')->nullable();
            $table->timestamp('deadline')->nullable(); // backward compat

            // Urgensi
            $table->enum('tingkat_urgensi', ['darurat', 'normal', 'terjadwal'])->default('normal');

            // Lokasi RS
            $table->string('nama_rumah_sakit')->nullable();
            $table->text('alamat_lengkap')->nullable();
            $table->string('nama_rs')->nullable();   // backward compat
            $table->text('alamat_rs')->nullable();   // backward compat
            $table->unsignedBigInteger('rumah_sakit_id')->nullable();

            // Kontak Darurat
            $table->string('nama_kontak')->nullable();
            $table->string('telepon_kontak', 20)->nullable();
            $table->string('kontak', 20)->nullable(); // backward compat

            // Pernyataan
            $table->boolean('pernyataan_setuju')->default(false);

            // Status
            $table->enum('status', ['menunggu', 'diproses', 'selesai', 'ditolak', 'pending', 'terpenuhi'])
                  ->default('menunggu');
            $table->string('status_pemenuhan')->nullable();

            // Catatan admin
            $table->text('catatan')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('tingkat_urgensi');
            $table->index('golongan_darah');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permintaan_darurats');
    }
};
