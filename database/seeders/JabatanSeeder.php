<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah data sudah ada
        if (Jabatan::count() == 0) {
            Jabatan::create([
                'id' => 1,
                'nama_jabatan' => 'Manager',
                'gaji_pokok' => 8000000.00,
                'minimal_absen_pagi' => '07:00:00',
                'minimal_absen_siang' => '12:00:00',
                'potongan_absen_awal' => 10000.00,
            ]);

            Jabatan::create([
                'id' => 2,
                'nama_jabatan' => 'Supervisor',
                'gaji_pokok' => 6000000.00,
                'minimal_absen_pagi' => '07:00:00',
                'minimal_absen_siang' => '12:00:00',
                'potongan_absen_awal' => 10000.00,
            ]);

            Jabatan::create([
                'id' => 3,
                'nama_jabatan' => 'Staff',
                'gaji_pokok' => 4000000.00,
                'minimal_absen_pagi' => '07:00:00',
                'minimal_absen_siang' => '12:00:00',
                'potongan_absen_awal' => 10000.00,
            ]);

            Jabatan::create([
                'id' => 4,
                'nama_jabatan' => 'Operator',
                'gaji_pokok' => 3500000.00,
                'minimal_absen_pagi' => '07:00:00',
                'minimal_absen_siang' => '12:00:00',
                'potongan_absen_awal' => 10000.00,
            ]);
        }
    }
}
