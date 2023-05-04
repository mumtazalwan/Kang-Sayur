<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Kategori::create([
            'nama_kategori' => 'Bahan Pokok'
        ]);

        Kategori::create([
            'nama_kategori' => 'Sayuran'
        ]);

        Kategori::create([
            'nama_kategori' => 'Buah-buahan'
        ]);

        Kategori::create([
            'nama_kategori' => 'Daging'
        ]);

        Kategori::create([
            'nama_kategori' => 'Unggas'
        ]);

        Kategori::create([
            'nama_kategori' => 'Telur'
        ]);
    }
}
