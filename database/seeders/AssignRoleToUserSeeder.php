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
        $findUser2 = User::findOrFail(2);
        $findUser3 = User::findOrFail(3); 
                  
        $role = Role::findOrFail(1);
        $role2 = Role::findOrFail(2);
        
        $findUser->assignRole([$role]);
        $findUser2->assignRole([$role2]);
        $findUser3->assignRole([$role]);
    }
}
