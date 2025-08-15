<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FingerprintAttendance;
use App\Models\User;
use Carbon\Carbon;

class FingerprintAttendanceDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua user yang ada
        $users = User::all();
        $totalUsers = $users->count();
        
        if ($totalUsers < 2) {
            $this->command->error('Tidak cukup user di database. Minimal butuh 2 user.');
            return;
        }

        $today = Carbon::today();
        $jamMasukNormal = '08:00:00'; // Jam masuk normal
        $jamMasukTerlambat = '08:30:00'; // Jam masuk terlambat
        
        // Hapus data dummy sebelumnya (opsional)
        FingerprintAttendance::where('raw_data', 'LIKE', '%DUMMY_DATA%')->delete();
        
        $this->command->info("Membuat data dummy fingerprint attendance untuk {$totalUsers} user...");
        
        // Bagi user menjadi 2 kelompok: tepat waktu dan terlambat
        $tepatWaktuCount = ceil($totalUsers / 2);
        $terlambatCount = $totalUsers - $tepatWaktuCount;
        
        // User tepat waktu
        for ($i = 0; $i < $tepatWaktuCount; $i++) {
            $user = $users[$i];
            
            // Set device_user_id jika belum ada
            if (!$user->device_user_id) {
                $user->device_user_id = 'FP_' . str_pad($user->id, 3, '0', STR_PAD_LEFT);
                $user->save();
            }
            
            FingerprintAttendance::create([
                'device_user_id' => $user->device_user_id,
                'device_ip' => '192.168.1.100', // IP dummy
                'attendance_time' => $today->copy()->setTimeFromTimeString($jamMasukNormal)->addMinutes(rand(-5, 5)), // Variasi ±5 menit
                'attendance_type' => 1, // 1 = masuk
                'verification_type' => 'fingerprint',
                'is_processed' => false,
                'user_id' => $user->id,
                'cabang_id' => $user->id_cabang,
                'raw_data' => json_encode([
                    'status' => 'DUMMY_DATA',
                    'type' => 'TEPAT_WAKTU',
                    'created_at' => now()
                ])
            ]);
            
            $this->command->info("✅ Data absensi tepat waktu untuk {$user->nama_pegawai}");
        }
        
        // User terlambat
        for ($i = $tepatWaktuCount; $i < $totalUsers; $i++) {
            $user = $users[$i];
            
            // Set device_user_id jika belum ada
            if (!$user->device_user_id) {
                $user->device_user_id = 'FP_' . str_pad($user->id, 3, '0', STR_PAD_LEFT);
                $user->save();
            }
            
            FingerprintAttendance::create([
                'device_user_id' => $user->device_user_id,
                'device_ip' => '192.168.1.100', // IP dummy
                'attendance_time' => $today->copy()->setTimeFromTimeString($jamMasukTerlambat)->addMinutes(rand(0, 30)), // Terlambat 30-60 menit
                'attendance_type' => 1, // 1 = masuk
                'verification_type' => 'fingerprint',
                'is_processed' => false,
                'user_id' => $user->id,
                'cabang_id' => $user->id_cabang,
                'raw_data' => json_encode([
                    'status' => 'DUMMY_DATA',
                    'type' => 'TERLAMBAT',
                    'created_at' => now()
                ])
            ]);
            
            $this->command->info("⚠️ Data absensi terlambat untuk {$user->nama_pegawai}");
        }
        
        $this->command->info('');
        $this->command->info('🎉 Selesai! Data dummy fingerprint attendance berhasil dibuat:');
        $this->command->info("   - {$tepatWaktuCount} orang absen tepat waktu (sekitar jam 08:00)");
        $this->command->info("   - {$terlambatCount} orang absen terlambat (sekitar jam 08:30-09:00)");
        $this->command->info('');
        $this->command->info('💡 Untuk menghapus data dummy, jalankan:');
        $this->command->info('   php artisan tinker --execute="App\\Models\\FingerprintAttendance::where(\'raw_data\', \'LIKE\', \'%DUMMY_DATA%\')->delete();"');
    }
}