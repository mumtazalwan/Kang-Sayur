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
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('img_id')->nullable();
            $table->string('nama_produk');
            $table->double('harga_produk');
            $table->longText('deskripsi');
            $table->integer('stok_produk');
            $table->double('rating')->nullable();
            $table->double('jarak')->nullable();
            $table->integer('kategori_id');
            $table->integer('katalog_id');
            $table->integer('varian_id')->nullable();
            $table->integer('toko_id');
            $table->integer('ulasan_id')->nullable();
            $table->integer('is_onsale')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
