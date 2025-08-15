<?php

namespace App\Http\Controllers;

use App\Models\JadwalKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JadwalKerjaController extends Controller
{
    /**
     * Display the work schedule settings.
     */
    public function index()
    {
        $jadwalKerja = JadwalKerja::first();
        
        // If no schedule exists, create default one
        if (!$jadwalKerja) {
            $jadwalKerja = JadwalKerja::create([
                'jam_masuk' => '08:00',
                'jam_pulang' => '17:00',
                'toleransi_keterlambatan' => 15,
                'potongan_per_menit' => 1000
            ]);
        }
        
        return view('admin.jadwal-kerja.index', compact('jadwalKerja'));
    }

    /**
     * Update work schedule settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'jam_masuk' => 'required',
            'jam_pulang' => 'required',
            'toleransi_keterlambatan' => 'required|integer|min:0',
            'potongan_per_menit' => 'required|integer|min:0',
        ]);

        $jadwalKerja = JadwalKerja::first();
        
        if ($jadwalKerja) {
            $jadwalKerja->update([
                'jam_masuk' => $request->jam_masuk,
                'jam_pulang' => $request->jam_pulang,
                'toleransi_keterlambatan' => $request->toleransi_keterlambatan,
                'potongan_per_menit' => $request->potongan_per_menit,
            ]);
        } else {
            JadwalKerja::create([
                'jam_masuk' => $request->jam_masuk,
                'jam_pulang' => $request->jam_pulang,
                'toleransi_keterlambatan' => $request->toleransi_keterlambatan,
                'potongan_per_menit' => $request->potongan_per_menit,
            ]);
        }

        return redirect()->route('jadwal-kerja.index')->with('success', 'Jadwal kerja berhasil diperbarui!');
    }
}