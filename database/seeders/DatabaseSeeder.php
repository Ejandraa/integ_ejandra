<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin User', 'password' => 'password123', 'role' => 'admin']
        );
    
        User::updateOrCreate(
            ['email' => 'user@example.com'],
            ['name' => 'Regular User', 'password' => 'password123', 'role' => 'user']
        );
    }
}
