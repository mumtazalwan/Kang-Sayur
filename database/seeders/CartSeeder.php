<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Cart;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cart::create([
            'user_id' => 1,
            'produk_id' => 1,
            'toko_id' => 1,
            'variant_id' => 2,
            'status' => 'true'
        ]);

        Cart::create([
            'user_id' => 1,
            'produk_id' => 2,
            'toko_id' => 1,
            'variant_id' => 3,
            'status' => 'true'
        ]);

        Cart::create([
            'user_id' => 1,
            'produk_id' => 7,
            'toko_id' => 2,
            'variant_id' => 14,
            'status' => 'true'
        ]);
    }
}
