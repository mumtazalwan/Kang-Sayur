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
            'photo' => '/storage/user_profile/alwan.jpg',
            'email' => 'alwan123@gmail.com',
            'sandi_id' => 1,
            'jenis_kelamin' => 1,
            'tanggal_lahir' => '2006-03-10',
            'longitude' => 110.842843,
            'latitude' => -6.753808
        ]);

        User::create([
            'name' => 'Akbar',
            'photo' => '/storage/user_profile/akbar.jpg',
            'email' => 'akbar123@gmail.com',
            'sandi_id' => 2,
            'jenis_kelamin' => 1,
            'tanggal_lahir' => '2006-03-10'
        ]);

        User::create([
            'name' => 'Niebel',
            'photo' => '/storage/user_profile/niebel.jpg',
            'email' => 'niebel123@gmail.com',
            'sandi_id' => 3,
            'jenis_kelamin' => 1,
            'tanggal_lahir' => '2005-03-10'
        ]);

        User::create([
            'name' => 'Joko',
            'photo' => '/storage/user_profile/1684847191asa.jpg',
            'email' => 'joko123@gmail.com',
            'sandi_id' => 2,
            'jenis_kelamin' => 1,
            'tanggal_lahir' => '2006-03-10'
        ]);

        User::create([
            'name' => 'Nunik',
            'photo' => '/storage/user_profile/1684847191asa.jpg',
            'email' => 'nunik123@gmail.com',
            'sandi_id' => 2,
            'jenis_kelamin' => 1,
            'tanggal_lahir' => '2006-03-10'
        ]);
    }
}
