<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Katalog;

class KatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Katalog::create([
            'id_katalog' => 1,
            'id_kategori' => 2,
        ]);

        Katalog::create([
            'id_katalog' => 1,
            'id_kategori' => 3,
        ]);
    }
}
