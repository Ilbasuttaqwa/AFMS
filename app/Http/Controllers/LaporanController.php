<?php

namespace App\Http\Controllers;


use App\Exports\PegawaiExport;
use App\Models\Absensi;

use App\Models\Jabatan;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    // public function indexPegawai()
    // {
    //     $absensi = Absensi::latest()->get();
    //     $pegawai = User::all();
    //     return view('admin.laporan.pegawai', compact('absensi', 'pegawai'));
    // }

    // LAPORAN BUAT PEGAWAI DAN FILTER
    // public function pegawai(Request $request)
    // {
    //     $jabatan = Jabatan::all();
    //     $tanggalAwal = $request->input('tanggal_awal');
    //     $tanggalAkhir = $request->input('tanggal_akhir');
    //     $jabatanId = $request->input('jabatan');

    //     if (!$tanggalAwal || !$tanggalAkhir) {
    //         $pegawai = User::where('role', '!=', 'manager')->get()->map(function ($pegawai) {
    //             $pegawai->umur = floor(Carbon::parse($pegawai->tanggal_lahir)->diffInYears(Carbon::now()));
    //             return $pegawai;
    //         });
    //     } else {
    //         $pegawai = User::whereBetween('tanggal_masuk', [$tanggalAwal, $tanggalAkhir])->get('*')->map(function ($pegawai) {
    //             $pegawai->umur = floor(Carbon::parse($pegawai->tanggal_lahir)->diffInYears(Carbon::now()));
    //             return $pegawai;
    //         });
    //     }

    //     // tampil pdf
    //     if ($request->has('pdf')) {
    //         $pdf = PDF::loadView('admin.laporan.pdf_pegawai', compact('pegawai'));
    //         return $pdf->stream('laporan_pegawai.pdf'); //ini buat show pdf
    //     }
    //     // download pdf
    //     if ($request->has('download_pdf')) {
    //         $pdf = PDF::loadView('admin.laporan.pdf_pegawai', compact('pegawai'));
    //         return $pdf->download('laporan_pegawai.pdf'); //ini buat download pdf
    //     }

    //     // download excel
    //     if ($request->has('download_excel')) {
    //         return Excel::download(new PegawaiExport($pegawai), 'laporan_pegawai.xlsx');
    //     }

    //     return view('admin.laporan.pegawai', compact('pegawai', 'jabatan'));
    // }

    public function pegawai(Request $request)
    {
        $jabatan = Jabatan::all();
        $tanggalAwal = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');
        $jabatanId = $request->input('jabatan');

        if (!$tanggalAwal || !$tanggalAkhir) {
            $pegawai = User::where('role', '!=', 'manager')
                ->when($jabatanId, function ($query) use ($jabatanId) {
                    return $query->where('id_jabatan', $jabatanId);
                })
                ->get()
                ->map(function ($pegawai) {
                    $pegawai->umur = floor(Carbon::parse($pegawai->tanggal_lahir)->diffInYears(Carbon::now()));
                    return $pegawai;
                });
        } else {
            $pegawai = User::whereBetween('tanggal_masuk', [$tanggalAwal, $tanggalAkhir])
                ->when($jabatanId, function ($query) use ($jabatanId) {
                    return $query->where('id_jabatan', $jabatanId);
                })
                ->get()
                ->map(function ($pegawai) {
                    $pegawai->umur = floor(Carbon::parse($pegawai->tanggal_lahir)->diffInYears(Carbon::now()));
                    return $pegawai;
                });
        }

        // Tampilkan atau unduh PDF
        if ($request->has('pdf')) {
            $pdf = PDF::loadView('admin.laporan.pdf_pegawai', compact('pegawai')) ->setPaper('a4', 'landscape');;
            return $pdf->stream('laporan_pegawai.pdf');
        }
        if ($request->has('download_pdf')) {
            $pdf = PDF::loadView('admin.laporan.pdf_pegawai', compact('pegawai')) ->setPaper('a4', 'landscape');;
            return $pdf->download('laporan_pegawai.pdf');
        }

        // Unduh Excel
        if ($request->has('download_excel')) {
            return Excel::download(new PegawaiExport($pegawai), 'laporan_pegawai.xlsx');
        }

        return view('admin.laporan.pegawai', compact('pegawai', 'jabatan'));
    }

    // LAPORAN BUAT ABSENSI DAN FILTER
    public function absensi(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));
        $cabangId = $request->input('cabang');
        
        // Create date range for the selected month and year
        $startDate = "$year-$month-01";
        $endDate = date('Y-m-t', strtotime($startDate));
        
        // Query to get attendance data with user relationship
        $query = Absensi::with(['user.cabang', 'user.jabatan'])
            ->whereBetween('tanggal_absen', [$startDate, $endDate]);
            
        // Filter by branch/location if selected
        if ($cabangId) {
            $query->whereHas('user', function($q) use ($cabangId) {
                $q->where('id_cabang', $cabangId);
            });
        }
        
        $absensi = $query->get();
        
        // Get all branches for the filter dropdown
        $cabang = \App\Models\Cabang::all();
        
        // Generate PDF if requested
        if ($request->has('pdf')) {
            $pdf = PDF::loadView('admin.laporan.pdf_absensi', compact('absensi'))->setPaper('a4', 'landscape');
            return $pdf->stream('laporan_absensi.pdf');
        }
        
        // Download PDF if requested
        if ($request->has('download_pdf')) {
            $pdf = PDF::loadView('admin.laporan.pdf_absensi', compact('absensi'))->setPaper('a4', 'landscape');
            return $pdf->download('laporan_absensi.pdf');
        }
        
        // Download Excel if requested
        if ($request->has('download_excel')) {
            return Excel::download(new \App\Exports\AbsensiExport($absensi), 'laporan_absensi.xlsx');
        }
        
        return view('admin.laporan.absensi', compact('absensi', 'cabang', 'year', 'month', 'cabangId'));
    }



    
}
