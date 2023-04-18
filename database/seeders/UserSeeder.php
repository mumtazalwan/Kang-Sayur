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
            'name'=>'Alwan',
            'email'=>'alwan123@gmail.com',
            'password'=>'$2y$10$SNirCdtPnpuUbhWpXm1nyeHATXkzwVbPOYxmXSQ6okjVdeG.ppkHq'
        ]);

        User::create([
            'name'=>'Akbar',
            'email'=>'akbar123@gmail.com',
            'password'=>'$2y$10$NeYzADmJWfnirKYi3.AW0e/XTW52K.esZiLvosyw5zEe8EmIR2P5W'
        ]);

        User::create([
            'name'=>'Niebel',
            'email'=>'niebel123@gmail.com',
            'password'=>'$2y$10$NeYzADmJWfnirKYi3.AW0e/XTW52K.esZiLvosyw5zEe8EmIR2P5W'
        ]);
    }
}
