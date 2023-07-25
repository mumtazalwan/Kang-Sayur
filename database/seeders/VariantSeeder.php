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
            'variant' => '500gr',
            'variant_img' => '/storage/user_profile/selada500g.jpg',
            'variant_desc' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).',
            'harga_variant' => 8000,
            'stok' => 10
        ]);

        Variant::create([
            'product_id' => 1,
            'variant' => '800gr',
            'variant_img' => '/storage/user_profile/selada800g.jpg',
            'variant_desc' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).',
            'harga_variant' => 12800,
            'stok' => 10
        ]);

        // selada muda
        Variant::create([
            'product_id' => 2,
            'variant' => '500gr',
            'variant_desc' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).',
            'harga_variant' => 5000,
            'stok' => 10
        ]);

        Variant::create([
            'product_id' => 2,
            'variant' => '800gr',
            'variant_desc' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).',
            'harga_variant' => 8000,
            'stok' => 10
        ]);

        // selada merah
        Variant::create([
            'product_id' => 3,
            'variant' => '800gr',
            'variant_img' => '/storage/user_profile/seladamerah500g.jpg',
            'variant_desc' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).',
            'harga_variant' => 15000,
            'stok' => 10
        ]);

        Variant::create([
            'product_id' => 3,
            'variant' => '800gr',
            'variant_img' => '/storage/user_profile/seladamerah800g.jpg',
            'variant_desc' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).',
            'harga_variant' => 20000,
            'stok' => 10
        ]);

        // sawi muda 
        Variant::create([
            'product_id' => 4,
            'variant' => '500gr',
            'variant_img' => '/storage/user_profile/sawi500g.jpg',
            'variant_desc' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).',
            'harga_variant' => 1000,
            'stok' => 10
        ]);

        Variant::create([
            'product_id' => 4,
            'variant' => '800gr',
            'variant_img' => '/storage/user_profile/sawi800g.jpg',
            'variant_desc' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).',
            'harga_variant' => 1600,
            'stok' => 10
        ]);

        // timun suri
        Variant::create([
            'product_id' => 5,
            'variant' => '500gr',
            'variant_img' => '/storage/user_profile/timunsuri500g.jpg',
            'variant_desc' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).',
            'harga_variant' => 15000,
            'stok' => 10
        ]);

        Variant::create([
            'product_id' => 5,
            'variant' => '800gr',
            'variant_img' => '/storage/user_profile/timunsuri800g.jpg',
            'variant_desc' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).',
            'harga_variant' => 20000,
            'stok' => 10
        ]);

        // bebek jepang
        Variant::create([
            'product_id' => 6,
            'variant' => 'Jantan',
            'variant_img' => '/storage/user_profile/bebekjantan.jpg',
            'variant_desc' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).',
            'harga_variant' => 34000,
            'stok' => 10
        ]);

        Variant::create([
            'product_id' => 6,
            'variant' => 'Betina',
            'variant_img' => '/storage/user_profile/bebekbetina.jpg',
            'variant_desc' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).',
            'harga_variant' => 40000,
            'stok' => 10
        ]);

        // bebek negri
        Variant::create([
            'product_id' => 7,
            'variant' => 'Jantan',
            'variant_img' => '/storage/user_profile/bebeknegrijantan.jpg',
            'variant_desc' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).',
            'harga_variant' => 30000,
            'stok' => 10
        ]);

        Variant::create([
            'product_id' => 7,
            'variant' => 'Betina',
            'variant_img' => '/storage/user_profile/bebeknegribetina.jpg',
            'variant_desc' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).',
            'harga_variant' => 45000,
            'stok' => 10
        ]);

        // selada hijau hidroponik
        Variant::create([
            'product_id' => 8,
            'variant' => '500g',
            'variant_img' => '/storage/user_profile/hidroponik500g.jpg',
            'variant_desc' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).',
            'harga_variant' => 30000,
            'stok' => 10
        ]);

        Variant::create([
            'product_id' => 8,
            'variant' => '1kg',
            'variant_img' => '/storage/user_profile/hidroponik1kg.jpg',
            'variant_desc' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).',
            'harga_variant' => 45000,
            'stok' => 10
        ]);

        // bawang merah
        Variant::create([
            'product_id' => 9,
            'variant' => '500g',
            'variant_img' => '/storage/user_profile/bawangmerah800g.jpg',
            'variant_desc' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).',
            'harga_variant' => 25000,
            'stok' => 10
        ]);

        Variant::create([
            'product_id' => 9,
            'variant' => '1kg',
            'variant_img' => '/storage/user_profile/bawangmerah1kg.jpg',
            'variant_desc' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).',
            'harga_variant' => 45000,
            'stok' => 10
        ]);
    }
}
