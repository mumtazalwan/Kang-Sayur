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
        Schema::create('tokos', function (Blueprint $table) {
            $table->id();
            $table->string('img_profile')->nullable();
            $table->string('img_header')->nullable();
            $table->string('nama_toko');
            $table->longText('deskripsi');
            $table->unsignedBigInteger('seller_id')->nullable();
            $table->foreign('seller_id')->references('id')->on('tokos');
            $table->longText('alamat');
            $table->string('location');
            $table->time('open');
            $table->time('close');
            $table->unsignedBigInteger('catalogue_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tokos');
    }
};
