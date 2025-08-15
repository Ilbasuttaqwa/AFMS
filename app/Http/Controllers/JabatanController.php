<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\User;
use App\Models\BonusGaji;
use App\Models\PotonganGaji;
use App\Models\Cabang;
use App\Models\Bon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jabatan = Jabatan::latest()->get();
        confirmDelete('Hapus Jabatan!', 'Apakah Anda Yakin?');
        return view('admin.jabatan.index', compact('jabatan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'nama_jabatan' => 'required|unique:jabatans',
                'additional_fields.*' => 'nullable|string|unique:jabatans,nama_jabatan',
            ],
            [
                'nama_jabatan.unique' => 'Nama jabatan sudah ada!',
                'additional_fields.*.unique' => 'Nama jabatan tambahan sudah ada!',
            ]
        );

        $jabatan = new Jabatan();
        $jabatan->nama_jabatan = $request->nama_jabatan;
        $jabatan->save();

        // Simpan jabatan tambahan (jika ada)
        if ($request->has('additional_fields')) {
            foreach ($request->additional_fields as $additionalField) {
                if (!empty($additionalField)) {
                    // Buat instance baru untuk tiap jabatan tambahan
                    $newJabatan = new Jabatan();
                    $newJabatan->nama_jabatan = $additionalField;
                    $newJabatan->save();
                }
            }
        }

        return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Display detailed information for a specific jabatan
     */
    public function detail($id)
    {
        $jabatan = Jabatan::with('pegawai')->findOrFail($id);
        
        // Get employees in this position
        $pegawai = User::where('id_jabatan', $id)
                      ->where('role', '!=', 'manager')
                      ->with(['cabang', 'absensi'])
                      ->get();
        
        // Get bonus and deduction data for employees in this position
        $bonusGaji = BonusGaji::whereHas('user', function($query) use ($id) {
            $query->where('id_jabatan', $id);
        })->with('user')->get();
        
        $potonganGaji = PotonganGaji::whereHas('user', function($query) use ($id) {
            $query->where('id_jabatan', $id);
        })->with('user')->get();
        
        // Get bon data for employees in this position
        $bonData = Bon::whereHas('pegawai', function($query) use ($id) {
            $query->where('id_jabatan', $id);
        })->with('pegawai')->get();
        
        // Get all positions for transfer functionality
        $allJabatan = Jabatan::where('id', '!=', $id)->get();
        
        // Get all branches
        $cabangs = Cabang::all();
        
        return view('admin.jabatan.detail', compact(
            'jabatan', 'pegawai', 'bonusGaji', 'potonganGaji', 
            'bonData', 'allJabatan', 'cabangs'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_jabatan' => 'required|unique:jabatans,nama_jabatan,' . $id,
            'gaji_pokok' => 'nullable|numeric',
            'batas_keterlambatan' => 'nullable|integer',
            'potongan_keterlambatan' => 'nullable|numeric',
        ], [
            'nama_jabatan.unique' => 'Nama jabatan sudah ada!',
            'gaji_pokok.numeric' => 'Gaji pokok harus berupa angka!',
            'batas_keterlambatan.integer' => 'Batas keterlambatan harus berupa angka!',
            'potongan_keterlambatan.numeric' => 'Potongan keterlambatan harus berupa angka!',
        ]);

        $jabatan = Jabatan::find($id);
        $jabatan->nama_jabatan = $request->nama_jabatan;

        if ($request->has('gaji_pokok')) {
            $jabatan->gaji_pokok = $request->gaji_pokok;
        }

        if ($request->has('batas_keterlambatan')) {
            $jabatan->batas_keterlambatan = $request->batas_keterlambatan;
        }

        if ($request->has('potongan_keterlambatan')) {
            $jabatan->potongan_keterlambatan = $request->potongan_keterlambatan;
        }

        $jabatan->save();
        return redirect()->route('jabatan.index')->with('warning', 'Jabatan berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jabatan = Jabatan::findOrFail($id);

        // Cek apakah ada pegawai yang menggunakan jabatan ini
        if ($jabatan->pegawai()->count() > 0) {
            return redirect()->route('jabatan.index')->with('error', 'Jabatan tidak bisa dihapus karena masih ada pegawai yang terkait.');
        }

        // Jika tidak ada pegawai terkait, lanjutkan penghapusan
        $jabatan->delete();
        return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil dihapus.');
    }

    /**
     * Get schedule data for a specific jabatan (for AJAX)
     */
    public function getScheduleData($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        
        return response()->json([
            'jam_masuk' => $jabatan->jam_masuk,
            'jam_masuk_siang' => $jabatan->jam_masuk_siang,
            'toleransi_keterlambatan' => $jabatan->toleransi_keterlambatan,
            'potongan_keterlambatan' => $jabatan->potongan_keterlambatan,
            'gaji_pokok' => $jabatan->gaji_pokok,
        ]);
    }

    /**
     * Update work schedule settings
     */
    public function updateJamKerja(Request $request, $id)
    {
        $request->validate([
            'jam_masuk' => 'required|date_format:H:i',
            'jam_masuk_siang' => 'nullable|date_format:H:i',
            'toleransi_keterlambatan' => 'required|integer|min:0',
            'gaji_pokok' => 'required|numeric|min:0',
        ]);

        $jabatan = Jabatan::findOrFail($id);
        $jabatan->update([
            'jam_masuk' => $request->jam_masuk,
            'jam_masuk_siang' => $request->jam_masuk_siang,
            'toleransi_keterlambatan' => $request->toleransi_keterlambatan,
            'gaji_pokok' => $request->gaji_pokok,
        ]);

        return redirect()->back()->with('success', 'Pengaturan jam kerja berhasil diperbarui!');
    }

    /**
     * Update lateness penalty settings
     */
    public function updatePotongan(Request $request, $id)
    {
        $request->validate([
            'potongan_31_45' => 'required|numeric|min:0',
            'potongan_46_60' => 'required|numeric|min:0',
            'potongan_61_100' => 'required|numeric|min:0',
            'potongan_101_200' => 'required|numeric|min:0',
            'potongan_200_plus' => 'required|numeric|min:0',
            'potongan_siang_31_45' => 'required|numeric|min:0',
            'potongan_siang_46_60' => 'required|numeric|min:0',
            'potongan_siang_61_100' => 'required|numeric|min:0',
            'potongan_siang_101_200' => 'required|numeric|min:0',
            'potongan_siang_200_plus' => 'required|numeric|min:0',
        ]);

        $jabatan = Jabatan::findOrFail($id);
        $jabatan->update([
            'potongan_0_30' => 0, // Always 0 for 0-30 minutes
            'potongan_31_45' => $request->potongan_31_45,
            'potongan_46_60' => $request->potongan_46_60,
            'potongan_61_100' => $request->potongan_61_100,
            'potongan_101_200' => $request->potongan_101_200,
            'potongan_200_plus' => $request->potongan_200_plus,
            'potongan_siang_0_30' => 0, // Always 0 for 0-30 minutes
            'potongan_siang_31_45' => $request->potongan_siang_31_45,
            'potongan_siang_46_60' => $request->potongan_siang_46_60,
            'potongan_siang_61_100' => $request->potongan_siang_61_100,
            'potongan_siang_101_200' => $request->potongan_siang_101_200,
            'potongan_siang_200_plus' => $request->potongan_siang_200_plus,
        ]);

        return redirect()->back()->with('success', 'Pengaturan potongan keterlambatan berhasil diperbarui!');
    }

    /**
     * Update leave settings
     */
    public function updateLibur(Request $request, $id)
    {
        $request->validate([
            'jatah_libur_per_bulan' => 'required|integer|min:0|max:31',
        ]);

        $jabatan = Jabatan::findOrFail($id);
        $jabatan->update([
            'jatah_libur_per_bulan' => $request->jatah_libur_per_bulan,
        ]);

        return redirect()->back()->with('success', 'Pengaturan libur karyawan berhasil diperbarui!');
    }

    /**
     * Update leave penalty settings
     */
    public function updateDendaLibur(Request $request, $id)
    {
        $request->validate([
            'denda_per_hari_libur' => 'required|numeric|min:0',
        ]);

        $jabatan = Jabatan::findOrFail($id);
        $jabatan->update([
            'denda_per_hari_libur' => $request->denda_per_hari_libur,
        ]);

        return redirect()->back()->with('success', 'Pengaturan denda libur berhasil diperbarui!');
    }

    /**
     * Update bonus settings
     */
    public function updateBonus(Request $request, $id)
    {
        $request->validate([
            'bonus_tidak_libur' => 'required|numeric|min:0',
        ]);

        $jabatan = Jabatan::findOrFail($id);
        $jabatan->update([
            'bonus_tidak_libur' => $request->bonus_tidak_libur,
        ]);

        return redirect()->back()->with('success', 'Pengaturan bonus tidak libur berhasil diperbarui!');
    }

    /**
     * Update minimal attendance time settings
     */
    public function updateMinimalAbsen(Request $request, $id)
    {
        $request->validate([
            'minimal_absen_pagi' => 'required|date_format:H:i',
            'minimal_absen_siang' => 'required|date_format:H:i',
            'potongan_absen_awal' => 'required|numeric|min:0',
        ]);

        $jabatan = Jabatan::findOrFail($id);
        $jabatan->update([
            'minimal_absen_pagi' => $request->minimal_absen_pagi . ':00',
            'minimal_absen_siang' => $request->minimal_absen_siang . ':00',
            'potongan_absen_awal' => $request->potongan_absen_awal,
        ]);

        return redirect()->back()->with('success', 'Pengaturan minimal waktu absen berhasil diperbarui!');
    }

    /**
     * Get employee lateness history data
     */
    public function getRiwayatKeterlambatan(Request $request)
    {
        $userId = $request->user_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        
        $user = User::with('jabatan')->find($userId);
        if (!$user) {
            return response()->json(['error' => 'User tidak ditemukan'], 404);
        }
        
        $query = \App\Models\Absensi::where('id_user', $userId)
            ->whereNotNull('jam_masuk');
            
        if ($startDate && $endDate) {
            $query->whereBetween('tanggal_absen', [$startDate, $endDate]);
        }
        
        $absensiData = $query->orderBy('tanggal_absen', 'desc')->get();
        
        $keterlambatanData = [];
        $no = 1;
        
        foreach ($absensiData as $absensi) {
            $jamMasukStandar = $user->jabatan->jam_masuk ?? '08:00:00';
            $jamMasuk = $absensi->jam_masuk;
            
            if ($jamMasuk > $jamMasukStandar) {
                $jamStandarCarbon = \Carbon\Carbon::createFromFormat('H:i:s', $jamMasukStandar);
                $jamMasukCarbon = \Carbon\Carbon::createFromFormat('H:i:s', $jamMasuk);
                $menitTerlambat = $jamMasukCarbon->diffInMinutes($jamStandarCarbon);
                
                // Calculate penalty based on lateness ranges
                $potongan = $this->calculateLatenessPenalty($menitTerlambat, $user->jabatan);
                
                $keterlambatanData[] = [
                    'no' => $no++,
                    'tanggal' => \Carbon\Carbon::parse($absensi->tanggal_absen)->format('d/m/Y'),
                    'jam_masuk' => $jamMasuk,
                    'jam_seharusnya' => $jamMasukStandar,
                    'durasi_terlambat' => $menitTerlambat . ' menit',
                    'potongan' => 'Rp ' . number_format($potongan, 0, ',', '.'),
                    'keterangan' => $absensi->note ?? 'Terlambat'
                ];
            }
        }
        
        return response()->json($keterlambatanData);
    }
    
    /**
     * Get employee excess leave history data
     */
    public function getRiwayatLiburBerlebih(Request $request)
    {
        $userId = $request->user_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        
        $user = User::with('jabatan')->find($userId);
        if (!$user) {
            return response()->json(['error' => 'User tidak ditemukan'], 404);
        }
        
        // Get leave data grouped by month
         $query = \App\Models\SakitIzin::where('id_user', $userId)
             ->where('keterangan', 'Izin');
             
         if ($startDate && $endDate) {
             $query->whereBetween('tanggal', [$startDate, $endDate]);
         }
         
         $leaveData = $query->get();
         $monthlyLeave = [];
         
         foreach ($leaveData as $leave) {
             $month = \Carbon\Carbon::parse($leave->tanggal)->format('Y-m');
             if (!isset($monthlyLeave[$month])) {
                 $monthlyLeave[$month] = [
                     'dates' => [],
                     'total_days' => 0
                 ];
             }
             
             // Each leave record is 1 day
             $monthlyLeave[$month]['total_days'] += 1;
             $monthlyLeave[$month]['dates'][] = \Carbon\Carbon::parse($leave->tanggal)->format('d/m/Y');
         }
        
        $liburBerlebihData = [];
        $no = 1;
        $jatahPerBulan = $user->jabatan->jatah_libur_per_bulan ?? 2;
        $dendaPerHari = $user->jabatan->denda_per_hari_libur ?? 50000;
        
        foreach ($monthlyLeave as $month => $data) {
            if ($data['total_days'] > $jatahPerBulan) {
                $liburBerlebih = $data['total_days'] - $jatahPerBulan;
                $totalDenda = $liburBerlebih * $dendaPerHari;
                
                $liburBerlebihData[] = [
                    'no' => $no++,
                    'bulan' => \Carbon\Carbon::parse($month . '-01')->format('F Y'),
                    'jatah_libur' => $jatahPerBulan . ' hari',
                    'libur_diambil' => $data['total_days'] . ' hari',
                    'libur_berlebih' => $liburBerlebih . ' hari',
                    'denda_per_hari' => 'Rp ' . number_format($dendaPerHari, 0, ',', '.'),
                    'total_denda' => 'Rp ' . number_format($totalDenda, 0, ',', '.'),
                    'detail_tanggal' => implode(', ', $data['dates'])
                ];
            }
        }
        
        return response()->json($liburBerlebihData);
    }
    
    /**
     * Get employee unused leave history data
     */
    public function getRiwayatTidakLibur(Request $request)
    {
        $userId = $request->user_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        
        $user = User::with('jabatan')->find($userId);
        if (!$user) {
            return response()->json(['error' => 'User tidak ditemukan'], 404);
        }
        
        // Calculate months in the date range
        $start = \Carbon\Carbon::parse($startDate ?? '2024-01-01');
        $end = \Carbon\Carbon::parse($endDate ?? now());
        
        $tidakLiburData = [];
        $no = 1;
        $jatahPerBulan = $user->jabatan->jatah_libur_per_bulan ?? 2;
        $bonusPerHari = $user->jabatan->bonus_tidak_libur ?? 25000;
        
        $current = $start->copy()->startOfMonth();
        while ($current <= $end) {
            // Get leave taken in this month
             $leaveTaken = \App\Models\SakitIzin::where('id_user', $userId)
                 ->where('keterangan', 'Izin')
                 ->whereMonth('tanggal', $current->month)
                 ->whereYear('tanggal', $current->year)
                 ->count();
                
            if ($leaveTaken < $jatahPerBulan) {
                $sisaLibur = $jatahPerBulan - $leaveTaken;
                $totalBonus = $sisaLibur * $bonusPerHari;
                
                $tidakLiburData[] = [
                    'no' => $no++,
                    'bulan' => $current->format('F Y'),
                    'jatah_libur' => $jatahPerBulan . ' hari',
                    'libur_diambil' => $leaveTaken . ' hari',
                    'sisa_libur' => $sisaLibur . ' hari',
                    'bonus_per_hari' => 'Rp ' . number_format($bonusPerHari, 0, ',', '.'),
                    'total_bonus' => 'Rp ' . number_format($totalBonus, 0, ',', '.'),
                    'status' => 'Bonus Diterima'
                ];
            }
            
            $current->addMonth();
        }
        
        return response()->json($tidakLiburData);
    }
    
    /**
     * Calculate lateness penalty based on ranges
     */
    private function calculateLatenessPenalty($minutes, $jabatan)
    {
        if ($minutes <= 30) {
            return $jabatan->potongan_0_30 ?? 0;
        } elseif ($minutes <= 45) {
            return $jabatan->potongan_31_45 ?? 0;
        } elseif ($minutes <= 60) {
            return $jabatan->potongan_46_60 ?? 0;
        } elseif ($minutes <= 100) {
            return $jabatan->potongan_61_100 ?? 0;
        } elseif ($minutes <= 200) {
            return $jabatan->potongan_101_200 ?? 0;
        } else {
            return $jabatan->potongan_200_plus ?? 0;
        }
    }

    /**
     * Transfer employees to different position/location
     */
    public function pindahKaryawan(Request $request)
    {
        $request->validate([
            'karyawan_ids' => 'required|array',
            'karyawan_ids.*' => 'exists:users,id',
            'golongan_tujuan' => 'required|exists:jabatans,id',
            'cabang_tujuan' => 'required|exists:cabang,id',
            'golongan_asal' => 'required|exists:jabatans,id',
        ]);

        try {
            DB::beginTransaction();

            // Get the new position's salary
            $jabatanTujuan = Jabatan::find($request->golongan_tujuan);
            $gajiBaruOtomatis = $jabatanTujuan ? $jabatanTujuan->gaji_pokok : 0;

            // Update employee position, location, and salary
            User::whereIn('id', $request->karyawan_ids)
                ->update([
                    'id_jabatan' => $request->golongan_tujuan,
                    'id_cabang' => $request->cabang_tujuan,
                    'gaji' => $gajiBaruOtomatis
                ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Karyawan berhasil dipindahkan dan gaji telah disesuaikan!'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store new bon data
     */
    public function storeBon(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:users,id',
            'jumlah' => 'required|numeric|min:1000',
            'keterangan' => 'nullable|string',
            'tanggal' => 'required|date',
            'jabatan_id' => 'required|exists:jabatans,id',
        ]);

        Bon::create([
            'pegawai_id' => $request->pegawai_id,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
            'tanggal' => $request->tanggal,
            'status' => 'pending',
            'created_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Data bon berhasil ditambahkan!');
    }

    /**
     * Update bon data
     */
    public function updateBon(Request $request, $id)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:users,id',
            'jumlah' => 'required|numeric|min:1000',
            'keterangan' => 'nullable|string',
            'tanggal' => 'required|date',
            'status' => 'required|in:pending,approved,rejected,paid',
        ]);

        $bon = Bon::findOrFail($id);
        $bon->update([
            'pegawai_id' => $request->pegawai_id,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
            'tanggal' => $request->tanggal,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Data bon berhasil diperbarui!');
    }

    /**
     * Delete bon data
     */
    public function destroyBon($id)
    {
        try {
            $bon = Bon::findOrFail($id);
            $bon->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data bon berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
