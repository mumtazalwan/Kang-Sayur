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
            'session_id' => 4,
            'produk_id' =>1
        ]);

        Sale::create([
            'session_id' => 4,
            'produk_id' =>2
        ]);

        Sale::create([
            'session_id' => 2,
            'produk_id' => 3
        ]);

        Sale::create([
            'session_id' => 3,
            'produk_id' => 4
        ]);
    }
}
