<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\SaleSession;

class SaleSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SaleSession::create([
            'start' => '10:00:00',
            'end' => '12:00:00'
        ]);

        SaleSession::create([
            'start' => '12:00:00',
            'end' => '14:00:00'
        ]);

        SaleSession::create([
            'start' => '18:00:00',
            'end' => '20:00:00'
        ]);

        SaleSession::create([
            'start' => '20:00:00',
            'end' => '22:00:00'
        ]);
    }
}
