<?php

namespace App\Http\Controllers;

use App\Models\Golongan;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GolonganController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $allowedGolonganIds = $user->getAllowedGolonganIds();
        
        $search = $request->get('search');
        $lokasiId = $request->get('lokasi_id');
        
        $query = Golongan::withCount('karyawan')
            ->whereIn('id', $allowedGolonganIds);
        
        if ($search) {
            $query->where('nama_golongan', 'like', "%{$search}%");
        }
        
        $golongan = $query->orderBy('nama_golongan')
            ->paginate(20)
            ->withQueryString();
        
        return Inertia::render('Golongan/Index', [
            'golongan' => $golongan,
            'filters' => [
                'search' => $search,
                'lokasi_id' => $lokasiId,
            ],
            'user_role' => $user->role,
        ]);
    }
    
    public function create()
    {
        $user = auth()->user();
        
        if (!$user->isManager()) {
            abort(403, 'Unauthorized access.');
        }
        
        return Inertia::render('Golongan/Create');
    }
    
    public function store(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->isManager()) {
            abort(403, 'Unauthorized access.');
        }
        
        $request->validate([
            'nama_golongan' => 'required|string|max:255|unique:golongan,nama_golongan',
            'gaji_pokok' => 'required|numeric|min:0',
            'jam_masuk_pagi' => 'required|date_format:H:i',
            'jam_pulang_siang' => 'required|date_format:H:i',
            'jam_masuk_siang' => 'required|date_format:H:i',
            'jam_pulang_sore' => 'required|date_format:H:i',
            'maksimal_ceklok_pagi' => 'required|integer|min:0|max:120',
            'maksimal_ceklok_siang' => 'required|integer|min:0|max:120',
            'denda_keterlambatan' => 'required|numeric|min:0',
            'potongan_tidak_masuk' => 'required|numeric|min:0',
            'jatah_libur' => 'required|integer|min:0|max:365',
            'bonus_tidak_libur' => 'required|numeric|min:0',
        ]);
        
        // Convert time format
        $data = $request->all();
        $data['jam_masuk_pagi'] .= ':00';
        $data['jam_pulang_siang'] .= ':00';
        $data['jam_masuk_siang'] .= ':00';
        $data['jam_pulang_sore'] .= ':00';
        
        Golongan::create($data);
        
        return redirect()->route('golongan.index')
            ->with('success', 'Golongan berhasil ditambahkan.');
    }
    
    public function show(Golongan $golongan, Request $request)
    {
        $user = auth()->user();
        
        if (!$user->canAccessGolongan($golongan->id)) {
            abort(403, 'Unauthorized access.');
        }
        
        $lokasiId = $request->get('lokasi_id');
        
        $karyawanQuery = $golongan->karyawan()->with('lokasi');
        
        if ($lokasiId) {
            $karyawanQuery->where('lokasi_id', $lokasiId);
        }
        
        $karyawan = $karyawanQuery->orderBy('nama_karyawan')
            ->paginate(20)
            ->withQueryString();
        
        // Get available locations for filter
        $lokasi = \App\Models\Lokasi::whereHas('karyawan', function($query) use ($golongan) {
            $query->where('golongan_id', $golongan->id);
        })->get();
        
        return Inertia::render('Golongan/Show', [
            'golongan' => $golongan,
            'karyawan' => $karyawan,
            'lokasi' => $lokasi,
            'filters' => [
                'lokasi_id' => $lokasiId ? (int) $lokasiId : null,
            ],
            'user_role' => $user->role,
        ]);
    }
    
    public function edit(Golongan $golongan)
    {
        $user = auth()->user();
        
        if (!$user->isManager() || !$user->canAccessGolongan($golongan->id)) {
            abort(403, 'Unauthorized access.');
        }
        
        // Format time for form display
        $golongan->jam_masuk_pagi = substr($golongan->jam_masuk_pagi, 0, 5);
        $golongan->jam_pulang_siang = substr($golongan->jam_pulang_siang, 0, 5);
        $golongan->jam_masuk_siang = substr($golongan->jam_masuk_siang, 0, 5);
        $golongan->jam_pulang_sore = substr($golongan->jam_pulang_sore, 0, 5);
        
        return Inertia::render('Golongan/Edit', [
            'golongan' => $golongan,
        ]);
    }
    
    public function update(Request $request, Golongan $golongan)
    {
        $user = auth()->user();
        
        if (!$user->isManager() || !$user->canAccessGolongan($golongan->id)) {
            abort(403, 'Unauthorized access.');
        }
        
        $request->validate([
            'nama_golongan' => 'required|string|max:255|unique:golongan,nama_golongan,' . $golongan->id,
            'gaji_pokok' => 'required|numeric|min:0',
            'jam_masuk_pagi' => 'required|date_format:H:i',
            'jam_pulang_siang' => 'required|date_format:H:i',
            'jam_masuk_siang' => 'required|date_format:H:i',
            'jam_pulang_sore' => 'required|date_format:H:i',
            'maksimal_ceklok_pagi' => 'required|integer|min:0|max:120',
            'maksimal_ceklok_siang' => 'required|integer|min:0|max:120',
            'denda_keterlambatan' => 'required|numeric|min:0',
            'potongan_tidak_masuk' => 'required|numeric|min:0',
            'jatah_libur' => 'required|integer|min:0|max:365',
            'bonus_tidak_libur' => 'required|numeric|min:0',
        ]);
        
        // Convert time format
        $data = $request->all();
        $data['jam_masuk_pagi'] .= ':00';
        $data['jam_pulang_siang'] .= ':00';
        $data['jam_masuk_siang'] .= ':00';
        $data['jam_pulang_sore'] .= ':00';
        
        $golongan->update($data);
        
        return redirect()->route('golongan.index')
            ->with('success', 'Golongan berhasil diperbarui.');
    }
    
    public function destroy(Golongan $golongan)
    {
        $user = auth()->user();
        
        if (!$user->isManager() || !$user->canAccessGolongan($golongan->id)) {
            abort(403, 'Unauthorized access.');
        }
        
        // Check if golongan has karyawan
        if ($golongan->karyawan()->count() > 0) {
            return redirect()->route('golongan.index')
                ->with('error', 'Golongan tidak dapat dihapus karena masih memiliki karyawan.');
        }
        
        $golongan->delete();
        
        return redirect()->route('golongan.index')
            ->with('success', 'Golongan berhasil dihapus.');
    }
}