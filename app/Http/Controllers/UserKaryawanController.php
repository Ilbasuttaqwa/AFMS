<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Absensi;
use App\Models\Cabang;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserKaryawanController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of employees in the same location
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get current user's branch information
        $currentBranch = $user->cabang;
        
        // Get all employees in the same branch (excluding managers)
        $query = User::where('id_cabang', $user->id_cabang)
                    ->where('role', '!=', 'manager')
                    ->with(['jabatan', 'cabang']);
        
        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_pegawai', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }
        
        // Filter by position
        if ($request->has('jabatan') && $request->jabatan != '') {
            $query->where('id_jabatan', $request->jabatan);
        }
        
        $karyawan = $query->orderBy('nama_pegawai', 'asc')->paginate(10);
        
        // Get all positions for filter
        $jabatan = Jabatan::all();
        
        // Get attendance statistics for each employee this month
        $currentMonth = Carbon::now()->format('Y-m');
        foreach ($karyawan as $employee) {
            $employee->attendance_stats = $this->getAttendanceStats($employee->id, $currentMonth);
        }
        
        return view('user.karyawan.index', compact('karyawan', 'currentBranch', 'jabatan'));
    }
    
    /**
     * Show detailed information about a specific employee
     */
    public function show($id)
    {
        $user = Auth::user();
        
        // Ensure the employee is in the same branch
        $karyawan = User::where('id', $id)
                       ->where('id_cabang', $user->id_cabang)
                       ->where('role', '!=', 'manager')
                       ->with(['jabatan', 'cabang'])
                       ->firstOrFail();
        
        // Get attendance history for the last 30 days
        $startDate = Carbon::now()->subDays(30)->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');
        
        $attendanceHistory = Absensi::where('id_user', $karyawan->id)
                                   ->whereBetween('tanggal_absen', [$startDate, $endDate])
                                   ->orderBy('tanggal_absen', 'desc')
                                   ->get();
        
        // Get monthly attendance statistics
        $currentMonth = Carbon::now()->format('Y-m');
        $attendanceStats = $this->getAttendanceStats($karyawan->id, $currentMonth);
        
        return view('user.karyawan.show', compact('karyawan', 'attendanceHistory', 'attendanceStats'));
    }
    
    /**
     * Get attendance statistics for an employee
     */
    private function getAttendanceStats($userId, $month)
    {
        $startDate = $month . '-01';
        $endDate = Carbon::parse($startDate)->endOfMonth()->format('Y-m-d');
        
        $totalDays = Absensi::where('id_user', $userId)
                           ->whereBetween('tanggal_absen', [$startDate, $endDate])
                           ->count();
        
        $lateDays = Absensi::where('id_user', $userId)
                          ->whereBetween('tanggal_absen', [$startDate, $endDate])
                          ->where('note', 'Telat')
                          ->count();
        
        $onTimeDays = $totalDays - $lateDays;
        
        return [
            'total_days' => $totalDays,
            'on_time_days' => $onTimeDays,
            'late_days' => $lateDays,
            'attendance_rate' => $totalDays > 0 ? round(($onTimeDays / $totalDays) * 100, 1) : 0
        ];
    }
    
    /**
     * Get attendance data for AJAX requests
     */
    public function getAttendanceData(Request $request)
    {
        $user = Auth::user();
        $employeeId = $request->employee_id;
        
        // Ensure the employee is in the same branch
        $employee = User::where('id', $employeeId)
                       ->where('id_cabang', $user->id_cabang)
                       ->first();
        
        if (!$employee) {
            return response()->json(['error' => 'Employee not found'], 404);
        }
        
        $month = $request->month ?? Carbon::now()->format('Y-m');
        $attendanceStats = $this->getAttendanceStats($employeeId, $month);
        
        return response()->json($attendanceStats);
    }
}