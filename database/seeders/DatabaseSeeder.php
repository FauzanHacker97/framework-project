<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Test User
        User::create([
            'name' => 'Test User',
            'email' => 'user@user.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        $this->command->info('Admin created: admin@admin.com / password');
        $this->command->info('Test user created: user@user.com / password');
    }
}