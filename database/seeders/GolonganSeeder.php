<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Golongan;

class GolonganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $golongan = [
            [
                'nama_golongan' => 'Staff',
                'gaji_pokok' => 3500000,
                'jam_masuk_pagi' => '08:00:00',
                'jam_pulang_siang' => '12:00:00',
                'jam_masuk_siang' => '13:00:00',
                'jam_pulang_sore' => '17:00:00',
                'maksimal_ceklok_pagi' => 30,
                'maksimal_ceklok_siang' => 30,
                'denda_keterlambatan' => 25000,
                'potongan_tidak_masuk' => 100000,
                'jatah_libur' => 12,
                'bonus_tidak_libur' => 200000,
            ],
            [
                'nama_golongan' => 'Supervisor',
                'gaji_pokok' => 5000000,
                'jam_masuk_pagi' => '08:00:00',
                'jam_pulang_siang' => '12:00:00',
                'jam_masuk_siang' => '13:00:00',
                'jam_pulang_sore' => '17:00:00',
                'maksimal_ceklok_pagi' => 30,
                'maksimal_ceklok_siang' => 30,
                'denda_keterlambatan' => 50000,
                'potongan_tidak_masuk' => 150000,
                'jatah_libur' => 12,
                'bonus_tidak_libur' => 300000,
            ],
            [
                'nama_golongan' => 'Manager',
                'gaji_pokok' => 8000000,
                'jam_masuk_pagi' => '08:00:00',
                'jam_pulang_siang' => '12:00:00',
                'jam_masuk_siang' => '13:00:00',
                'jam_pulang_sore' => '17:00:00',
                'maksimal_ceklok_pagi' => 30,
                'maksimal_ceklok_siang' => 30,
                'denda_keterlambatan' => 75000,
                'potongan_tidak_masuk' => 200000,
                'jatah_libur' => 12,
                'bonus_tidak_libur' => 500000,
            ],
        ];

        foreach ($golongan as $data) {
            Golongan::create($data);
        }
    }
}