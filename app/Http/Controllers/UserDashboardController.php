<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the employee dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();
        
        // Get employee's branch/location
        $userCabang = $user->id_cabang;
        
        // Count employees who are late today (in the same branch)
        $karyawanTerlambat = Absensi::whereDate('tanggal_absen', $today)
            ->whereTime('jam_masuk', '>', '08:00:00') // Assuming 08:00 is the standard start time
            ->whereHas('user', function($query) use ($userCabang) {
                if ($userCabang) {
                    $query->where('id_cabang', $userCabang);
                }
            })
            ->count();
        
        // Count employees who are sick today (in the same branch)
        $karyawanSakit = Absensi::whereDate('tanggal_absen', $today)
            ->where('status', 'Sakit')
            ->whereHas('user', function($query) use ($userCabang) {
                if ($userCabang) {
                    $query->where('id_cabang', $userCabang);
                }
            })
            ->count();
        
        return view('user.dashboard.index', compact('karyawanTerlambat', 'karyawanSakit'));
    }
}