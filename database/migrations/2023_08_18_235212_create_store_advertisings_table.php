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
        Schema::create('store_advertisings', function (Blueprint $table) {
            $table->id();
            $table->integer('toko_id');
            $table->string('img_pamflet');
            $table->timestamp('expire_through');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_advertisings');
    }
};
