<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Bon;
use App\Models\Karyawan;
use App\Models\User;
use Carbon\Carbon;

class BonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $karyawan = Karyawan::take(4)->get();
        $manager = User::where('role', 'manager')->first();
        
        $bonData = [
            [
                'karyawan_id' => $karyawan[0]->id,
                'jumlah_bon' => 2000000,
                'cicilan_per_bulan' => 200000,
                'sisa_bon' => 1400000,
                'status' => 'aktif',
                'tanggal_bon' => Carbon::now()->subMonths(3)->format('Y-m-d'),
                'keterangan' => 'Bon untuk keperluan renovasi rumah',
                'created_by' => $manager->id,
            ],
            [
                'karyawan_id' => $karyawan[1]->id,
                'jumlah_bon' => 1500000,
                'cicilan_per_bulan' => 300000,
                'sisa_bon' => 900000,
                'status' => 'aktif',
                'tanggal_bon' => Carbon::now()->subMonths(2)->format('Y-m-d'),
                'keterangan' => 'Bon untuk biaya pendidikan anak',
                'created_by' => $manager->id,
            ],
            [
                'karyawan_id' => $karyawan[2]->id,
                'jumlah_bon' => 1000000,
                'cicilan_per_bulan' => 250000,
                'sisa_bon' => 0,
                'status' => 'lunas',
                'tanggal_bon' => Carbon::now()->subMonths(6)->format('Y-m-d'),
                'keterangan' => 'Bon untuk keperluan medis',
                'created_by' => $manager->id,
            ],
            [
                'karyawan_id' => $karyawan[3]->id,
                'jumlah_bon' => 3000000,
                'cicilan_per_bulan' => 500000,
                'sisa_bon' => 3000000,
                'status' => 'aktif',
                'tanggal_bon' => Carbon::now()->subDays(5)->format('Y-m-d'),
                'keterangan' => 'Bon untuk modal usaha sampingan',
                'created_by' => $manager->id,
            ],
        ];
        
        foreach ($bonData as $data) {
            Bon::create($data);
        }
    }
}