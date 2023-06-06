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
            'img_profile' => '/storage/user_profile/bujujue.jpg',
            'deskripsi' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industr\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
            'alamat' => 'Jl Jawa XV Bl A/2 Kawasan Industri Cakung,Rorotanu',
            'latitude' => -6.753804,
            'longitude' => 110.843100,
            'open' => '07:00:00',
            'close' => '15:00:00',
        ]);

        Toko::create([
            'nama_toko' => 'Toko Bebek Pak Kwan',
            'img_profile' => '/storage/user_profile/bebekkwan.jpg',
            'deskripsi' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industr\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
            'seller_id' => 2,
            'alamat' => 'Jl Jawa XV Bl A/2 Kawasan Industri Cakung,Rorotanu',
            'latitude' => -6.747032,
            'longitude' => 110.837825,
            'open' => '07:00:00',
            'close' => '15:00:00',
        ]);

        Toko::create([
            'nama_toko' => 'Selada Hidroponik Kudus',
            'img_profile' => '/storage/user_profile/selada.jpg',
            'deskripsi' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industr\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
            'seller_id' => 4,
            'alamat' => 'Langgardalem RT. 01 RW. 02 No. 75, Kec. Kota Kudus, Kabupaten Kudus, Jawa Tengah',
            'latitude' => -6.804048,
            'longitude' => 110.837206,
            'open' => '09:00:00',
            'close' => '17:00:00',
        ]);

        Toko::create([
            'nama_toko' => 'Petani Bawang',
            'img_profile' => '/storage/user_profile/petanibawang.jpg',
            'deskripsi' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industr\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
            'seller_id' => 5,
            'alamat' => '4V7M+5FF, Sidorejo, Wotan, Kec. Sukolilo, Kabupaten Pati, Jawa Tengah 59172',
            'latitude' => -6.887077,
            'longitude' => 110.883697,
            'open' => '00:00:01',
            'close' => '24:00:00',
        ]);
    }
}
