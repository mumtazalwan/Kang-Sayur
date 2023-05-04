<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

use App\Models\User;


class AssignRoleToUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $findUser = User::findOrFail(1);            
        $role = Role::findOrFail(1);
        
        $findUser->assignRole([$role]);
    }
}
