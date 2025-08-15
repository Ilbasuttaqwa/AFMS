<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PengaturanHariLiburMingguan;

class PengaturanHariLiburMingguanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $days = [
            ['hari' => 'senin', 'is_libur' => false, 'keterangan' => 'Hari kerja'],
            ['hari' => 'selasa', 'is_libur' => false, 'keterangan' => 'Hari kerja'],
            ['hari' => 'rabu', 'is_libur' => false, 'keterangan' => 'Hari kerja'],
            ['hari' => 'kamis', 'is_libur' => false, 'keterangan' => 'Hari kerja'],
            ['hari' => 'jumat', 'is_libur' => false, 'keterangan' => 'Hari kerja'],
            ['hari' => 'sabtu', 'is_libur' => true, 'keterangan' => 'Hari libur weekend'],
            ['hari' => 'minggu', 'is_libur' => true, 'keterangan' => 'Hari libur weekend'],
        ];

        foreach ($days as $day) {
            PengaturanHariLiburMingguan::updateOrCreate(
                ['hari' => $day['hari']],
                $day
            );
        }
    }
}