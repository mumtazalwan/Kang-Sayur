<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kendaraans', function (Blueprint $table) {
            $table->id();
            $table->integer('driver_id');
            $table->integer('toko_id');
            $table->bigInteger('noTelfon_cadangan')->nullable();
            $table->enum('jenis_kendaraan', ['Kendaraan bermotor (Roda 2)', 'Mobil boks (pikap)', 'Truk Engkel', 'Truk Pendingin'])->default('Kendaraan bermotor (Roda 2)');
            $table->string('nama_kendaraan');
            $table->string('nomor_polisi');
            $table->string('nomor_rangka');
            $table->string('photo_ktp')->nullable();
            $table->string('photo_kk')->nullable();
            $table->string('photo_kendaraan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kendaraans');
    }
};
