<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Review;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Review::create([
            'id_user' => 1,
            'rating' => 5,
            'comment' => 'Ini nasi nya enak seperti kenangan dia :’)',
            'product_id' => 1,
            'toko_id' => 1,
            'variant_id' => 1,
            'transaction_code' => 4571005688725
        ]);

        Review::create([
            'id_user' => 3,
            'rating' => 5,
            'comment' => 'Nasi nya pulen banget deh aku suka ><)',
            'product_id' => 1,
            'toko_id' => 1,
            'variant_id' => 1,
            'transaction_code' => 4571005688725
        ]);

        Review::create([
            'id_user' => 3,
            'rating' => 5,
            'comment' => 'Nasi nya pulen banget deh aku suka ><)',
            'product_id' => 1,
            'toko_id' => 1,
            'variant_id' => 2,
            'transaction_code' => 4571005688725
        ]);
    }
}
