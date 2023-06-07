<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            UserSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            SandiSeeder::class,
            TokoSeeder::class,
            KategoriSeeder::class,
            AssignRoleToUserSeeder::class,
            ProdukSeeder::class,
            LogVisitortSeeder::class,
            ReviewSeeder::class,
            LikeCommentSeeder::class,
            SaleSeeder::class,
            SaleSessionSeeder::class,
            StatusSeeder::class,
            CartSeeder::class,
            OrderSeeder::class,
            TransactionSeeder::class,
            VariantSeeder::class
        ]);
    }
}
