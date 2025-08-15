<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Jabatan;
use App\Models\Cabang;
use App\Models\BonusGaji;
use App\Models\PotonganGaji;
use App\Models\Bon;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;

class KeuanganController extends Controller
{


    /**
     * Menampilkan halaman untuk menambahkan bonus gaji
     */
    public function bonusGaji(Request $request)
    {
        $query = User::with(['jabatan', 'cabang']);

        if ($request->filled('search')) {
            $query->where('nama_pegawai', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('jabatan')) {
            $query->where('id_jabatan', $request->jabatan);
        }

        if ($request->filled('cabang')) {
            $query->where('id_cabang', $request->cabang);
        }

        $users = $query->get();
        $jabatan = Jabatan::all();
        $cabang = Cabang::all();

        return view('admin.keuangan.bonus_gaji', compact('users', 'jabatan', 'cabang'));
    }

    /**
     * Menyimpan data bonus gaji
     */
    public function storeBonusGaji(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id',
            'jumlah_bonus' => 'required|numeric',
            'bulan_tahun' => 'required|string', // Ubah dari date ke string untuk menerima format YYYY-MM
            'keterangan' => 'nullable|string',
        ]);

        // Konversi format YYYY-MM menjadi tanggal valid (YYYY-MM-01)
        $bulanTahun = $request->bulan_tahun . '-01';

        // Validasi apakah format tanggal valid
        if (!strtotime($bulanTahun)) {
            return redirect()->back()->with('error', 'Format bulan dan tahun tidak valid!');
        }

