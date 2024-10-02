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
        // Seeder untuk pengguna dengan role 'admin'
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'role' => 'admin'
        ]);

        // Seeder untuk pengguna dengan role 'user'
        User::factory()->create([
            'name' => 'Regular User',   
            'email' => 'user@gmail.com',
            'password' => Hash::make('user'),
            'role' => 'user'
        ]);

        // Seeder untuk pengguna dengan role 'bendahara'
        User::factory()->create([
            'name' => 'Bendahara User',
            'email' => 'bendahara@gmail.com',
            'password' => Hash::make('bendahara'),
            'role' => 'bendahara'
        ]);
    }
}
