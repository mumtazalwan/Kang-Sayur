<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Produk;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Produk::create([
            'nama_produk' => 'Selada Hijau',
            'rating' => 4.5,
            'kategori_id' => 2,
            'toko_id' => 1,
            'ulasan_id' => 2,
            'is_onsale' => 1
        ]);

        Produk::create([
            'nama_produk' => 'Selada Muda',
            'rating' => 4.5,
            'kategori_id' => 2,
            'toko_id' => 1,
            'ulasan_id' => 1,
            'is_onsale' => 1
        ]);

        Produk::create([
            'nama_produk' => 'Selada Merah',
            'rating' => 4.5,
            'kategori_id' => 2,
            'toko_id' => 1,
            'ulasan_id' => 1,
            'is_onsale' => 1
        ]);

        Produk::create([
            'nama_produk' => 'Sawi Muda',
            'rating' => 4.5,
            'kategori_id' => 1,
            'toko_id' => 1,
            'ulasan_id' => 1,
            'is_onsale' => 1
        ]);

        Produk::create([
            'nama_produk' => 'Timun Suri',
            'rating' => 4.5,
            'kategori_id' => 1,
            'toko_id' => 1,
            'ulasan_id' => 1,
            'is_onsale' => 1
        ]);

        Produk::create([
            'nama_produk' => 'Bebek Jepang',
            'rating' => 4.5,
            'kategori_id' => 1,
            'toko_id' => 2,
            'ulasan_id' => 1,
            'is_onsale' => 1
        ]);

        Produk::create([
            'nama_produk' => 'Bebek Negri',
            'rating' => 4.8,
            'kategori_id' => 1,
            'toko_id' => 2,
            'ulasan_id' => 1,
            'is_onsale' => 1
        ]);

        Produk::create([
            'nama_produk' => 'Selada Hijau Hidroponik',
            'rating' => 4.9,
            'kategori_id' => 2,
            'toko_id' => 3,
            'ulasan_id' => 1,
            'is_onsale' => 1
        ]);

        Produk::create([
            'nama_produk' => 'Bawang merah',
            'rating' => 4.9,
            'kategori_id' => 2,
            'toko_id' => 4,
            'ulasan_id' => 1,
            'is_onsale' => 1
        ]);
    }
}
