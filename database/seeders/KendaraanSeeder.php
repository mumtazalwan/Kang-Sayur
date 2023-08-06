<?php

namespace Database\Seeders;

use App\Models\Kendaraan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KendaraanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Kendaraan::create([
            'driver_id' => 3,
            'toko_id' => 2,
            'noTelfon_cadangan' => 81298199012,
            'jenis_kendaraan' => 'Kendaraan bermotor (Roda 2)',
            'nomor_polisi' => 'B8220B',
            'nomor_rangka' => 'AEZ4KM1',
            'photo_ktp' => 'kosong',
            'photo_kk' => 'kosong',
            'photo_kendaraan' => 'kosong'
        ]);
    }
}
