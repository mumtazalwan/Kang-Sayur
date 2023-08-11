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
            'status' => 'Accepted',
            'created_at' => '0000-00-00 00:00:00',
            'updated_at' => '0000-00-00 00:00:00',
        ]);

        Status::create([
            'produk_id' => 2,
            'toko_id' => 1,
            'status' => 'Accepted',
            'created_at' => '0000-00-00 00:00:00',
            'updated_at' => '0000-00-00 00:00:00',
        ]);

        Status::create([
            'produk_id' => 3,
            'toko_id' => 1,
            'status' => 'Accepted',
            'created_at' => '0000-00-00 00:00:00',
            'updated_at' => '0000-00-00 00:00:00',
        ]);

        Status::create([
            'produk_id' => 4,
            'toko_id' => 1,
            'status' => 'Accepted',
            'created_at' => '0000-00-00 00:00:00',
            'updated_at' => '0000-00-00 00:00:00',
        ]);

        Status::create([
            'produk_id' => 5,
            'toko_id' => 1,
            'status' => 'Accepted',
            'created_at' => '0000-00-00 00:00:00',
            'updated_at' => '0000-00-00 00:00:00',
        ]);

        Status::create([
            'produk_id' => 6,
            'toko_id' => 2,
            'status' => 'Accepted',
            'created_at' => '0000-00-00 00:00:00',
            'updated_at' => '0000-00-00 00:00:00',
        ]);

        Status::create([
            'produk_id' => 7,
            'toko_id' => 2,
            'status' => 'Accepted',
            'created_at' => '0000-00-00 00:00:00',
            'updated_at' => '0000-00-00 00:00:00',
        ]);

        Status::create([
            'produk_id' => 8,
            'toko_id' => 3,
            'status' => 'Accepted',
            'created_at' => '0000-00-00 00:00:00',
            'updated_at' => '0000-00-00 00:00:00',
        ]);

        Status::create([
            'produk_id' => 9,
            'toko_id' => 4,
            'status' => 'Accepted',
            'created_at' => '0000-00-00 00:00:00',
            'updated_at' => '0000-00-00 00:00:00',
        ]);
    }
}
