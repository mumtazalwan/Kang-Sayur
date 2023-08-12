<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Sale;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sale::create([
            'session_id' => 1,
            'produk_id' => 1,
            'variant_id' => 2,
            'harga_sale' => 10000,
            'stok' => 50
        ]);

        Sale::create([
            'session_id' => 2,
            'produk_id' => 2,
            'variant_id' => 3,
            'harga_sale' => 2000,
            'stok' => 60
        ]);

        Sale::create([
            'session_id' => 3,
            'produk_id' => 3,
            'variant_id' => 6,
            'harga_sale' => 14500,
            'stok' => 15
        ]);

        Sale::create([
            'session_id' => 4,
            'produk_id' => 4,
            'variant_id' => 8,
            'harga_sale' => 1000,
            'stok' => 50
        ]);

        Sale::create([
            'session_id' => 5,
            'produk_id' => 6,
            'variant_id' => 11,
            'harga_sale' => 27500,
            'stok' => 50
        ]);
    }
}
