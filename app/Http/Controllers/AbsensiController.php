<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Karyawan;
use App\Models\Lokasi;
use App\Models\Golongan;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $allowedGolonganIds = $user->getAllowedGolonganIds();
        
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        $lokasiId = $request->get('lokasi_id');
        
        // Get calendar data
        $startDate = Carbon::createFromDate($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();
        
        $query = Absensi::with(['karyawan.golongan', 'karyawan.lokasi'])
            ->byDateRange($startDate, $endDate)
            ->whereHas('karyawan', function($q) use ($allowedGolonganIds, $lokasiId) {
                $q->whereIn('golongan_id', $allowedGolonganIds);
                if ($lokasiId) {
                    $q->where('lokasi_id', $lokasiId);
                }
            });
        
        $absensi = $query->orderBy('tanggal', 'desc')
            ->orderBy('karyawan_id')
            ->paginate(50)
            ->withQueryString();
        
        // Get calendar view data
        $calendarData = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dayAbsensi = Absensi::whereDate('tanggal', $date)
                ->whereHas('karyawan', function($q) use ($allowedGolonganIds, $lokasiId) {
                    $q->whereIn('golongan_id', $allowedGolonganIds);
                    if ($lokasiId) {
                        $q->where('lokasi_id', $lokasiId);
                    }
                })
                ->selectRaw('status, COUNT(*) as jumlah')
                ->groupBy('status')
                ->pluck('jumlah', 'status')
                ->toArray();
            
            $terlambat = Absensi::whereDate('tanggal', $date)
                ->where(function($q) {
                    $q->where('terlambat_pagi', true)
                      ->orWhere('terlambat_siang', true);
                })
                ->whereHas('karyawan', function($q) use ($allowedGolonganIds, $lokasiId) {
                    $q->whereIn('golongan_id', $allowedGolonganIds);
                    if ($lokasiId) {
                        $q->where('lokasi_id', $lokasiId);
                    }
                })
                ->count();
            
            $calendarData[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $date->day,
                'hadir' => $dayAbsensi['hadir'] ?? 0,
                'tidak_hadir' => $dayAbsensi['tidak_hadir'] ?? 0,
                'izin' => $dayAbsensi['izin'] ?? 0,
                'sakit' => $dayAbsensi['sakit'] ?? 0,
                'cuti' => $dayAbsensi['cuti'] ?? 0,
                'terlambat' => $terlambat,
            ];
        }
        
        // Get filter options
        $lokasi = Lokasi::active()->get();
        
        return Inertia::render('Absensi/Index', [
            'absensi' => $absensi,
            'calendar_data' => $calendarData,
            'filters' => [
                'month' => (int) $month,
                'year' => (int) $year,
                'lokasi_id' => $lokasiId ? (int) $lokasiId : null,
            ],
            'lokasi' => $lokasi,
            'user_role' => $user->role,
        ]);
    }
    
    public function show(Request $request, $date)
    {
        $user = auth()->user();
        $allowedGolonganIds = $user->getAllowedGolonganIds();
        $lokasiId = $request->get('lokasi_id');
        
        $absensiDetail = Absensi::with(['karyawan.golongan', 'karyawan.lokasi'])
            ->whereDate('tanggal', $date)
            ->whereHas('karyawan', function($q) use ($allowedGolonganIds, $lokasiId) {
                $q->whereIn('golongan_id', $allowedGolonganIds);
                if ($lokasiId) {
                    $q->where('lokasi_id', $lokasiId);
                }
            })
            ->orderBy('karyawan_id')
            ->get();
        
        return Inertia::render('Absensi/Detail', [
            'absensi_detail' => $absensiDetail,
            'date' => $date,
            'lokasi_id' => $lokasiId,
        ]);
    }
    
    public function store(Request $request)
    {
        // This will be called by fingerprint SDK
        $request->validate([
            'fingerprint_id' => 'required|string',
            'device_id' => 'required|string',
            'timestamp' => 'required|date',
        ]);
        
        $karyawan = Karyawan::where('fingerprint_id', $request->fingerprint_id)->first();
        
        if (!$karyawan) {
            return response()->json(['error' => 'Karyawan tidak ditemukan'], 404);
        }
        
        $date = Carbon::parse($request->timestamp)->toDateString();
        $time = Carbon::parse($request->timestamp)->toTimeString();
        
        $absensi = Absensi::firstOrCreate(
            [
                'karyawan_id' => $karyawan->id,
                'tanggal' => $date,
            ],
            [
                'fingerprint_device_id' => $request->device_id,
            ]
        );
        
        // Determine which time slot this is
        $golongan = $karyawan->golongan;
        $currentTime = Carbon::createFromFormat('H:i:s', $time);
        
        // Logic to determine if this is morning, lunch break, or evening
        $jamMasukPagi = Carbon::createFromFormat('H:i:s', $golongan->jam_masuk_pagi);
        $jamPulangSiang = Carbon::createFromFormat('H:i:s', $golongan->jam_pulang_siang);
        $jamMasukSiang = Carbon::createFromFormat('H:i:s', $golongan->jam_masuk_siang);
        $jamPulangSore = Carbon::createFromFormat('H:i:s', $golongan->jam_pulang_sore);
        
        if (!$absensi->waktu_masuk_pagi && $currentTime->between($jamMasukPagi->subHours(2), $jamPulangSiang)) {
            $absensi->waktu_masuk_pagi = $time;
        } elseif (!$absensi->waktu_keluar_istirahat && $currentTime->between($jamPulangSiang->subMinutes(30), $jamMasukSiang)) {
            $absensi->waktu_keluar_istirahat = $time;
        } elseif (!$absensi->waktu_masuk_istirahat && $currentTime->between($jamMasukSiang->subMinutes(30), $jamPulangSore)) {
            $absensi->waktu_masuk_istirahat = $time;
        } elseif (!$absensi->waktu_pulang && $currentTime->gte($jamPulangSore->subMinutes(30))) {
            $absensi->waktu_pulang = $time;
        }
        
        $absensi->save();
        $absensi->calculateKeterlambatan();
        
        return response()->json([
            'message' => 'Absensi berhasil dicatat',
            'data' => $absensi->load('karyawan')
        ]);
    }
}