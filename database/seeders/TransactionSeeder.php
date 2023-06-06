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
            'payment_method' => "BRIVA",
            'notes' => "tolong lubangi sedikit kantung plastiknya ya min"
        ]);

        Transaction::create([
            'transaction_code' => 1671005688725,
            'user_id' => 1,
            'payment_method' => "BRIVA",
            'notes' => "tolong lubangi sedikit kantung plastiknya ya min"
        ]);

        Transaction::create([
            'transaction_code' => 2571005688725,
            'user_id' => 1,
            'payment_method' => "BRIVA",
            'notes' => "tolong lubangi sedikit kantung plastiknya ya min"
        ]);

        Transaction::create([
            'transaction_code' => 3571005688725,
            'user_id' => 1,
            'payment_method' => "BRIVA",
            'notes' => "tolong lubangi sedikit kantung plastiknya ya min"
        ]);

        Transaction::create([
            'transaction_code' => 4571005688725,
            'user_id' => 1,
            'payment_method' => "BRIVA",
            'notes' => "tolong lubangi sedikit kantung plastiknya ya min"
        ]);
    }
}
