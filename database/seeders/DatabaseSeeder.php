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
        \App\Models\productattribut::factory(10)->recycle(
            [
                \App\Models\Product::factory(100)->recycle([
                    \App\Models\Categori::factory(5)->create(),
                    \App\Models\Supplier::factory(10)->create(),
                    \App\Models\StockTransaction::factory(25)->recycle(
                        \App\Models\User::factory(10)->create()
                    )->create()
                ])->create()
            ]
        )->create();

        \App\Models\User::factory([
            'name' => 'Rafael Theo Santoso',
            'email' => 'theo@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'Admin',
        ])->create();

        \App\Models\User::factory([
            'name' => 'Rafael Theo Santoso',
            'email' => 'rafael@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'Manager Gudang',
        ])->create();

        \App\Models\User::factory([
            'name' => 'Rafael Theo Santoso',
            'email' => 'santoso@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'Staff Gudang',
        ])->create();
    }
}
