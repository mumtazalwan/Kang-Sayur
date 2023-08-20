<?php

namespace Database\Seeders;

use App\Models\Message;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Message::create([
            'conversation_id' => 1,
            'user_id' => 1,
            'message' => "Hallow Pak Kwan, apakah bebek masih ada?"
        ]);

        Message::create([
            'conversation_id' => 1,
            'user_id' => 1,
            'message' => "Kalo ada saya ingin pesan, namun sebelum itu saya ingin bertanya untuk kondisi bebeknya terlebih dahulu."
        ]);
    }
}
