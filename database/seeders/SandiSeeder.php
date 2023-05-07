<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sandi;

class SandiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sandi::create([
            'id' => 1,
            'password'=>'$2y$10$f8mFDbTKxzQo725Xbl7q2OyIq8ZfpmybYdAXQYaV4wtByJrZkyCXy',
            'new_password' => '',
        ]);

        Sandi::create([
            'id' => 2,
            'password'=>'$2y$10$NeYzADmJWfnirKYi3.AW0e/XTW52K.esZiLvosyw5zEe8EmIR2P5W',
            'new_password' => '',
        ]);

        Sandi::create([
            'id' => 3,
            'password'=>'$2y$10$XljUun4ceDiQXDSSbhxBke9yt8SaYmPq4fKO62.mGW7X/30AmnSh6',
            'new_password' => '',
        ]);
    }
}
