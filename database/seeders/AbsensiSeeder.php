<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Absensi;
use App\Models\Karyawan;
use Carbon\Carbon;

class AbsensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $karyawanIds = Karyawan::pluck('id')->toArray();
        
        // Generate absensi data for the last 30 days (excluding today)
        for ($i = 29; $i >= 1; $i--) {
            $tanggal = Carbon::now()->subDays($i)->format('Y-m-d');
            
            foreach ($karyawanIds as $karyawanId) {
                // Skip some days randomly to simulate absences
                if (rand(1, 10) <= 8) { // 80% attendance rate
                    $jamMasukPagi = Carbon::parse($tanggal . ' 08:' . rand(0, 30) . ':' . rand(0, 59));
                    $jamPulangSiang = Carbon::parse($tanggal . ' 12:' . rand(0, 15) . ':' . rand(0, 59));
                    $jamMasukSiang = Carbon::parse($tanggal . ' 13:' . rand(0, 30) . ':' . rand(0, 59));
                    $jamPulangSore = Carbon::parse($tanggal . ' 17:' . rand(0, 30) . ':' . rand(0, 59));
                    
                    // Determine if late
                    $terlambatPagi = $jamMasukPagi->format('H:i:s') > '08:00:00';
                    $terlambatSiang = $jamMasukSiang->format('H:i:s') > '13:00:00';
                    
                    $menitTerlambatPagi = 0;
                    $menitTerlambatSiang = 0;
                    
                    if ($terlambatPagi) {
                        $batasWaktu = Carbon::parse($tanggal . ' 08:00:00');
                        $menitTerlambatPagi = $batasWaktu->diffInMinutes($jamMasukPagi);
                    }
                    
                    if ($terlambatSiang) {
                        $batasWaktu = Carbon::parse($tanggal . ' 13:00:00');
                        $menitTerlambatSiang = $batasWaktu->diffInMinutes($jamMasukSiang);
                    }
                    
                    Absensi::create([
                        'karyawan_id' => $karyawanId,
                        'tanggal' => $tanggal,
                        'waktu_masuk_pagi' => $jamMasukPagi,
                        'waktu_keluar_istirahat' => $jamPulangSiang,
                        'waktu_masuk_istirahat' => $jamMasukSiang,
                        'waktu_pulang' => $jamPulangSore,
                        'terlambat_pagi' => $terlambatPagi,
                        'terlambat_siang' => $terlambatSiang,
                        'menit_keterlambatan_pagi' => $menitTerlambatPagi,
                        'menit_keterlambatan_siang' => $menitTerlambatSiang,
                        'status' => 'hadir',
                        'keterangan' => null,
                    ]);
                }
            }
        }
        
        // Add some specific late attendance for today
        $today = Carbon::now()->format('Y-m-d');
        $someKaryawan = Karyawan::take(3)->get();
        
        foreach ($someKaryawan as $karyawan) {
            // Check if record already exists for today
            $existingRecord = Absensi::where('karyawan_id', $karyawan->id)
                                   ->where('tanggal', $today)
                                   ->first();
                                   
            if (!$existingRecord) {
                // Create late attendance only if no record exists
                $jamMasukPagi = Carbon::parse($today . ' 08:' . rand(15, 45) . ':' . rand(0, 59));
                $menitTerlambatPagi = Carbon::parse($today . ' 08:00:00')->diffInMinutes($jamMasukPagi);
                
                Absensi::create([
                    'karyawan_id' => $karyawan->id,
                    'tanggal' => $today,
                    'waktu_masuk_pagi' => $jamMasukPagi,
                    'waktu_keluar_istirahat' => null,
                    'waktu_masuk_istirahat' => null,
                    'waktu_pulang' => null,
                    'terlambat_pagi' => true,
                    'terlambat_siang' => false,
                    'menit_keterlambatan_pagi' => $menitTerlambatPagi,
                    'menit_keterlambatan_siang' => 0,
                    'status' => 'hadir',
                    'keterangan' => 'Terlambat masuk pagi',
                ]);
            }
        }
    }
}