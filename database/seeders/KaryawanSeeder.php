<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nama_pegawai' => 'John Doe',
            'google_id' => null,
            'email' => 'karyawan@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status_pegawai' => 1,
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jl. Contoh No. 123',
            'tanggal_masuk' => '2024-01-01',
            'gaji' => 5000000,
            'id_jabatan' => 1,
        ]);

        User::create([
            'nama_pegawai' => 'Jane Smith',
            'google_id' => null,
            'email' => 'jane@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status_pegawai' => 1,
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1992-05-15',
            'jenis_kelamin' => 'Perempuan',
            'alamat' => 'Jl. Sample No. 456',
            'tanggal_masuk' => '2024-02-01',
            'gaji' => 4500000,
            'id_jabatan' => 1,
        ]);
    }
}
