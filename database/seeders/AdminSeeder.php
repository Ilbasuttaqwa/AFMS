<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin user
        User::create([
            'nama_pegawai' => 'Administrator',
            'google_id' => null,
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_admin' => 1,
            'status_pegawai' => 1,
            'id_jabatan' => 1,
            'id_cabang' => 1,
        ]);

        // Create Manager user
        User::create([
            'nama_pegawai' => 'Manager',
            'google_id' => null,
            'email' => 'manager@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'is_admin' => 1,
            'status_pegawai' => 1,
            'id_jabatan' => 1,
            'id_cabang' => 1,
        ]);
    }
}
