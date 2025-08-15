<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FingerprintService;
use App\Models\Cabang;
use App\Models\Absensi;
use App\Models\User;
use Carbon\Carbon;

class FingerprintController extends Controller
{
    protected $fingerprintService;
    
    public function __construct(FingerprintService $fingerprintService)
    {
        $this->fingerprintService = $fingerprintService;
    }
    
    /**
     * Show fingerprint configuration page
     */
    public function index()
    {
        $user = auth()->user();
        
        if ($user->role === 'manager') {
            // Manager can see all branches
            $cabangs = Cabang::all();
        } else {
            // Admin can only see their branch
            $cabangs = Cabang::where('id', $user->id_cabang)->get();
        }
        
        return view('admin.fingerprint.index', compact('cabangs'));
    }
    
    /**
     * Update fingerprint configuration for a branch
     */
    public function updateConfig(Request $request, $cabangId)
    {
        $user = auth()->user();
        
        // Check permission
        if ($user->role === 'admin' && $user->id_cabang != $cabangId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $request->validate([
            'fingerprint_ip' => 'required|ip',
            'fingerprint_port' => 'required|integer|min:1|max:65535',
            'fingerprint_active' => 'boolean'
        ]);
        
        $cabang = Cabang::findOrFail($cabangId);
        
        $cabang->update([
            'fingerprint_ip' => $request->fingerprint_ip,
            'fingerprint_port' => $request->fingerprint_port,
            'fingerprint_active' => $request->has('fingerprint_active')
        ]);
        
        return response()->json(['success' => 'Konfigurasi fingerprint berhasil diperbarui']);
    }
    
    /**
     * Test connection to fingerprint device
     */
    public function testConnection(Request $request)
    {
        $request->validate([
            'ip' => 'required|ip',
            'port' => 'required|integer|min:1|max:65535'
        ]);
        
        $isConnected = $this->fingerprintService->testConnection(
            $request->ip, 
            $request->port
        );
        
        if ($isConnected) {
            return response()->json(['success' => 'Koneksi berhasil!']);
        } else {
            return response()->json(['error' => 'Koneksi gagal. Periksa IP dan port.'], 400);
        }
    }
    
    /**
     * Manual sync attendance data
     */
    public function syncAttendance($cabangId)
    {
        $user = auth()->user();
        
        // Check permission
        if ($user->role === 'admin' && $user->id_cabang != $cabangId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $syncedCount = $this->fingerprintService->syncAttendanceData($cabangId);
        
        if ($syncedCount !== false) {
            return response()->json([
                'success' => "Berhasil sinkronisasi {$syncedCount} data absensi"
            ]);
        } else {
            return response()->json(['error' => 'Gagal melakukan sinkronisasi'], 400);
        }
    }
    
    /**
     * Sync all devices (Manager only)
     */
    public function syncAll()
    {
        $user = auth()->user();
        
        if ($user->role !== 'manager') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $totalSynced = $this->fingerprintService->syncAllDevices();
        
        return response()->json([
            'success' => "Berhasil sinkronisasi {$totalSynced} data absensi dari semua cabang"
        ]);
    }
    
    /**
     * Get attendance data based on user role
     */
    public function getAttendanceData(Request $request)
    {
        $user = auth()->user();
        $query = Absensi::with(['user', 'user.cabang']);
        
        // Filter based on role
        if ($user->role === 'admin') {
            // Admin only sees data from their branch
            $query->whereHas('user', function($q) use ($user) {
                $q->where('id_cabang', $user->id_cabang);
            });
        }
        // Manager sees all data (no additional filter)
        
        // Apply date filter if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('tanggal_absen', [
                $request->start_date,
                $request->end_date
            ]);
        }
        
        // Apply branch filter (for manager)
        if ($request->has('cabang_id') && $user->role === 'manager') {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('id_cabang', $request->cabang_id);
            });
        }
        
        $attendances = $query->orderBy('tanggal_absen', 'desc')
                           ->orderBy('jam_masuk', 'desc')
                           ->paginate(50);
        
        return response()->json($attendances);
    }
    
    /**
     * Get sync status for all branches
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
}
