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
            'start' => '07:00:00',
            'end' => '08:00:00'
        ]);

        SaleSession::create([
            'start' => '09:00:00',
            'end' => '10:00:00'
        ]);

        SaleSession::create([
            'start' => '11:00:00',
            'end' => '12:00:00'
        ]);

        SaleSession::create([
            'start' => '15:00:00',
            'end' => '16:00:00'
        ]);

        SaleSession::create([
            'start' => '18:00:00',
            'end' => '19:00:00'
        ]);
    }
}
