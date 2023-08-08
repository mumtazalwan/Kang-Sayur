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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('photo')->default("");
            $table->string('email')->unique();
            $table->bigInteger('phone_number')->default("000000000000");
            $table->timestamp('email_verified_at')->default("0000-00-00 00:00:00");
            $table->unsignedBigInteger('sandi_id');
            $table->integer('jenis_kelamin')->default(1);
            $table->timestamp('tanggal_lahir')->default("0000-00-00 00:00:00");
            $table->string('address')->default("");
            $table->double('longitude')->nullable();
            $table->double('latitude')->nullable();
            $table->rememberToken()->default("");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
