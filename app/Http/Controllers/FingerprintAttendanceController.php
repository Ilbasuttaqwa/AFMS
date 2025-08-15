<?php

namespace App\Http\Controllers;

use App\Models\FingerprintAttendance;
use App\Models\User;
use App\Models\Absensi;
use App\Services\FingerprintService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FingerprintAttendanceController extends Controller
{
    protected $fingerprintService;

    public function __construct(FingerprintService $fingerprintService)
    {
        $this->fingerprintService = $fingerprintService;
    }

    /**
     * Endpoint untuk menerima data dari mesin fingerprint
     */
    public function receiveAttendance(Request $request)
    {
        try {
            // Validasi data yang diterima
            $validated = $request->validate([
                'device_user_id' => 'required|string',
                'device_ip' => 'required|ip',
                'attendance_time' => 'required|date',
                'attendance_type' => 'required|integer|in:1,2,3,4',
                'verification_type' => 'nullable|integer',
                'raw_data' => 'nullable|string'
            ]);

            // Simpan data fingerprint mentah
            $fingerprintAttendance = FingerprintAttendance::create([
                'device_user_id' => $validated['device_user_id'],
                'device_ip' => $validated['device_ip'],
                'attendance_time' => Carbon::parse($validated['attendance_time']),
                'attendance_type' => $validated['attendance_type'],
                'verification_type' => $validated['verification_type'] ?? 1,
                'is_processed' => false,
                'raw_data' => $validated['raw_data'] ?? json_encode($request->all())
            ]);

            // Proses data fingerprint menjadi absensi
            $this->fingerprintService->processAttendance($fingerprintAttendance);

            return response()->json([
                'status' => 'success',
                'message' => 'Attendance data received and processed',
                'data' => $fingerprintAttendance
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error receiving fingerprint attendance: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process attendance data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan daftar data fingerprint attendance
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Hanya manager yang bisa melihat semua data
        if ($user->role !== 'manager') {
            abort(403, 'Unauthorized');
        }

        $query = FingerprintAttendance::with(['user', 'cabang'])
            ->orderBy('attendance_time', 'desc');

        // Filter berdasarkan cabang jika bukan super admin
        if ($user->cabang_id) {
            $query->where('cabang_id', $user->cabang_id);
        }

        // Filter berdasarkan tanggal jika ada
        if ($request->filled('date')) {
            $query->byDate($request->date);
        }

        // Filter berdasarkan status pemrosesan
        if ($request->filled('processed')) {
            $query->where('is_processed', $request->boolean('processed'));
        }

        $attendances = $query->paginate(50);

        return view('admin.fingerprint-attendance.index', compact('attendances'));
    }

    /**
     * Memproses ulang data fingerprint yang belum diproses
     */
    public function reprocess(Request $request)
    {
        $user = auth()->user();
        
        if ($user->role !== 'manager') {
            abort(403, 'Unauthorized');
        }

        try {
            $unprocessedCount = 0;
            $processedCount = 0;

            // Ambil data yang belum diproses
            $unprocessedAttendances = FingerprintAttendance::unprocessed()
                ->when($user->cabang_id, function($query) use ($user) {
                    return $query->where('cabang_id', $user->cabang_id);
                })
                ->get();

            foreach ($unprocessedAttendances as $attendance) {
                $unprocessedCount++;
                
                if ($this->fingerprintService->processAttendance($attendance)) {
                    $processedCount++;
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => "Berhasil memproses {$processedCount} dari {$unprocessedCount} data fingerprint",
                'processed' => $processedCount,
                'total' => $unprocessedCount
            ]);

        } catch (\Exception $e) {
            Log::error('Error reprocessing fingerprint attendance: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memproses ulang data fingerprint',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menghapus data fingerprint attendance
     */
    public function destroy($id)
    {
        $user = auth()->user();
        
        if ($user->role !== 'manager') {
            abort(403, 'Unauthorized');
        }

        try {
            $attendance = FingerprintAttendance::findOrFail($id);
            
            // Cek apakah user memiliki akses ke cabang ini
            if ($user->cabang_id && $attendance->cabang_id !== $user->cabang_id) {
                abort(403, 'Unauthorized');
            }

            $attendance->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Data fingerprint berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting fingerprint attendance: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data fingerprint',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
