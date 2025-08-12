<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Manager user
        User::create([
            'name' => 'Manager System',
            'email' => 'manager@afms.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'allowed_golongan_ids' => null, // Manager can access all golongan
            'email_verified_at' => now(),
        ]);

        // Create Admin user with limited access
        User::create([
            'name' => 'Admin Staff',
            'email' => 'admin@afms.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'allowed_golongan_ids' => [1, 2], // Can only access Staff and Supervisor golongan
            'email_verified_at' => now(),
        ]);

        // Create another Admin user
        User::create([
            'name' => 'Admin HRD',
            'email' => 'hrd@afms.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'allowed_golongan_ids' => [1], // Can only access Staff golongan
            'email_verified_at' => now(),
        ]);
    }
}