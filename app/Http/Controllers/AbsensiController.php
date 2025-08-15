<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\User;
use App\Models\Cabang;
use App\Models\PengaturanLibur;
use App\Models\PengaturanHariLiburMingguan;
use App\Services\FingerprintService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;

class AbsensiController extends Controller
{
    protected $fingerprintService;
    
    public function __construct(FingerprintService $fingerprintService)
    {
        $this->fingerprintService = $fingerprintService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Auto-sync fingerprint data before displaying
        try {
            Log::info("Starting auto-sync for user: {$user->nama_pegawai} (role: {$user->role})");
            
            if ($user->role === 'manager') {
                // Manager: sync all active branches
                $syncedCount = $this->fingerprintService->syncAllDevices();
                Log::info("Manager auto-sync completed: {$syncedCount} records synced");
            } else {
                // Admin: sync only their branch
                if ($user->id_cabang) {
                    $syncedCount = $this->fingerprintService->syncAttendanceData($user->id_cabang);
                    Log::info("Admin auto-sync completed for cabang {$user->id_cabang}: {$syncedCount} records synced");
                }
            }
        } catch (\Exception $e) {
            Log::warning('Auto-sync failed: ' . $e->getMessage());
        }

        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));
        $cabangId = $request->input('cabang');

        // Create date range for the selected month and year
        $startDate = "$year-$month-01";
        $endDate = date('Y-m-t', strtotime($startDate));

        // Query to get attendance data with user relationship
        $query = Absensi::with(['user.cabang', 'user.jabatan'])
            ->whereBetween('tanggal_absen', [$startDate, $endDate]);

        // Filter by branch/location if selected and user permission
        if ($user->role === 'admin') {
            // Admin only sees their branch data
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('id_cabang', $user->id_cabang);
            });
        } elseif ($cabangId) {
            // Manager can filter by specific branch
            $query->whereHas('user', function ($q) use ($cabangId) {
                $q->where('id_cabang', $cabangId);
            });
        }

        $absensi = $query->get();
        $pegawai = User::latest()->get();

        // Get available branches based on user role
        if ($user->role === 'manager') {
            $cabangs = Cabang::all();
        } else {
            $cabangs = Cabang::where('id', $user->id_cabang)->get();
        }

        return view('admin.absensi.index', compact('absensi', 'pegawai', 'year', 'month', 'cabangId', 'cabangs'));
    }

    /**
     * Display fingerprint attendance page with employee data.
     */
    public function fingerprint(Request $request)
    {
        $searchName = $request->input('search-name');
        $searchCabang = $request->input('search-cabang');
        $selectedMonth = $request->input('search-month', date('m'));
        $selectedYear = $request->input('search-year', date('Y'));

        // Query to get employee data with filtering
        $query = User::where('role', '!=', 'manager');

        // Filter by name if provided
        if ($searchName) {
            $query->where('nama_pegawai', 'like', "%$searchName%");
        }

        // Filter by branch/location if selected
        if ($searchCabang) {
            $query->where('id_cabang', $searchCabang);
        }

        $pegawai = $query->get();

        return view('admin.absensi.fingerprint', compact('pegawai'));
    }

    /**
     * Export attendance data to CSV.
     */
    public function export(Request $request)
    {
        $searchName = $request->input('search-name');
        $searchCabang = $request->input('search-cabang');
        $selectedMonth = $request->input('search-month', date('m'));
        $selectedYear = $request->input('search-year', date('Y'));

        // Query to get employee data with filtering
        $query = User::where('role', '!=', 'manager');

        // Filter by name if provided
        if ($searchName) {
            $query->where('nama_pegawai', 'like', "%$searchName%");
        }

        // Filter by branch/location if selected
        if ($searchCabang) {
            $query->where('id_cabang', $searchCabang);
        }

        $pegawai = $query->get();

        // Create CSV content
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $selectedMonth, $selectedYear);
        $monthName = date('F', mktime(0, 0, 0, $selectedMonth, 1, $selectedYear));

        $headers = ['Nama Karyawan'];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $headers[] = $day . ' (Pagi)';
            $headers[] = $day . ' (Sore)';
        }

        $csvContent = implode(',', $headers) . "\n";

        foreach ($pegawai as $data) {
            $row = [$data->nama_pegawai];

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = "$selectedYear-$selectedMonth-" . str_pad($day, 2, '0', STR_PAD_LEFT);

                $absensi = Absensi::where('id_user', $data->id)
                    ->where('tanggal_absen', $date)
                    ->first();

                $row[] = $absensi ? Carbon::parse($absensi->jam_masuk)->format('H:i') : '-';
                $row[] = $absensi && $absensi->jam_masuk_sore ? Carbon::parse($absensi->jam_masuk_sore)->format('H:i') : '-';
            }

            $csvContent .= implode(',', $row) . "\n";
        }

        $filename = "absensi_$monthName-$selectedYear.csv";

        return Response::make($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\""
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $absensi = Absensi::all();
        $pegawai = User::where('role', '!=', 'manager')->get();
        return view('admin.absensi.index', compact('absensi', 'pegawai'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');

        // Validate fingerprint data
        $request->validate([
            'id_user' => 'required|exists:users,id',
            'fingerprint_data' => 'sometimes',  // This would be actual fingerprint data in a real system
        ]);

        // Check if user exists
        $pegawai = User::find($request->id_user);
        if (!$pegawai) {
            return redirect()->route('absensi.index')->with('error', 'User tidak ditemukan!');
        }

        // Get current time
        $currentTime = now();
        $currentHour = (int)$currentTime->format('H');

        // Determine if this is morning or evening check-in
        $todayAbsen = Absensi::where('id_user', $pegawai->id)
            ->whereDate('tanggal_absen', $currentTime->format('Y-m-d'))
            ->first();

        if (!$todayAbsen) {
            // Morning check-in (first check-in of the day)
            Absensi::create([
                'id_user' => $request->id_user,
                'tanggal_absen' => $currentTime->format('Y-m-d'),
                'jam_masuk' => $currentTime->format('H:i'),
                'status' => 'Hadir',
            ]);

            return redirect()->route('absensi.index')->with('success', 'Morning fingerprint check-in recorded successfully!');
        } elseif ($currentHour >= 12 && !$todayAbsen->jam_masuk_sore) {
            // Evening check-in (afternoon)
            $todayAbsen->jam_masuk_sore = $currentTime->format('H:i');
            $todayAbsen->save();

            return redirect()->route('absensi.index')->with('success', 'Evening fingerprint check-in recorded successfully!');
        } else {
            return redirect()->route('absensi.index')->with('warning', 'You have already checked in for this time period!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $absensi = Absensi::findOrFail($id);
        $pegawai = User::all();
        return view('admin.absensi.index', compact('absensi', 'pegawai'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $absensi = Absensi::find($id);

        if ($absensi && is_null($absensi->jam_keluar)) {
            $absensi->jam_keluar = Carbon::now()->setTimezone('Asia/Jakarta');
            $absensi->save();

            Session::forget('absen_masuk');
            Session::put('absen_keluar', true);
            return redirect()->route('absensi.index')->with('success', 'Absen Pulang berhasil disimpan!');
        }
        return redirect()->route('absensi.index')->with('error', 'Absen Pulang gagal disimpan.');
    }
    /**
     * Get attendance data for AJAX request
     */
    public function getAttendanceData(Request $request)
    {
        $user = auth()->user();
        
        // Auto-sync fingerprint data before getting data
        try {
            Log::info("Starting auto-sync in getAttendanceData for user: {$user->nama_pegawai} (role: {$user->role})");
            
            if ($user->role === 'manager') {
                // Manager: sync all active branches
                $syncedCount = $this->fingerprintService->syncAllDevices();
                Log::info("Manager auto-sync in getAttendanceData completed: {$syncedCount} records synced");
            } else {
                // Admin: sync only their branch
                if ($user->id_cabang) {
                    $syncedCount = $this->fingerprintService->syncAttendanceData($user->id_cabang);
                    Log::info("Admin auto-sync in getAttendanceData completed for cabang {$user->id_cabang}: {$syncedCount} records synced");
                }
            }
        } catch (\Exception $e) {
            Log::warning('Auto-sync failed in getAttendanceData: ' . $e->getMessage());
        }

        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));
        $cabangId = $request->input('cabang');

        // Create date range for the selected month and year
        $startDate = "$year-$month-01";
        $endDate = date('Y-m-t', strtotime($startDate));

        // Query to get employees with their attendance data
        $query = User::where('role', '!=', 'manager')
            ->with(['cabang', 'jabatan']);

        // Filter by branch based on user role
        if ($user->role === 'admin') {
            // Admin only sees their branch employees
            $query->where('id_cabang', $user->id_cabang);
        } elseif ($cabangId) {
            // Manager can filter by specific branch
            $query->where('id_cabang', $cabangId);
        }

        $employees = $query->get();

        // Get attendance data for the month
        $attendanceQuery = Absensi::whereBetween('tanggal_absen', [$startDate, $endDate]);

        if ($user->role === 'admin') {
            $attendanceQuery->whereHas('user', function ($q) use ($user) {
                $q->where('id_cabang', $user->id_cabang);
            });
        } elseif ($cabangId) {
            $attendanceQuery->whereHas('user', function ($q) use ($cabangId) {
                $q->where('id_cabang', $cabangId);
            });
        }

        $attendanceData = $attendanceQuery->get()->groupBy('id_user');

        // Get holiday settings for the month
        $holidaySettings = PengaturanLibur::where('is_active', true)
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal_libur', [$startDate, $endDate])
                      ->orWhere(function($q) use ($startDate, $endDate) {
                          $q->where('tanggal_libur', '<=', $endDate)
                            ->where(function($subQ) use ($startDate) {
                                $subQ->whereNull('tanggal_selesai')
                                     ->orWhere('tanggal_selesai', '>=', $startDate);
                            });
                      });
            })
            ->get();

        $holidays = [];
        foreach ($holidaySettings as $holiday) {
            if ($holiday->tanggal_selesai) {
                // Range holiday
                $start = max($holiday->tanggal_libur->format('Y-m-d'), $startDate);
                $end = min($holiday->tanggal_selesai->format('Y-m-d'), $endDate);
                
                $current = $start;
                while ($current <= $end) {
                    $holidays[] = $current;
                    $current = date('Y-m-d', strtotime($current . ' +1 day'));
                }
            } else {
                // Single day holiday
                $holidayDate = $holiday->tanggal_libur->format('Y-m-d');
                if ($holidayDate >= $startDate && $holidayDate <= $endDate) {
                    $holidays[] = $holidayDate;
                }
            }
        }
        $holidays = array_unique($holidays);

        $weeklyHolidays = PengaturanHariLiburMingguan::where('is_libur', true)
            ->pluck('hari')
            ->toArray();



        // Calculate days in month
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $result = [];
        foreach ($employees as $employee) {
            $employeeData = [
                'id' => $employee->id,
                'nama' => $employee->nama_pegawai,
                'cabang' => $employee->cabang ? $employee->cabang->nama_cabang : '-',
                'golongan' => $employee->jabatan ? $employee->jabatan->nama_jabatan : '-',
                'device_user_id' => $employee->device_user_id,
                'attendance' => []
            ];

            // Get attendance for each day
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = "$year-$month-" . str_pad($day, 2, '0', STR_PAD_LEFT);
                $dayName = date('D', strtotime($date));
                $dayNameIndonesian = $this->getDayNameIndonesian($date);

                $attendance = null;
                if (isset($attendanceData[$employee->id])) {
                    $attendance = $attendanceData[$employee->id]->where('tanggal_absen', $date)->first();
                }

                // Check if this date is a holiday (special holidays or weekly holidays)
                $isSpecialHoliday = in_array($date, $holidays);
                $isWeeklyHoliday = in_array($dayNameIndonesian, $weeklyHolidays);
                $isHoliday = $isSpecialHoliday || $isWeeklyHoliday;
                
                // Weekend is now determined by weekly holiday settings, not hardcoded
                $isWeekend = $isWeeklyHoliday;

                $employeeData['attendance'][$day] = [
                    'date' => $date,
                    'day_name' => $dayName,
                    'jam_masuk' => $attendance ? $attendance->jam_masuk : null,
                    'jam_masuk_sore' => $attendance ? $attendance->jam_masuk_sore : null,
                    'status' => $attendance ? $attendance->status : null,
                    'keterangan' => $attendance ? $attendance->note : null,
                    'is_weekend' => $isWeekend,
                    'is_holiday' => $isHoliday,
                    'source' => $attendance && $attendance->note === 'Auto-sync from fingerprint' ? 'Fingerprint' : 'Manual'
                ];
            }

            $result[] = $employeeData;
        }

        return response()->json($result);
    }

    /**
     * Get branches for dropdown
     */
    public function getBranches()
    {
        $user = auth()->user();
        
        if ($user->role === 'manager') {
            $branches = Cabang::all();
        } else {
            $branches = Cabang::where('id', $user->id_cabang)->get();
        }
        
        return response()->json($branches);
    }

    /**
     * Manual sync fingerprint data
     */
    public function syncFingerprint(Request $request)
    {
        $user = auth()->user();
        $cabangId = $request->input('cabang_id');
        
        try {
            if ($user->role === 'manager') {
                if ($cabangId) {
                    // Sync specific branch
                    $syncedCount = $this->fingerprintService->syncAttendanceData($cabangId);
                    $message = "Berhasil sinkronisasi {$syncedCount} data absensi untuk cabang yang dipilih";
                } else {
                    // Sync all branches
                    $syncedCount = $this->fingerprintService->syncAllDevices();
                    $message = "Berhasil sinkronisasi {$syncedCount} data absensi dari semua cabang";
                }
            } else {
                // Admin can only sync their branch
                $syncedCount = $this->fingerprintService->syncAttendanceData($user->id_cabang);
                $message = "Berhasil sinkronisasi {$syncedCount} data absensi";
            }
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'synced_count' => $syncedCount
            ]);
            
        } catch (\Exception $e) {
            Log::error('Manual sync failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan sinkronisasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get sync status
     */
    public function getSyncStatus()
    {
        $user = auth()->user();
        
        if ($user->role === 'manager') {
            $cabangs = Cabang::where('fingerprint_active', true)->get();
        } else {
            $cabangs = Cabang::where('id', $user->id_cabang)
                           ->where('fingerprint_active', true)
                           ->get();
        }
        
        $status = [];
        
        foreach ($cabangs as $cabang) {
            $lastSync = $cabang->last_sync ? 
                Carbon::parse($cabang->last_sync)->diffForHumans() : 
                'Belum pernah sync';
                
            $status[] = [
                'id' => $cabang->id,
                'nama_cabang' => $cabang->nama_cabang,
                'fingerprint_ip' => $cabang->fingerprint_ip,
                'fingerprint_active' => $cabang->fingerprint_active,
                'last_sync' => $lastSync,
                'last_sync_raw' => $cabang->last_sync
            ];
        }
        
        return response()->json($status);
    }

    /**
     * Helper method to get Indonesian day name
     */
    private function getDayNameIndonesian($date)
    {
        $dayMapping = [
            'Monday' => 'senin',
            'Tuesday' => 'selasa',
            'Wednesday' => 'rabu',
            'Thursday' => 'kamis',
            'Friday' => 'jumat',
            'Saturday' => 'sabtu',
            'Sunday' => 'minggu'
        ];

        $englishDayName = date('l', strtotime($date));
        return $dayMapping[$englishDayName] ?? 'senin';
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
