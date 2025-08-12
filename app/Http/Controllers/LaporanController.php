<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Absensi;
use App\Models\Bon;
use App\Models\Bonus;
use App\Models\Golongan;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $allowedGolonganIds = $user->getAllowedGolonganIds();
        
        $karyawanId = $request->get('karyawan_id');
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        
        $query = Karyawan::with(['golongan', 'lokasi'])
            ->whereIn('golongan_id', $allowedGolonganIds);
        
        if ($karyawanId) {
            $query->where('id', $karyawanId);
        }
        
        $karyawan = $query->get();
        
        $laporanData = $karyawan->map(function($k) use ($month, $year) {
            return $this->calculateGajiKaryawan($k, $month, $year);
        });
        
        // Get karyawan for filter
        $karyawanOptions = Karyawan::whereIn('golongan_id', $allowedGolonganIds)
            ->orderBy('nama_karyawan')
            ->get(['id', 'nama_karyawan', 'nip']);
        
        return Inertia::render('Laporan/Index', [
            'laporan_data' => $laporanData,
            'filters' => [
                'karyawan_id' => $karyawanId ? (int) $karyawanId : null,
                'month' => (int) $month,
                'year' => (int) $year,
            ],
            'karyawan_options' => $karyawanOptions,
            'user_role' => $user->role,
        ]);
    }
    
    public function detailKeterlambatan(Request $request, Karyawan $karyawan)
    {
        $user = auth()->user();
        
        if (!$user->canAccessGolongan($karyawan->golongan_id)) {
            abort(403, 'Unauthorized access.');
        }
        
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        
        $keterlambatan = Absensi::with('karyawan')
            ->where('karyawan_id', $karyawan->id)
            ->byMonth($month, $year)
            ->where(function($query) {
                $query->where('terlambat_pagi', true)
                      ->orWhere('terlambat_siang', true);
            })
            ->orderBy('tanggal')
            ->get();
        
        return Inertia::render('Laporan/DetailKeterlambatan', [
            'karyawan' => $karyawan->load('golongan'),
            'keterlambatan' => $keterlambatan,
            'month' => (int) $month,
            'year' => (int) $year,
        ]);
    }
    
    private function calculateGajiKaryawan($karyawan, $month, $year)
    {
        $golongan = $karyawan->golongan;
        $gajiPokok = $golongan->gaji_pokok;
        
        // Hitung total keterlambatan dalam bulan ini
        $totalMenitTerlambat = Absensi::where('karyawan_id', $karyawan->id)
            ->byMonth($month, $year)
            ->sum('menit_keterlambatan_pagi') + 
            Absensi::where('karyawan_id', $karyawan->id)
            ->byMonth($month, $year)
            ->sum('menit_keterlambatan_siang');
        
        // Hitung jumlah hari tidak masuk (tanpa keterangan valid)
        $hariTidakMasuk = Absensi::where('karyawan_id', $karyawan->id)
            ->byMonth($month, $year)
            ->where('status', 'tidak_hadir')
            ->count();
        
        // Hitung potongan keterlambatan
        $potonganTelat = 0;
        if ($totalMenitTerlambat > 0) {
            // Asumsi: setiap 60 menit keterlambatan = 1 hari denda
            $hariDenda = ceil($totalMenitTerlambat / 60);
            $potonganTelat = $hariDenda * $golongan->denda_keterlambatan;
        }
        
        // Hitung potongan tidak masuk
        $potonganTidakMasuk = 0;
        if ($hariTidakMasuk > 0) {
            $potonganTidakMasuk = $hariTidakMasuk * $golongan->potongan_tidak_masuk;
        }
        
        // Hitung total cicilan bon aktif
        $totalCicilanBon = Bon::where('karyawan_id', $karyawan->id)
            ->where('status', 'aktif')
            ->sum('cicilan_per_bulan');
        
        // Hitung total bonus bulan ini
        $totalBonus = Bonus::where('karyawan_id', $karyawan->id)
            ->byMonth($month, $year)
            ->sum('jumlah_bonus');
        
        // Hitung bonus tidak libur jika tidak mengambil jatah libur
        $jumlahLibur = Absensi::where('karyawan_id', $karyawan->id)
            ->byMonth($month, $year)
            ->whereIn('status', ['cuti', 'izin'])
            ->count();
        
        $bonusTidakLibur = 0;
        if ($jumlahLibur == 0 && $golongan->bonus_tidak_libur > 0) {
            $bonusTidakLibur = $golongan->bonus_tidak_libur;
            $totalBonus += $bonusTidakLibur;
        }
        
        // Hitung gaji bersih
        $gajiBersih = $gajiPokok - $potonganTelat - $potonganTidakMasuk - $totalCicilanBon + $totalBonus;
        
        return [
            'id' => $karyawan->id,
            'nama_karyawan' => $karyawan->nama_karyawan,
            'nip' => $karyawan->nip,
            'golongan' => $golongan->nama_golongan,
            'lokasi' => $karyawan->lokasi->nama_lokasi,
            'gaji_pokok' => $gajiPokok,
            'potongan_telat' => $potonganTelat,
            'potongan_tidak_masuk' => $potonganTidakMasuk,
            'total_cicilan_bon' => $totalCicilanBon,
            'total_bonus' => $totalBonus,
            'bonus_tidak_libur' => $bonusTidakLibur,
            'gaji_bersih' => $gajiBersih,
            'total_menit_terlambat' => $totalMenitTerlambat,
            'hari_tidak_masuk' => $hariTidakMasuk,
            'jumlah_libur' => $jumlahLibur,
        ];
    }
    
    public function export(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->isManager()) {
            abort(403, 'Unauthorized access.');
        }
        
        // This would implement CSV/Excel export functionality
        // For now, return JSON data
        $allowedGolonganIds = $user->getAllowedGolonganIds();
        
        $karyawanId = $request->get('karyawan_id');
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        
        $query = Karyawan::with(['golongan', 'lokasi'])
            ->whereIn('golongan_id', $allowedGolonganIds);
        
        if ($karyawanId) {
            $query->where('id', $karyawanId);
        }
        
        $karyawan = $query->get();
        
        $laporanData = $karyawan->map(function($k) use ($month, $year) {
            return $this->calculateGajiKaryawan($k, $month, $year);
        });
        
        return response()->json([
            'data' => $laporanData,
            'month' => $month,
            'year' => $year,
            'exported_at' => now()->format('Y-m-d H:i:s'),
        ]);
    }
}