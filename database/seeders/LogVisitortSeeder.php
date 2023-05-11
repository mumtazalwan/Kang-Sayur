<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\LogVisitor;

class LogVisitortSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LogVisitor::create([
            'product_id' => 1,
            'user_id' => 1
        ]);
        LogVisitor::create([
            'product_id' => 1,
            'user_id' => 1
        ]);
        LogVisitor::create([
            'product_id' => 1,
            'user_id' => 1
        ]);
        LogVisitor::create([
            'product_id' => 1,
            'user_id' => 2
        ]);
        LogVisitor::create([
            'product_id' => 1,
            'user_id' => 1
        ]);
        LogVisitor::create([
            'product_id' => 1,
            'user_id' => 1
        ]);
        LogVisitor::create([
            'product_id' => 1,
            'user_id' => 1
        ]);
        LogVisitor::create([
            'product_id' => 1,
            'user_id' => 2
        ]);





        LogVisitor::create([
            'product_id' => 2,
            'user_id' => 1
        ]);
        LogVisitor::create([
            'product_id' => 2,
            'user_id' => 1
        ]);
        LogVisitor::create([
            'product_id' => 2,
            'user_id' => 1
        ]);
        LogVisitor::create([
            'product_id' => 2,
            'user_id' => 1
        ]);
        
        LogVisitor::create([
            'product_id' => 2,
            'user_id' => 2
        ]);
        LogVisitor::create([
            'product_id' => 2,
            'user_id' => 2
        ]);

        LogVisitor::create([
            'product_id' => 3,
            'user_id' => 2
        ]);

        LogVisitor::create([
            'product_id' => 4,
            'user_id' => 2
        ]);

        LogVisitor::create([
            'product_id' => 4,
            'user_id' => 2
        ]);
    }
}
