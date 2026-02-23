<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stok_darah', function (Blueprint $table) {
            $table->id();
            $table->string('nama_rs');
            $table->string('foto')->nullable();
            $table->integer('stok_a')->default(0);
            $table->integer('stok_b')->default(0);
            $table->integer('stok_ab')->default(0);
            $table->integer('stok_o')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stok_darah');
    }
};