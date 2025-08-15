<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Filter berdasarkan cabang, golongan, dan nama
        $query = User::with(['cabang', 'jabatan'])->where('role', '!=', 'manager');
        
        // Filter berdasarkan cabang jika ada
        if ($request->has('search-cabang') && $request->input('search-cabang') != '') {
            $query->where('id_cabang', $request->input('search-cabang'));
        }
        
        // Filter berdasarkan golongan/jabatan jika ada
        if ($request->has('search-golongan') && $request->input('search-golongan') != '') {
            $query->where('id_jabatan', $request->input('search-golongan'));
        }
        
        // Filter berdasarkan nama jika ada
        if ($request->has('search-name') && $request->input('search-name') != '') {
            $query->where('nama_pegawai', 'like', '%' . $request->input('search-name') . '%');
        }
        
        $pegawai = $query->get();

        $jabatan = Jabatan::all();
        confirmDelete('Hapus Pegawai!', 'Apakah Anda Yakin?');

        return view('admin.pegawai.index', compact('pegawai', 'jabatan'));
    }

    public function indexAdmin()
    {
        $pegawai = User::where('role', 'manager')->get();
        return view('admin.pegawai.index', compact('pegawai'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pegawai = User::all();
        $jabatan = Jabatan::all();
        $cabang = \App\Models\Cabang::all();
        return view('admin.pegawai.create', compact('pegawai', 'jabatan', 'cabang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pegawai' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string|in:Laki-Laki,Perempuan',
            'id_cabang' => 'required|exists:cabang,id',
            'id_jabatan' => 'required|exists:jabatans,id',
            'email' => 'required|email|unique:users,email|max:255',
            'alamat' => 'required|string|max:500',
            'device_user_id' => 'nullable|string|unique:users,device_user_id|max:50',
        ], [
            'nama_pegawai.required' => 'Nama pegawai harus diisi!',
            'nama_pegawai.max' => 'Nama pegawai maksimal 255 karakter!',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih!',
            'jenis_kelamin.in' => 'Jenis kelamin harus Laki-Laki atau Perempuan!',
            'id_cabang.required' => 'Lokasi harus dipilih!',
            'id_cabang.exists' => 'Lokasi yang dipilih tidak valid!',
            'id_jabatan.required' => 'Golongan harus dipilih!',
            'id_jabatan.exists' => 'Golongan yang dipilih tidak valid!',
            'email.required' => 'Email harus diisi!',
            'email.email' => 'Format email tidak valid!',
            'email.unique' => 'Email sudah digunakan!',
            'email.max' => 'Email maksimal 255 karakter!',
            'alamat.required' => 'Alamat harus diisi!',
            'alamat.max' => 'Alamat maksimal 500 karakter!',
            'device_user_id.unique' => 'ID Device Fingerprint sudah digunakan!',
            'device_user_id.max' => 'ID Device Fingerprint maksimal 50 karakter!',
        ]);

        // Get gaji pokok from jabatan
        $jabatan = Jabatan::find($request->id_jabatan);
        $gajiOtomatis = $jabatan ? $jabatan->gaji_pokok : 0;

        $pegawai = new User();
        $pegawai->nama_pegawai = $request->nama_pegawai;
        $pegawai->jenis_kelamin = $request->jenis_kelamin;
        $pegawai->alamat = $request->alamat;
        $pegawai->email = $request->email;
        $pegawai->password = Hash::make('password123'); // Default password
        $pegawai->id_jabatan = $request->id_jabatan;
        $pegawai->id_cabang = $request->id_cabang;
        $pegawai->device_user_id = $request->device_user_id;
        $pegawai->gaji = $gajiOtomatis; // Set gaji sesuai jabatan
        $pegawai->role = 'admin'; // Set as admin (previously employee)
        $pegawai->status_pegawai = 1; // Set as active

        $pegawai->save();
        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil ditambahkan!');

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pegawai = User::with(['cabang', 'jabatan'])->findOrFail($id);
        return view('admin.pegawai.show', compact('pegawai'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pegawai = User::findOrFail($id);
        $jabatan = Jabatan::all();
        $cabang = \App\Models\Cabang::all();
        
        // Debug: Log data pegawai
        \Log::info('Data Pegawai untuk Edit:', [
            'id' => $pegawai->id,
            'nama_pegawai' => $pegawai->nama_pegawai,
            'email' => $pegawai->email,
            'jenis_kelamin' => $pegawai->jenis_kelamin,
            'alamat' => $pegawai->alamat,
            'gaji' => $pegawai->gaji,
            'id_jabatan' => $pegawai->id_jabatan,
            'id_cabang' => $pegawai->id_cabang,
            'device_user_id' => $pegawai->device_user_id
        ]);
        
        return view('admin.pegawai.edit', compact('pegawai', 'jabatan', 'cabang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pegawai' => 'required|string|max:255',
            'id_jabatan' => 'required|exists:jabatans,id',
            'id_cabang' => 'required|exists:cabang,id',
            'jenis_kelamin' => 'required|in:Laki-Laki,Perempuan',
            'alamat' => 'required|string|max:500',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'device_user_id' => 'nullable|string|max:50',
            'gaji' => 'nullable|numeric|min:0',
        ], [
            'nama_pegawai.required' => 'Nama pegawai wajib diisi.',
            'nama_pegawai.max' => 'Nama pegawai maksimal 255 karakter.',
            'id_jabatan.required' => 'Golongan wajib dipilih.',
            'id_jabatan.exists' => 'Golongan yang dipilih tidak valid.',
            'id_cabang.required' => 'Lokasi wajib dipilih.',
            'id_cabang.exists' => 'Lokasi yang dipilih tidak valid.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin harus Laki-Laki atau Perempuan.',
            'alamat.required' => 'Alamat wajib diisi.',
            'alamat.max' => 'Alamat maksimal 500 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'email.unique' => 'Email sudah digunakan oleh pegawai lain.',
            'device_user_id.max' => 'ID Device Fingerprint maksimal 50 karakter.',
            'gaji.numeric' => 'Gaji harus berupa angka.',
            'gaji.min' => 'Gaji tidak boleh kurang dari 0.',
        ]);

        $pegawai = User::findOrFail($id);
        
        // Jika gaji tidak diisi atau kosong, gunakan gaji pokok dari jabatan
        $gaji = $request->gaji;
        if (empty($gaji)) {
            $jabatan = Jabatan::find($request->id_jabatan);
            $gaji = $jabatan ? $jabatan->gaji_pokok : 0;
        }
        
        $pegawai->update([
            'nama_pegawai' => $request->nama_pegawai,
            'id_jabatan' => $request->id_jabatan,
            'id_cabang' => $request->id_cabang,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'email' => $request->email,
            'device_user_id' => $request->device_user_id,
            'gaji' => $gaji
        ]);

        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil diperbarui.');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pegawai = User::find($id);

        if (!$pegawai) {
            return redirect()->route('pegawai.index')->with('danger', 'Pegawai tidak ditemukan!');
        }

        if (Auth::user()->id !== $pegawai->id) {
            $pegawai->delete();
            return redirect()->route('pegawai.index')->with('danger', 'pegawai berhasil dihapus!');
        }

        return redirect()->route('pegawai.index')->with('danger', 'Anda tidak bisa menghapus diri sendiri!');

    }
    
    public function export(Request $request)
    {
        // Get filter parameters
        $selectedMonth = $request->input('search-month', date('m'));
        $selectedYear = $request->input('search-year', date('Y'));
        $searchName = $request->input('search-name');
        $searchBranch = $request->input('search-branch');
        
        // Build query with filters
        $query = User::with(['cabang', 'jabatan'])->where('role', '!=', 'manager');
        
        if ($searchName) {
            $query->where('nama_pegawai', 'like', '%' . $searchName . '%');
        }
        
        if ($searchBranch) {
            $query->where('id_cabang', $searchBranch);
        }
        
        $pegawai = $query->get();
        
        // Calculate days in month
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $selectedMonth, $selectedYear);
        $monthName = date('F', mktime(0, 0, 0, $selectedMonth, 1, $selectedYear));
        
        // Create CSV content
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="absensi-' . $monthName . '-' . $selectedYear . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        $callback = function() use ($pegawai, $daysInMonth, $selectedMonth, $selectedYear) {
            $file = fopen('php://output', 'w');
            
            // Header row 1 - Month and Year
            $headerRow1 = ['Nama Karyawan'];
            $monthName = date('F', mktime(0, 0, 0, $selectedMonth, 1, $selectedYear));
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $headerRow1[] = $day;
                $headerRow1[] = ''; // Empty cell for evening check-in
            }
            fputcsv($file, $headerRow1);
            
            // Header row 2 - Morning/Evening indicators
            $headerRow2 = [''];
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $headerRow2[] = 'Pagi';
                $headerRow2[] = 'Sore';
            }
            fputcsv($file, $headerRow2);
            
            // Data rows
            foreach ($pegawai as $data) {
                $row = [$data->nama_pegawai];
                
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $date = "$selectedYear-$selectedMonth-" . str_pad($day, 2, '0', STR_PAD_LEFT);
                    $absensi = \App\Models\Absensi::where('id_user', $data->id)
                        ->where('tanggal_absen', $date)
                        ->first();
                    
                    if ($absensi) {
                        $row[] = $absensi->jam_masuk ? Carbon::parse($absensi->jam_masuk)->format('H:i') : '-';
                        $row[] = $absensi->jam_masuk_sore ? Carbon::parse($absensi->jam_masuk_sore)->format('H:i') : '-';
                    } else {
                        $row[] = '-';
                        $row[] = '-';
                    }
                }
                
                fputcsv($file, $row);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }
}
