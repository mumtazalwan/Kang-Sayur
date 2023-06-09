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
            $table->longText('alamat');
            $table->double('longitude')->nullable();
            $table->double('latitude')->nullable();
            $table->time('open');
            $table->time('close');
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
