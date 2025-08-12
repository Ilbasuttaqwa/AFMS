<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Golongan;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $allowedGolonganIds = $user->getAllowedGolonganIds();
        
        $search = $request->get('search');
        $golonganId = $request->get('golongan_id');
        $lokasiId = $request->get('lokasi_id');
        
        $query = Karyawan::with(['golongan', 'lokasi'])
            ->whereIn('golongan_id', $allowedGolonganIds);
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_karyawan', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($golonganId) {
            $query->where('golongan_id', $golonganId);
        }
        
        if ($lokasiId) {
            $query->where('lokasi_id', $lokasiId);
        }
        
        $karyawan = $query->orderBy('nama_karyawan')
            ->paginate(20)
            ->withQueryString();
        
        // Get filter options
        $golongan = Golongan::whereIn('id', $allowedGolonganIds)->get();
        $lokasi = Lokasi::active()->get();
        
        return Inertia::render('Karyawan/Index', [
            'karyawan' => $karyawan,
            'filters' => [
                'search' => $search,
                'golongan_id' => $golonganId ? (int) $golonganId : null,
                'lokasi_id' => $lokasiId ? (int) $lokasiId : null,
            ],
            'golongan' => $golongan,
            'lokasi' => $lokasi,
            'user_role' => $user->role,
        ]);
    }
    
    public function create()
    {
        $user = auth()->user();
        
        if (!$user->isManager()) {
            abort(403, 'Unauthorized access.');
        }
        
        $allowedGolonganIds = $user->getAllowedGolonganIds();
        
        $golongan = Golongan::whereIn('id', $allowedGolonganIds)->get();
        $lokasi = Lokasi::active()->get();
        
        return Inertia::render('Karyawan/Create', [
            'golongan' => $golongan,
            'lokasi' => $lokasi,
        ]);
    }
    
    public function store(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->isManager()) {
            abort(403, 'Unauthorized access.');
        }
        
        $request->validate([
            'nama_karyawan' => 'required|string|max:255',
            'nip' => 'required|string|unique:karyawan,nip|max:50',
            'email' => 'nullable|email|unique:karyawan,email',
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tanggal_masuk' => 'required|date',
            'golongan_id' => 'required|exists:golongan,id',
            'lokasi_id' => 'required|exists:lokasi,id',
            'fingerprint_id' => 'nullable|string|unique:karyawan,fingerprint_id',
        ]);
        
        // Check if user can access this golongan
        if (!$user->canAccessGolongan($request->golongan_id)) {
            abort(403, 'Unauthorized access to this golongan.');
        }
        
        Karyawan::create($request->all());
        
        return redirect()->route('karyawan.index')
            ->with('success', 'Karyawan berhasil ditambahkan.');
    }
    
    public function show(Karyawan $karyawan)
    {
        $user = auth()->user();
        
        if (!$user->canAccessGolongan($karyawan->golongan_id)) {
            abort(403, 'Unauthorized access.');
        }
        
        $karyawan->load(['golongan', 'lokasi', 'absensi' => function($query) {
            $query->orderBy('tanggal', 'desc')->limit(10);
        }]);
        
        return Inertia::render('Karyawan/Show', [
            'karyawan' => $karyawan,
        ]);
    }
    
    public function edit(Karyawan $karyawan)
    {
        $user = auth()->user();
        
        if (!$user->isManager() || !$user->canAccessGolongan($karyawan->golongan_id)) {
            abort(403, 'Unauthorized access.');
        }
        
        $allowedGolonganIds = $user->getAllowedGolonganIds();
        
        $golongan = Golongan::whereIn('id', $allowedGolonganIds)->get();
        $lokasi = Lokasi::active()->get();
        
        return Inertia::render('Karyawan/Edit', [
            'karyawan' => $karyawan,
            'golongan' => $golongan,
            'lokasi' => $lokasi,
        ]);
    }
    
    public function update(Request $request, Karyawan $karyawan)
    {
        $user = auth()->user();
        
        if (!$user->isManager() || !$user->canAccessGolongan($karyawan->golongan_id)) {
            abort(403, 'Unauthorized access.');
        }
        
        $request->validate([
            'nama_karyawan' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:karyawan,nip,' . $karyawan->id,
            'email' => 'nullable|email|unique:karyawan,email,' . $karyawan->id,
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tanggal_masuk' => 'required|date',
            'golongan_id' => 'required|exists:golongan,id',
            'lokasi_id' => 'required|exists:lokasi,id',
            'fingerprint_id' => 'nullable|string|unique:karyawan,fingerprint_id,' . $karyawan->id,
            'is_active' => 'boolean',
        ]);
        
        // Check if user can access the new golongan
        if (!$user->canAccessGolongan($request->golongan_id)) {
            abort(403, 'Unauthorized access to this golongan.');
        }
        
        $karyawan->update($request->all());
        
        return redirect()->route('karyawan.index')
            ->with('success', 'Karyawan berhasil diperbarui.');
    }
    
    public function destroy(Karyawan $karyawan)
    {
        $user = auth()->user();
        
        if (!$user->isManager() || !$user->canAccessGolongan($karyawan->golongan_id)) {
            abort(403, 'Unauthorized access.');
        }
        
        $karyawan->delete();
        
        return redirect()->route('karyawan.index')
            ->with('success', 'Karyawan berhasil dihapus.');
    }
}