        BonusGaji::create([
            'id_user' => $request->id_user,
            'jumlah_bonus' => $request->jumlah_bonus,
            'bulan_tahun' => $bulanTahun, // Simpan sebagai YYYY-MM-01
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('keuangan.bonus-gaji')->with('success', 'Bonus gaji berhasil ditambahkan!');
    }

    /**
     * Menampilkan halaman untuk menambahkan potongan gaji
     */
    public function potonganGaji(Request $request)
    {
        $query = User::with(['jabatan', 'cabang']);

        if ($request->filled('search')) {
            $query->where('nama_pegawai', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('jabatan')) {
            $query->where('id_jabatan', $request->jabatan);
        }

        if ($request->filled('cabang')) {
            $query->where('id_cabang', $request->cabang);
        }

        $users = $query->get();
        $jabatan = Jabatan::all();
        $cabang = Cabang::all();

        return view('admin.keuangan.potongan_gaji', compact('users', 'jabatan', 'cabang'));
    }

    /**
     * Menyimpan data potongan gaji
     */
    public function storePotonganGaji(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id',
            'jumlah_potongan' => 'required|numeric',
            'bulan_tahun' => 'required|string', // Ubah dari date ke string untuk menerima format YYYY-MM
            'keterangan' => 'nullable|string',
        ]);

        // Konversi format YYYY-MM menjadi tanggal valid (YYYY-MM-01)
        $bulanTahun = $request->bulan_tahun . '-01';

        // Validasi apakah format tanggal valid
        if (!strtotime($bulanTahun)) {
            return redirect()->back()->with('error', 'Format bulan dan tahun tidak valid!');
        }

        PotonganGaji::create([
            'id_user' => $request->id_user,
            'jumlah_potongan' => $request->jumlah_potongan,
            'bulan_tahun' => $bulanTahun, // Simpan sebagai YYYY-MM-01
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('keuangan.potongan-gaji')->with('success', 'Potongan gaji berhasil ditambahkan!');
    }

    /**
     * Menampilkan halaman penggajian terintegrasi
     */
    public function penggajian(Request $request)
    {
        // Ambil filter bulan dan tahun dari request, default ke bulan dan tahun saat ini
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));

        $users = User::with(['jabatan', 'cabang'])->where('role', '!=', 'manager')->get();

        // Filter bonus dan potongan berdasarkan bulan dan tahun
        $bonusData = BonusGaji::with(['user.jabatan'])
            ->whereMonth('bulan_tahun', $bulan)
            ->whereYear('bulan_tahun', $tahun)
            ->latest()->get();

        $potonganData = PotonganGaji::with(['user.jabatan'])
            ->whereMonth('bulan_tahun', $bulan)
            ->whereYear('bulan_tahun', $tahun)
            ->latest()->get();

        // Hitung data penggajian untuk setiap karyawan berdasarkan bulan dan tahun yang dipilih
        $penggajianData = [];
        foreach ($users as $user) {
            $gajiPokok = $user->jabatan->gaji_pokok ?? 0;

            // Total bonus untuk bulan dan tahun yang dipilih
            $totalBonus = BonusGaji::where('id_user', $user->id)
                ->whereMonth('bulan_tahun', $bulan)
                ->whereYear('bulan_tahun', $tahun)
                ->sum('jumlah_bonus');

            // Total potongan manual untuk bulan dan tahun yang dipilih
            $totalPotonganManual = PotonganGaji::where('id_user', $user->id)
                ->whereMonth('bulan_tahun', $bulan)
                ->whereYear('bulan_tahun', $tahun)
                ->sum('jumlah_potongan');

            // Hitung keterlambatan dan absen awal dalam bulan yang dipilih
            $absensiData = \App\Models\Absensi::where('id_user', $user->id)
                ->whereMonth('tanggal_absen', $bulan)
                ->whereYear('tanggal_absen', $tahun)
                ->get();

            $totalKeterlambatan = 0;
            $detailKeterlambatan = [];
            $totalAbsenAwal = 0;
            $detailAbsenAwal = [];

            foreach ($absensiData as $absensi) {
                $jamKerjaStandar = '08:00:00';
                $jamMasuk = $absensi->jam_masuk;

                if ($jamMasuk) {
                    // Cek keterlambatan
                    if ($jamMasuk > $jamKerjaStandar) {
                        $jamKerjaStandarCarbon = \Carbon\Carbon::createFromFormat('H:i:s', $jamKerjaStandar);
                        $jamMasukCarbon = \Carbon\Carbon::createFromFormat('H:i:s', $jamMasuk);
                        $menitTerlambat = $jamMasukCarbon->diffInMinutes($jamKerjaStandarCarbon);

                        $totalKeterlambatan += $menitTerlambat;
                        $detailKeterlambatan[] = [
                            'tanggal' => $absensi->tanggal_absen,
                            'jam_masuk' => $jamMasuk,
                            'menit_terlambat' => $menitTerlambat
                        ];
                    }
                    
                    // Cek absen terlalu awal (pagi)
                    $minimalAbsenPagi = $user->jabatan->minimal_absen_pagi ?? '07:00:00';
                    if ($jamMasuk < $minimalAbsenPagi) {
                        $totalAbsenAwal++;
                        $detailAbsenAwal[] = [
                            'tanggal' => $absensi->tanggal_absen,
                            'jam_masuk' => $jamMasuk,
                            'minimal_jam' => $minimalAbsenPagi,
                            'tipe' => 'pagi'
                        ];
                    }
                }
                
                // Cek absen siang terlalu awal
                $jamPulang = $absensi->jam_pulang;
                if ($jamPulang) {
                    $minimalAbsenSiang = $user->jabatan->minimal_absen_siang ?? '12:00:00';
                    if ($jamPulang < $minimalAbsenSiang) {
                        $totalAbsenAwal++;
                        $detailAbsenAwal[] = [
                            'tanggal' => $absensi->tanggal_absen,
                            'jam_pulang' => $jamPulang,
                            'minimal_jam' => $minimalAbsenSiang,
                            'tipe' => 'siang'
                        ];
                    }
                }
            }

            // Hitung potongan keterlambatan
            $potonganPerMenit = $user->jabatan->potongan_keterlambatan ?? 0;
            $totalPotonganKeterlambatan = $totalKeterlambatan * $potonganPerMenit;
            
            // Hitung potongan absen awal
            $potonganAbsenAwal = $user->jabatan->potongan_absen_awal ?? 0;
            $totalPotonganAbsenAwal = $totalAbsenAwal * $potonganAbsenAwal;

            $totalPotongan = $totalPotonganManual + $totalPotonganKeterlambatan + $totalPotonganAbsenAwal;
            $gajiBersih = $gajiPokok + $totalBonus - $totalPotongan;

            $penggajianData[] = [
                'user' => $user,
                'gaji_pokok' => $gajiPokok,
                'total_bonus' => $totalBonus,
                'total_potongan_manual' => $totalPotonganManual,
                'total_keterlambatan' => $totalKeterlambatan,
                'total_potongan_keterlambatan' => $totalPotonganKeterlambatan,
                'total_absen_awal' => $totalAbsenAwal,
                'total_potongan_absen_awal' => $totalPotonganAbsenAwal,
                'total_potongan' => $totalPotongan,
                'gaji_bersih' => $gajiBersih,
                'detail_keterlambatan' => $detailKeterlambatan,
                'detail_absen_awal' => $detailAbsenAwal
            ];
        }

        return view('admin.keuangan.penggajian', compact('users', 'bonusData', 'potonganData', 'penggajianData', 'bulan', 'tahun'));
    }

