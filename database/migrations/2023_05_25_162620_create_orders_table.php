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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_code');
            $table->integer('product_id');
            $table->integer('variant_id');
            $table->integer('store_id');
            $table->integer('user_id');
            $table->enum('status', ['Menunggu pembayaran', 'Pesanan', 'Sedang disiapkan', 'Menunggu driver', 'Sedang diantar', 'Selesai'])->default('Menunggu pembayaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
