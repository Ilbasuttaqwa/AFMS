<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Absensi;
use App\Models\Golongan;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $today = Carbon::today();
        
        // Get allowed golongan IDs based on user role
        $allowedGolonganIds = $user->getAllowedGolonganIds();
        
        // Statistik karyawan terlambat hari ini
        $karyawanTerlambat = Absensi::with(['karyawan.golongan', 'karyawan.lokasi'])
            ->terlambatHariIni()
            ->whereHas('karyawan', function($query) use ($allowedGolonganIds) {
                $query->whereIn('golongan_id', $allowedGolonganIds);
            })
            ->count();
        
        // Total karyawan aktif
        $totalKaryawan = Karyawan::active()
            ->whereIn('golongan_id', $allowedGolonganIds)
            ->count();
        
        // Statistik absensi hari ini
        $absensiHariIni = Absensi::whereDate('tanggal', $today)
            ->whereHas('karyawan', function($query) use ($allowedGolonganIds) {
                $query->whereIn('golongan_id', $allowedGolonganIds);
            })
            ->selectRaw('status, COUNT(*) as jumlah')
            ->groupBy('status')
            ->pluck('jumlah', 'status')
            ->toArray();
        
        // Statistik per lokasi
        $statistikLokasi = Lokasi::with(['karyawan' => function($query) use ($allowedGolonganIds) {
                $query->whereIn('golongan_id', $allowedGolonganIds);
            }])
            ->get()
            ->map(function($lokasi) use ($today) {
                $totalKaryawan = $lokasi->karyawan->count();
                $hadir = Absensi::whereDate('tanggal', $today)
                    ->where('status', 'hadir')
                    ->whereHas('karyawan', function($query) use ($lokasi) {
                        $query->where('lokasi_id', $lokasi->id);
                    })
                    ->count();
                
                $terlambat = Absensi::terlambatHariIni()
                    ->whereHas('karyawan', function($query) use ($lokasi) {
                        $query->where('lokasi_id', $lokasi->id);
                    })
                    ->count();
                
                return [
                    'nama_lokasi' => $lokasi->nama_lokasi,
                    'total_karyawan' => $totalKaryawan,
                    'hadir' => $hadir,
                    'terlambat' => $terlambat,
                    'tidak_hadir' => $totalKaryawan - $hadir,
                ];
            });
        
        // Grafik absensi 7 hari terakhir
        $grafikAbsensi = collect(range(6, 0))->map(function($daysAgo) use ($allowedGolonganIds) {
            $date = Carbon::today()->subDays($daysAgo);
            
            $hadir = Absensi::whereDate('tanggal', $date)
                ->where('status', 'hadir')
                ->whereHas('karyawan', function($query) use ($allowedGolonganIds) {
                    $query->whereIn('golongan_id', $allowedGolonganIds);
                })
                ->count();
            
            $terlambat = Absensi::whereDate('tanggal', $date)
                ->where(function($query) {
                    $query->where('terlambat_pagi', true)
                          ->orWhere('terlambat_siang', true);
                })
                ->whereHas('karyawan', function($query) use ($allowedGolonganIds) {
                    $query->whereIn('golongan_id', $allowedGolonganIds);
                })
                ->count();
            
            return [
                'tanggal' => $date->format('d/m'),
                'hadir' => $hadir,
                'terlambat' => $terlambat,
            ];
        });
        
        return Inertia::render('Dashboard/Index', [
            'stats' => [
                'karyawan_terlambat' => $karyawanTerlambat,
                'total_karyawan' => $totalKaryawan,
                'absensi_hari_ini' => $absensiHariIni,
            ],
            'statistik_lokasi' => $statistikLokasi,
            'grafik_absensi' => $grafikAbsensi,
            'user_role' => $user->role,
        ]);
    }
}