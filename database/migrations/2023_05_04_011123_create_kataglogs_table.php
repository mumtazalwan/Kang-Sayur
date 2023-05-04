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
        Schema::create('katalogs', function (Blueprint $table) {
            $table->unsignedBigInteger('id_katalog')->nullable();
            $table->foreign('id_katalog')->references('katalog_id')->on('tokos');
            $table->unsignedBigInteger('id_kategori')->nullable();
            $table->foreign('id_kategori')->references('id')->on('kategori');
            // $table->unsignedBigInteger('barang_id')->nullable();
            // $table->foreign('barang_id')->references('id')->on('katalogs');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('katalogs');
    }
};
