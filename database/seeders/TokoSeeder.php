<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Toko;

class TokoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Toko::create([
            'nama_toko' => 'Toko Sayuer Bu Juju',
            'deskripsi' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industr\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
            'alamat' => 'Jl Jawa XV Bl A/2 Kawasan Industri Cakung,Rorotanu',
            'longitude' => -6.753804,
            'latitude' => 110.843100,
            'open' => '07:00:00',
            'close' => '15:00:00',
        ]);

        Toko::create([
            'nama_toko' => 'Toko Bebek Pak Kwan',
            'deskripsi' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industr\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
            'alamat' => 'Jl Jawa XV Bl A/2 Kawasan Industri Cakung,Rorotanu',
            'longitude' => -6.747032,
            'latitude' => 110.837825,
            'open' => '07:00:00',
            'close' => '15:00:00',
        ]);
    }
}
