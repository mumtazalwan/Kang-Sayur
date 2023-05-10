<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\LikeComment;

class LikeCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LikeComment::create([
            'review_id' => 1,
            'user_id' => 1
        ]);

        LikeComment::create([
            'review_id' => 1,
            'user_id' => 2
        ]);

        LikeComment::create([
            'review_id' => 1,
            'user_id' => 3
        ]);
    }
}