    /**
     * Menyimpan data penggajian
     */
    public function storePenggajian(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id',
            'periode' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        // Logic untuk menyimpan data penggajian bisa ditambahkan di sini
        // Misalnya membuat record di tabel penggajian atau update status

        return redirect()->route('keuangan.penggajian')->with('success', 'Data penggajian berhasil ditambahkan!');
    }

    /**
     * Menampilkan laporan potongan gaji dengan akumulasi keterlambatan
     */
    public function laporanPotonganGaji(Request $request)
    {
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));
        $search = $request->get('search');

        // Auto-sync attendance data from fingerprint before generating report
        try {
            $fingerprintService = app(\App\Services\FingerprintService::class);
            $totalSynced = 0;
            
            // Sync data for all branches
            $cabangList = \App\Models\Cabang::all();
            foreach ($cabangList as $cabang) {
                $syncResult = $fingerprintService->syncAttendanceData($cabang->id);
                $totalSynced += $syncResult;
            }
            
            // Log the sync result
            \Log::info('Auto-sync for salary deduction report', [
                'total_synced' => $totalSynced,
                'branches_count' => $cabangList->count(),
                'month' => $bulan,
                'year' => $tahun,
                'timestamp' => now()
            ]);
        } catch (\Exception $e) {
            \Log::error('Auto-sync failed for salary deduction report', [
                'error' => $e->getMessage(),
                'month' => $bulan,
                'year' => $tahun,
                'timestamp' => now()
            ]);
        }

        // Query untuk mendapatkan data karyawan dengan relasi
        $query = User::with(['jabatan', 'cabang'])
            ->where('role', '!=', 'manager');

        // Filter pencarian nama
        if ($search) {
            $query->where('nama_pegawai', 'like', '%' . $search . '%');
        }

        $users = $query->get();

        // Proses data untuk setiap karyawan
        $laporanData = [];
        foreach ($users as $user) {
            // Hitung keterlambatan dalam bulan yang dipilih
            $absensiData = \App\Models\Absensi::where('id_user', $user->id)
                ->whereMonth('tanggal_absen', $bulan)
                ->whereYear('tanggal_absen', $tahun)
                ->get();

            $totalKeterlambatan = 0;
            $jumlahHariTerlambat = 0;

            foreach ($absensiData as $absensi) {
                $jamKerjaStandar = '08:00:00';
                $jamMasuk = $absensi->jam_masuk;

                if ($jamMasuk && $jamMasuk > $jamKerjaStandar) {
                    $jamKerjaStandarCarbon = \Carbon\Carbon::createFromFormat('H:i:s', $jamKerjaStandar);
                    $jamMasukCarbon = \Carbon\Carbon::createFromFormat('H:i:s', $jamMasuk);
                    $menitTerlambat = $jamMasukCarbon->diffInMinutes($jamKerjaStandarCarbon);

                    $totalKeterlambatan += $menitTerlambat;
                    $jumlahHariTerlambat++;
                }
            }

            // Hitung potongan berdasarkan sistem rentang keterlambatan baru
            $totalPotonganKeterlambatan = 0;

            // Hitung potongan untuk setiap hari terlambat berdasarkan rentang
            foreach ($absensiData as $absensi) {
                $jamKerjaStandar = '08:00:00';
                $jamMasuk = $absensi->jam_masuk;

                if ($jamMasuk && $jamMasuk > $jamKerjaStandar) {
                    $jamKerjaStandarCarbon = \Carbon\Carbon::createFromFormat('H:i:s', $jamKerjaStandar);
                    $jamMasukCarbon = \Carbon\Carbon::createFromFormat('H:i:s', $jamMasuk);
                    $menitTerlambat = $jamMasukCarbon->diffInMinutes($jamKerjaStandarCarbon);

                    // Hitung denda berdasarkan potongan per menit
                    $potonganPerMenit = $user->jabatan->potongan_keterlambatan ?? 0;
                    $dendaHarian = $menitTerlambat * $potonganPerMenit;
                    $totalPotonganKeterlambatan += $dendaHarian;
                }
            }
            
            // Hitung potongan absen awal
            $totalAbsenAwal = 0;
            $totalPotonganAbsenAwal = 0;
            
            foreach ($absensiData as $absensi) {
                $jamMasuk = $absensi->jam_masuk;
                $jamPulang = $absensi->jam_pulang;
                
                // Cek absen pagi terlalu awal
                if ($jamMasuk) {
                    $minimalAbsenPagi = $user->jabatan->minimal_absen_pagi ?? '07:00:00';
                    if ($jamMasuk < $minimalAbsenPagi) {
                        $totalAbsenAwal++;
                    }
                }
                
                // Cek absen siang terlalu awal
                if ($jamPulang) {
                    $minimalAbsenSiang = $user->jabatan->minimal_absen_siang ?? '12:00:00';
                    if ($jamPulang < $minimalAbsenSiang) {
                        $totalAbsenAwal++;
                    }
                }
            }
            
            $potonganAbsenAwal = $user->jabatan->potongan_absen_awal ?? 0;
            $totalPotonganAbsenAwal = $totalAbsenAwal * $potonganAbsenAwal;

            // Ambil potongan manual dari admin
            $potonganManual = \App\Models\PotonganGaji::where('id_user', $user->id)
                ->whereMonth('bulan_tahun', $bulan)
                ->whereYear('bulan_tahun', $tahun)
                ->sum('jumlah_potongan');

            // Ambil bonus dari admin
            $bonusGaji = \App\Models\BonusGaji::where('id_user', $user->id)
                ->whereMonth('bulan_tahun', $bulan)
                ->whereYear('bulan_tahun', $tahun)
                ->sum('jumlah_bonus');

            // Ambil total bon karyawan untuk bulan dan tahun yang dipilih
            $totalBon = \App\Models\Bon::where('pegawai_id', $user->id)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->where('status', 'approved')
                ->sum('jumlah');

            $totalPotongan = $totalPotonganKeterlambatan + $potonganManual + $totalBon + $totalPotonganAbsenAwal;

            $laporanData[] = [
                'user' => $user,
                'total_menit_terlambat' => $totalKeterlambatan,
                'jumlah_hari_terlambat' => $jumlahHariTerlambat,
                'potongan_keterlambatan' => $totalPotonganKeterlambatan,
                'total_absen_awal' => $totalAbsenAwal,
                'potongan_absen_awal' => $totalPotonganAbsenAwal,
                'potongan_manual' => $potonganManual,
                'total_bon' => $totalBon,
                'total_potongan' => $totalPotongan,
                'bonus_gaji' => $bonusGaji,
                'gaji_bersih' => ($user->jabatan->gaji_pokok ?? 0) - $totalPotongan + $bonusGaji
            ];
        }

        return view('admin.keuangan.laporan_potongan_gaji', compact('laporanData', 'bulan', 'tahun', 'search'));
    }

    /**
     * Menampilkan detail keterlambatan karyawan
     */
    public function detailKeterlambatan(Request $request)
    {
        $userId = $request->user_id;
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $user = User::with('jabatan')->find($userId);

        if (!$user) {
            return response()->json(['error' => 'User tidak ditemukan'], 404);
        }

        // Ambil data absensi untuk bulan dan tahun yang dipilih
        $absensiData = \App\Models\Absensi::where('id_user', $userId)
            ->whereMonth('tanggal_absen', $bulan)
            ->whereYear('tanggal_absen', $tahun)
            ->orderBy('tanggal_absen', 'asc')
            ->get();

        $detailKeterlambatan = [];
        $totalMenitTerlambat = 0;
        $jumlahHariTerlambat = 0;

        foreach ($absensiData as $absensi) {
            $jamKerjaStandar = '08:00:00';
            $jamMasuk = $absensi->jam_masuk;

            if ($jamMasuk && $jamMasuk > $jamKerjaStandar) {
                $jamKerjaStandarCarbon = \Carbon\Carbon::createFromFormat('H:i:s', $jamKerjaStandar);
                $jamMasukCarbon = \Carbon\Carbon::createFromFormat('H:i:s', $jamMasuk);
                $menitTerlambat = $jamMasukCarbon->diffInMinutes($jamKerjaStandarCarbon);

                // Hitung denda berdasarkan potongan per menit
                $potonganPerMenit = $user->jabatan->potongan_keterlambatan ?? 0;
                $dendaHarian = $menitTerlambat * $potonganPerMenit;

                $detailKeterlambatan[] = [
                    'tanggal' => \Carbon\Carbon::parse($absensi->tanggal_absen)->format('d/m/Y'),
                    'jam_masuk' => $jamMasuk,
                    'jam_standar' => $jamKerjaStandar,
                    'menit_terlambat' => $menitTerlambat,
                    'potongan' => $dendaHarian,
                ];

                $totalMenitTerlambat += $menitTerlambat;
                $jumlahHariTerlambat++;
            }
        }

        // Hitung total potongan berdasarkan sistem rentang baru
        $totalPotonganKeterlambatan = array_sum(array_column($detailKeterlambatan, 'potongan'));
        $potonganPerMenit = $user->jabatan->potongan_keterlambatan ?? 0; // Untuk kompatibilitas

        return response()->json([
            'user' => $user,
            'detail_keterlambatan' => $detailKeterlambatan,
            'total_menit_terlambat' => $totalMenitTerlambat,
            'jumlah_hari_terlambat' => $jumlahHariTerlambat,
            'potongan_per_menit' => $potonganPerMenit,
            'total_potongan_keterlambatan' => $totalPotonganKeterlambatan,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ]);
    }

    /**
     * Display a listing of bon.
     */
    public function bonIndex(Request $request)
    {
        // Query untuk users dengan filter
        $query = User::with(['jabatan', 'cabang'])->where('role', '!=', 'manager');

        if ($request->filled('search')) {
            $query->where('nama_pegawai', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('jabatan')) {
            $query->where('id_jabatan', $request->jabatan);
        }

        if ($request->filled('cabang')) {
            $query->where('id_cabang', $request->cabang);
        }

        $users = $query->with(['jabatan', 'cabang'])->get();
        $jabatan = Jabatan::all();
        $cabang = Cabang::all();

        return view('admin.keuangan.bon_index', compact('users', 'jabatan', 'cabang'));
    }

    /**
     * Show the form for creating a new bon.
     */
    public function bonCreate()
    {
        $pegawais = User::where('role', '!=', 'manager')->with(['jabatan', 'cabang'])->get();
        return view('admin.keuangan.bon_create', compact('pegawais'));
    }

    /**
     * Store a newly created bon in storage.
     */
    public function bonStore(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:users,id',
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'required|string|max:255',
            'tanggal' => 'required|date',
        ]);

        Bon::create([
            'pegawai_id' => $request->pegawai_id,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
            'tanggal' => $request->tanggal,
            'status' => 'approved',
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('keuangan.bon.index')->with('success', 'Bon berhasil ditambahkan');
    }

    /**
     * Display the specified bon.
     */
    public function bonShow(string $id)
    {
        $bon = Bon::with('pegawai')->findOrFail($id);
        return view('admin.keuangan.bon_show', compact('bon'));
    }

    /**
     * Show the form for editing the specified bon.
     */
    public function bonEdit(string $id)
    {
        $bon = Bon::findOrFail($id);
        $pegawais = User::where('role', '!=', 'manager')->with(['jabatan', 'cabang'])->get();
        return view('admin.keuangan.bon_edit', compact('bon', 'pegawais'));
    }

    /**
     * Update the specified bon in storage.
     */
    public function bonUpdate(Request $request, string $id)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:users,id',
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'required|string|max:255',
            'tanggal' => 'required|date',
        ]);

        $bon = Bon::findOrFail($id);
        $bon->update([
            'pegawai_id' => $request->pegawai_id,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
            'tanggal' => $request->tanggal,
            'status' => 'approved',
        ]);

        return redirect()->route('keuangan.bon.index')->with('success', 'Bon berhasil diupdate');
    }

    /**
     * Remove the specified bon from storage.
     */
    public function bonDestroy(string $id)
    {
        $bon = Bon::findOrFail($id);
        $bon->delete();

        return redirect()->route('keuangan.bon.index')->with('success', 'Bon berhasil dihapus');
    }

    /**
     * Update salary data (manual deductions and bonuses)
     */
    public function updateGaji(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'potongan_manual' => 'required|numeric|min:0',
            'bonus_gaji' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:255',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020|max:2030',
        ]);

        try {
            // Update atau create potongan manual
            \App\Models\PotonganGaji::updateOrCreate(
                [
                    'id_user' => $request->user_id,
                    'bulan_tahun' => $request->tahun . '-' . str_pad($request->bulan, 2, '0', STR_PAD_LEFT) . '-01'
                ],
                [
                    'jumlah_potongan' => $request->potongan_manual,
                    'keterangan' => $request->keterangan ?? 'Update manual dari laporan potongan gaji',
                    'created_by' => Auth::id(),
                ]
            );

            // Update atau create bonus gaji
            \App\Models\BonusGaji::updateOrCreate(
                [
                    'id_user' => $request->user_id,
                    'bulan_tahun' => $request->tahun . '-' . str_pad($request->bulan, 2, '0', STR_PAD_LEFT) . '-01'
                ],
                [
                    'jumlah_bonus' => $request->bonus_gaji,
                    'keterangan' => $request->keterangan ?? 'Update manual dari laporan potongan gaji',
                    'created_by' => Auth::id(),
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Data gaji berhasil diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
