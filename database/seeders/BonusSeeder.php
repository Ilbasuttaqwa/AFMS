<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Bonus;
use App\Models\Karyawan;
use App\Models\User;
use Carbon\Carbon;

class BonusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $karyawan = Karyawan::all();
        $manager = User::where('role', 'manager')->first();
        
        // Create monthly bonuses for some employees
        foreach ($karyawan->take(5) as $employee) {
            // Performance bonus for last month
            Bonus::create([
                'karyawan_id' => $employee->id,
                'jenis_bonus' => 'kinerja',
                'jumlah_bonus' => rand(300000, 800000),
                'bulan' => Carbon::now()->subMonth()->month,
                'tahun' => Carbon::now()->subMonth()->year,
                'keterangan' => 'Bonus kinerja bulanan',
                'created_by' => $manager->id,
            ]);
        }
        
        // Create attendance bonuses (no leave bonus)
        foreach ($karyawan->take(3) as $employee) {
            Bonus::create([
                'karyawan_id' => $employee->id,
                'jenis_bonus' => 'tidak_libur',
                'jumlah_bonus' => $employee->golongan->bonus_tidak_libur,
                'bulan' => Carbon::now()->subMonth()->month,
                'tahun' => Carbon::now()->subMonth()->year,
                'keterangan' => 'Bonus tidak libur sebulan penuh',
                'created_by' => $manager->id,
            ]);
        }
        
        // Create holiday bonus for all employees
        foreach ($karyawan as $employee) {
            Bonus::create([
                'karyawan_id' => $employee->id,
                'jenis_bonus' => 'lainnya',
                'jumlah_bonus' => $employee->golongan->gaji_pokok * 0.5, // 50% of base salary
                'bulan' => 12,
                'tahun' => Carbon::now()->subYear()->year,
                'keterangan' => 'Tunjangan Hari Raya (THR)',
                'created_by' => $manager->id,
            ]);
        }
        
        // Create some project bonuses
        foreach ($karyawan->where('golongan_id', '>=', 2)->take(2) as $employee) {
            Bonus::create([
                'karyawan_id' => $employee->id,
                'jenis_bonus' => 'lainnya',
                'jumlah_bonus' => rand(1000000, 2000000),
                'bulan' => Carbon::now()->month,
                'tahun' => Carbon::now()->year,
                'keterangan' => 'Bonus penyelesaian proyek khusus',
                'created_by' => $manager->id,
            ]);
        }
    }
}