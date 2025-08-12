<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Karyawan;
use Carbon\Carbon;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $karyawan = [
            [
                'nama_karyawan' => 'Ahmad Rizki',
                'nip' => 'EMP001',
                'email' => 'ahmad.rizki@company.com',
                'no_telepon' => '081234567890',
                'alamat' => 'Jl. Merdeka No. 10, Jakarta',
                'tanggal_lahir' => '1990-05-15',
                'jenis_kelamin' => 'L',
                'tanggal_masuk' => '2020-01-15',
                'golongan_id' => 1, // Staff
                'lokasi_id' => 1, // Jakarta
                'fingerprint_id' => 'FP001',
                'is_active' => true,
            ],
            [
                'nama_karyawan' => 'Siti Nurhaliza',
                'nip' => 'EMP002',
                'email' => 'siti.nurhaliza@company.com',
                'no_telepon' => '081234567891',
                'alamat' => 'Jl. Pahlawan No. 20, Jakarta',
                'tanggal_lahir' => '1988-08-22',
                'jenis_kelamin' => 'P',
                'tanggal_masuk' => '2019-03-10',
                'golongan_id' => 2, // Supervisor
                'lokasi_id' => 1, // Jakarta
                'fingerprint_id' => 'FP002',
                'is_active' => true,
            ],
            [
                'nama_karyawan' => 'Budi Santoso',
                'nip' => 'EMP003',
                'email' => 'budi.santoso@company.com',
                'no_telepon' => '081234567892',
                'alamat' => 'Jl. Diponegoro No. 30, Bandung',
                'tanggal_lahir' => '1985-12-03',
                'jenis_kelamin' => 'L',
                'tanggal_masuk' => '2018-06-01',
                'golongan_id' => 3, // Manager
                'lokasi_id' => 2, // Bandung
                'fingerprint_id' => 'FP003',
                'is_active' => true,
            ],
            [
                'nama_karyawan' => 'Dewi Lestari',
                'nip' => 'EMP004',
                'email' => 'dewi.lestari@company.com',
                'no_telepon' => '081234567893',
                'alamat' => 'Jl. Veteran No. 40, Bandung',
                'tanggal_lahir' => '1992-02-18',
                'jenis_kelamin' => 'P',
                'tanggal_masuk' => '2021-09-15',
                'golongan_id' => 1, // Staff
                'lokasi_id' => 2, // Bandung
                'fingerprint_id' => 'FP004',
                'is_active' => true,
            ],
            [
                'nama_karyawan' => 'Eko Prasetyo',
                'nip' => 'EMP005',
                'email' => 'eko.prasetyo@company.com',
                'no_telepon' => '081234567894',
                'alamat' => 'Jl. Basuki Rahmat No. 50, Surabaya',
                'tanggal_lahir' => '1987-11-25',
                'jenis_kelamin' => 'L',
                'tanggal_masuk' => '2019-12-01',
                'golongan_id' => 2, // Supervisor
                'lokasi_id' => 3, // Surabaya
                'fingerprint_id' => 'FP005',
                'is_active' => true,
            ],
            [
                'nama_karyawan' => 'Maya Sari',
                'nip' => 'EMP006',
                'email' => 'maya.sari@company.com',
                'no_telepon' => '081234567895',
                'alamat' => 'Jl. Ahmad Yani No. 60, Surabaya',
                'tanggal_lahir' => '1993-07-12',
                'jenis_kelamin' => 'P',
                'tanggal_masuk' => '2022-02-14',
                'golongan_id' => 1, // Staff
                'lokasi_id' => 3, // Surabaya
                'fingerprint_id' => 'FP006',
                'is_active' => true,
            ],
            [
                'nama_karyawan' => 'Rudi Hartono',
                'nip' => 'EMP007',
                'email' => 'rudi.hartono@company.com',
                'no_telepon' => '081234567896',
                'alamat' => 'Jl. Sisingamangaraja No. 70, Medan',
                'tanggal_lahir' => '1986-04-08',
                'jenis_kelamin' => 'L',
                'tanggal_masuk' => '2020-08-20',
                'golongan_id' => 2, // Supervisor
                'lokasi_id' => 4, // Medan
                'fingerprint_id' => 'FP007',
                'is_active' => true,
            ],
            [
                'nama_karyawan' => 'Indah Permata',
                'nip' => 'EMP008',
                'email' => 'indah.permata@company.com',
                'no_telepon' => '081234567897',
                'alamat' => 'Jl. Kartini No. 80, Medan',
                'tanggal_lahir' => '1991-09-30',
                'jenis_kelamin' => 'P',
                'tanggal_masuk' => '2021-11-05',
                'golongan_id' => 1, // Staff
                'lokasi_id' => 4, // Medan
                'fingerprint_id' => 'FP008',
                'is_active' => true,
            ],
        ];

        foreach ($karyawan as $data) {
            Karyawan::create($data);
        }
    }
}