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
            'transaction_code' => 8803072131833,
            'store_id' => 1,
            'user_id' => 1,
            'payment_method' => 'Gopay',
            'delivery_time' => "2006-03-10 00:00:00"
        ]);

        Transaction::create([
            'transaction_code' => 8979220193691,
            'store_id' => 1,
            'user_id' => 1,
            'payment_method' => 'Gopay',
            'delivery_time' => "2006-03-10 00:00:00"
        ]);
    }
}
