<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Transaction::create([
            'transaction_code' => 1571005688725,
            'user_id' => 1,
            'status' => 'Sudah dibayar',
            'payment_method' => "BRIVA",
            'transaction_token' => '',
            'client_key' => 'SB-Mid-client-h63xQCl1Rfv_0XW0',
            'created_at' => '2023-06-01 00:00:00'
        ]);

        Transaction::create([
            'transaction_code' => 1671005688725,
            'user_id' => 1,
            'status' => 'Sudah dibayar',
            'payment_method' => "BRIVA",
            'transaction_token' => '',
            'client_key' => 'SB-Mid-client-h63xQCl1Rfv_0XW0',
            'created_at' => '2023-06-01 00:00:00'
        ]);

        Transaction::create([
            'transaction_code' => 2571005688725,
            'user_id' => 1,
            'status' => 'Sudah dibayar',
            'payment_method' => "BRIVA",
            'transaction_token' => '',
            'client_key' => 'SB-Mid-client-h63xQCl1Rfv_0XW0',
            'created_at' => '2023-06-02 00:00:00'
        ]);

        Transaction::create([
            'transaction_code' => 3571005688725,
            'user_id' => 1,
            'status' => 'Sudah dibayar',
            'payment_method' => "BRIVA",
            'transaction_token' => '',
            'client_key' => 'SB-Mid-client-h63xQCl1Rfv_0XW0',
            'created_at' => '2023-06-03 00:00:00'
        ]);

        Transaction::create([
            'transaction_code' => 4571005688725,
            'user_id' => 1,
            'status' => 'Sudah dibayar',
            'payment_method' => "BRIVA",
            'transaction_token' => '',
            'client_key' => 'SB-Mid-client-h63xQCl1Rfv_0XW0',
            'created_at' => '2023-06-04 00:00:00'
        ]);

        Transaction::create([
            'transaction_code' => 5571005688725,
            'user_id' => 1,
            'status' => 'Sudah dibayar',
            'payment_method' => "BRIVA",
            'transaction_token' => '',
            'client_key' => 'SB-Mid-client-h63xQCl1Rfv_0XW0',
            'created_at' => '2023-03-04 00:00:00'
        ]);

        Transaction::create([
            'transaction_code' => 6571005688725,
            'user_id' => 1,
            'status' => 'Sudah dibayar',
            'payment_method' => "BRIVA",
            'transaction_token' => '',
            'client_key' => 'SB-Mid-client-h63xQCl1Rfv_0XW0',
            'created_at' => '2023-01-04 00:00:00'
        ]);
    }
}
