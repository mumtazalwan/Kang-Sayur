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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('photo')->default("");
            $table->string('email')->unique();
            $table->bigInteger('phone_number')->default("");
            $table->timestamp('email_verified_at')->default("");
            $table->unsignedBigInteger('sandi_id');
            $table->integer('jenis_kelamin')->default(1);
            $table->timestamp('tanggal_lahir')->default("");
            $table->string('address')->nullable();
            $table->double('longitude')->nullable();
            $table->double('latitude')->nullable();
            $table->rememberToken();
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
