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
            'kategori_img' => 'beras',
            'nama_kategori' => 'Bahan Pokok'
        ]);

        Kategori::create([
            'kategori_img' => 'sayur',
            'nama_kategori' => 'Sayuran'
        ]);

        Kategori::create([
            'kategori_img' => 'buah',
            'nama_kategori' => 'Buah-buahan'
        ]);

        Kategori::create([
            'kategori_img' => 'daging',
            'nama_kategori' => 'Daging'
        ]);

        Kategori::create([
            'kategori_img' => 'unggas',
            'nama_kategori' => 'Unggas'
        ]);

        Kategori::create([
            'kategori_img' => 'telur',
            'nama_kategori' => 'Telur'
        ]);
    }
}
