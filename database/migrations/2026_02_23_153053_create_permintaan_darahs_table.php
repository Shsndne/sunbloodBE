<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('permintaan_darahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rumah_sakit_id')->constrained('rumah_sakits')->onDelete('cascade');
            $table->enum('golongan_darah', ['A', 'B', 'AB', 'O']);
            $table->integer('jumlah_kantong');
            $table->enum('status', ['darurat', 'biasa'])->default('biasa');
            $table->enum('status_pemenuhan', ['belum', 'diproses', 'terpenuhi'])->default('belum');
            $table->text('keterangan')->nullable();
            $table->date('tanggal_dibutuhkan');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('permintaan_darahs');
    }
};