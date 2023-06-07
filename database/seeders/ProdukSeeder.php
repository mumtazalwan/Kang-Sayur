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
            'deskripsi' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
            'rating' => 4.5,
            'kategori_id' => 2,
            'varian_id' => 1,
            'toko_id' => 1,
            'ulasan_id' => 2,
            'is_onsale' => 1
        ]);

        Produk::create([
            'nama_produk' => 'Selada Muda',
            'deskripsi' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
            'rating' => 4.5,
            'kategori_id' => 2,
            'varian_id' => 1,
            'toko_id' => 1,
            'ulasan_id' => 1,
            'is_onsale' => 1
        ]);

        Produk::create([
            'nama_produk' => 'Selada Merah',
            'deskripsi' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
            'rating' => 4.5,
            'kategori_id' => 2,
            'varian_id' => 1,
            'toko_id' => 1,
            'ulasan_id' => 1,
            'is_onsale' => 1
        ]);

        Produk::create([
            'nama_produk' => 'Sawi Muda',
            'deskripsi' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
            'rating' => 4.5,
            'kategori_id' => 1,
            'varian_id' => 1,
            'toko_id' => 1,
            'ulasan_id' => 1,
            'is_onsale' => 1
        ]);

        Produk::create([
            'nama_produk' => 'Timun Suri',
            'deskripsi' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
            'rating' => 4.5,
            'kategori_id' => 1,
            'varian_id' => 1,
            'toko_id' => 1,
            'ulasan_id' => 1,
            'is_onsale' => 1
        ]);

        Produk::create([
            'nama_produk' => 'Bebek Jepang',
            'deskripsi' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
            'rating' => 4.5,
            'kategori_id' => 1,
            'varian_id' => 1,
            'toko_id' => 2,
            'ulasan_id' => 1,
            'is_onsale' => 1
        ]);

        Produk::create([
            'nama_produk' => 'Bebek Negri',
            'deskripsi' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
            'rating' => 4.8,
            'kategori_id' => 1,
            'varian_id' => 1,
            'toko_id' => 2,
            'ulasan_id' => 1,
            'is_onsale' => 1
        ]);

        Produk::create([
            'nama_produk' => 'Selada Hijau Hidroponik',
            'deskripsi' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
            'rating' => 4.9,
            'kategori_id' => 2,
            'varian_id' => 1,
            'toko_id' => 3,
            'ulasan_id' => 1,
            'is_onsale' => 1
        ]);

        Produk::create([
            'nama_produk' => 'Bawang merah',
            'deskripsi' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
            'rating' => 4.9,
            'kategori_id' => 2,
            'varian_id' => 1,
            'toko_id' => 4,
            'ulasan_id' => 1,
            'is_onsale' => 1
        ]);
    }
}
