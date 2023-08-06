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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('nama_penerima');
            $table->bigInteger('nomor_hp')->nullable();
            $table->string('alamat_lengkap')->nullable();
            $table->double('longitude')->nullable();
            $table->double('latitude')->nullable();
            $table->enum('label_alamat', ['Rumah', 'Apartemen', 'Kantor', 'Kos'])->default('Rumah');
            $table->enum('prioritas_alamat', ['Utama', 'Tambahan'])->default('Tambahan');
            $table->text('catatan')->default("");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
