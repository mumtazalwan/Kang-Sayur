<?php

namespace Database\Seeders;

use App\Models\Address;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Address::create([
            'user_id' => 1,
            'nama_penerima' => "alwan",
            'nomor_hp' => 81298699106,
            'alamat_lengkap' => "Jl.Raya asjkasjkasjakjskas wwowowo No.12",
            'longitude' => 110.842843,
            'latitude' => -6.753808,
            'prioritas_alamat' => "Utama",
        ]);
    }
}
