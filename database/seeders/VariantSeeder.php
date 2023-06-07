<?php

namespace Database\Seeders;

use App\Models\Variant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // selada hijau
        Variant::create([
            'product_id' => 1,
            'variant' => '500g',
            'harga_variant' => 8000,
            'stok' => 10
        ]);

        Variant::create([
            'product_id' => 1,
            'variant' => '800g',
            'harga_variant' => 12800,
            'stok' => 10
        ]);

        // selada muda
        Variant::create([
            'product_id' => 2,
            'variant' => '500g',
            'harga_variant' => 5000,
            'stok' => 10
        ]);

        Variant::create([
            'product_id' => 2,
            'variant' => '800g',
            'harga_variant' => 8000,
            'stok' => 10
        ]);

        // selada merah
        Variant::create([
            'product_id' => 3,
            'variant' => '1kg',
            'harga_variant' => 15000,
            'stok' => 10
        ]);

        Variant::create([
            'product_id' => 3,
            'variant' => '1,5kg',
            'harga_variant' => 20000,
            'stok' => 10
        ]);

        // sawi muda 
        Variant::create([
            'product_id' => 4,
            'variant' => '500g',
            'harga_variant' => 1000,
            'stok' => 10
        ]);

        Variant::create([
            'product_id' => 4,
            'variant' => '800g',
            'harga_variant' => 1600,
            'stok' => 10
        ]);

        // timun suri
        Variant::create([
            'product_id' => 5,
            'variant' => '500g',
            'harga_variant' => 15000,
            'stok' => 10
        ]);

        Variant::create([
            'product_id' => 5,
            'variant' => '800g',
            'harga_variant' => 20000,
            'stok' => 10
        ]);

        // bebek jepang
        Variant::create([
            'product_id' => 6,
            'variant' => 'Jantan',
            'harga_variant' => 34000,
            'stok' => 10
        ]);

        Variant::create([
            'product_id' => 6,
            'variant' => 'Betina',
            'harga_variant' => 40000,
            'stok' => 10
        ]);

        // bebek negri
        Variant::create([
            'product_id' => 7,
            'variant' => 'Jantan',
            'harga_variant' => 30000,
            'stok' => 10
        ]);

        Variant::create([
            'product_id' => 7,
            'variant' => 'Betina',
            'harga_variant' => 45000,
            'stok' => 10
        ]);

        // selada hijau hidroponik
        Variant::create([
            'product_id' => 8,
            'variant' => '500g',
            'harga_variant' => 30000,
            'stok' => 10
        ]);

        Variant::create([
            'product_id' => 8,
            'variant' => '1kg',
            'harga_variant' => 45000,
            'stok' => 10
        ]);

        // bawang merah
        Variant::create([
            'product_id' => 9,
            'variant' => '500g',
            'harga_variant' => 25000,
            'stok' => 10
        ]);

        Variant::create([
            'product_id' => 9,
            'variant' => '1kg',
            'harga_variant' => 45000,
            'stok' => 10
        ]);
    }
}
