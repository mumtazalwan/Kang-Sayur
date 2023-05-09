<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Catalogue;

class CatalogueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Catalogue::create([
            'toko_id' => 1,
            'kategori_id' => 1
        ]);

        Catalogue::create([
            'toko_id' => 1,
            'kategori_id' => 2
        ]);

        Catalogue::create([
            'toko_id' => 1,
            'kategori_id' => 3
        ]);
    }
}
