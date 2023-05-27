<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Status;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Status::create([
            'produk_id' => 1,
            'toko_id' => 1,
            'status' => 'Accepted'
        ]);

        Status::create([
            'produk_id' => 2,
            'toko_id' => 1,
            'status' => 'Accepted'
        ]);

        Status::create([
            'produk_id' => 3,
            'toko_id' => 1,
            'status' => 'Accepted'
        ]);

        Status::create([
            'produk_id' => 4,
            'toko_id' => 1,
            'status' => 'Accepted'
        ]);

        Status::create([
            'produk_id' => 5,
            'toko_id' => 1,
            'status' => 'Accepted'
        ]);

        Status::create([
            'produk_id' => 6,
            'toko_id' => 1,
            'status' => 'Accepted'
        ]);

        Status::create([
            'produk_id' => 7,
            'toko_id' => 2,
            'status' => 'Accepted'
        ]);
    }
}
