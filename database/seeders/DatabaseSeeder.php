<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Akun Admin
        User::updateOrCreate(
            ['email' => 'admin@laundry.com'],
            [
                'name' => 'Administrator Laundry',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Akun Kasir
        User::updateOrCreate(
            ['email' => 'kasir@laundry.com'],
            [
                'name' => 'Kasir Laundry',
                'password' => Hash::make('password'),
                'role' => 'kasir',
            ]
        );

        // Seeder Layanan
        $this->call([
            LayananSeeder::class,
            TransaksiSeeder::class,
        ]);
    }
}
