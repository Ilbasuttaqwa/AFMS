<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Lokasi;

class LokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lokasi = [
            [
                'nama_lokasi' => 'Kantor Pusat Jakarta',
                'alamat' => 'Jl. Sudirman No. 123, Jakarta Pusat',
                'kode_lokasi' => 'JKT001',
                'is_active' => true,
            ],
            [
                'nama_lokasi' => 'Cabang Bandung',
                'alamat' => 'Jl. Asia Afrika No. 45, Bandung',
                'kode_lokasi' => 'BDG001',
                'is_active' => true,
            ],
            [
                'nama_lokasi' => 'Cabang Surabaya',
                'alamat' => 'Jl. Pemuda No. 67, Surabaya',
                'kode_lokasi' => 'SBY001',
                'is_active' => true,
            ],
            [
                'nama_lokasi' => 'Cabang Medan',
                'alamat' => 'Jl. Gatot Subroto No. 89, Medan',
                'kode_lokasi' => 'MDN001',
                'is_active' => true,
            ],
        ];

        foreach ($lokasi as $data) {
            Lokasi::create($data);
        }
    }
}