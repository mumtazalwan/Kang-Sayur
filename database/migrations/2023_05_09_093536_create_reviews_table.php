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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            $table->integer('rating');
            $table->string('img_product')->nullable();
            $table->longText('comment')->nullable();
            $table->integer('product_id');
            $table->integer('toko_id');
            $table->integer('variant_id');
            $table->unsignedBigInteger('transaction_code');
            $table->longText('reply')->default("");
            $table->enum('direply', ['true', 'false'])->default('false');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
