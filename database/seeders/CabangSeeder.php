<?php

namespace Database\Seeders;

use App\Models\Cabang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CabangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah data sudah ada
        if (Cabang::count() == 0) {
            Cabang::create([
                'id' => 1,
                'nama_cabang' => 'Cabang Pusat',
                'alamat' => 'Jl. Raya No. 123',
                'kode_cabang' => 'CP001',
            ]);

            Cabang::create([
                'id' => 2,
                'nama_cabang' => 'Cabang Timur',
                'alamat' => 'Jl. Timur No. 456',
                'kode_cabang' => 'CT002',
            ]);
        }
    }
}
