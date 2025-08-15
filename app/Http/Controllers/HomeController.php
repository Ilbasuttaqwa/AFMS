<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Absensi;
use Carbon\Carbon;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $pegawai = User::all();

        $totalPegawai = User::count('id');
        
        // Hitung jumlah karyawan yang terlambat hari ini
        $terlambatHariIni = Absensi::whereDate('tanggal_absen', Carbon::today())
            ->where('jam_masuk', '>', '08:00:00')
            ->distinct('id_user')
            ->count();

        return view('home', compact('pegawai', 'totalPegawai', 'terlambatHariIni'));
    }
}
