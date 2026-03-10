<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_darah', function (Blueprint $table) {
            $table->id();
            $table->string('nama_rs');
            $table->string('foto')->nullable();
            
            // Golongan A
            $table->integer('stok_a_plus')->default(0);
            $table->integer('stok_a_minus')->default(0);
            
            // Golongan B
            $table->integer('stok_b_plus')->default(0);
            $table->integer('stok_b_minus')->default(0);
            
            // Golongan AB
            $table->integer('stok_ab_plus')->default(0);
            $table->integer('stok_ab_minus')->default(0);
            
            // Golongan O
            $table->integer('stok_o_plus')->default(0);
            $table->integer('stok_o_minus')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Index untuk optimasi query
            $table->index('nama_rs');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_darah');
    }
};