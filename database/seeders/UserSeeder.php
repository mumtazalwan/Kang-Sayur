<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Alwan',
            'photo' => '/images/profile/asa.jpg',
            'email' => 'alwan123@gmail.com',
            'sandi_id' => 1,
            'jenis_kelamin' => 1,
            'tanggal_lahir' => '2006-03-10',
            'longitude' => -6.753808,
            'latitude' => 110.842843
        ]);


        User::create([
            'name' => 'Akbar',
            'email' => 'akbar123@gmail.com',
            'sandi_id' => 2,
            'jenis_kelamin' => 1,
            'tanggal_lahir' => '2006-03-10'
        ]);

        User::create([
            'name' => 'Niebel',
            'email' => 'niebel123@gmail.com',
            'sandi_id' => 3,
            'jenis_kelamin' => 1,
            'tanggal_lahir' => '2005-03-10'
        ]);
    }
}
