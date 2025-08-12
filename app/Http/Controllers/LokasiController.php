<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LokasiController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $search = $request->get('search');
        
        $query = Lokasi::withCount('karyawan');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_lokasi', 'like', "%{$search}%")
                  ->orWhere('kode_lokasi', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }
        
        $lokasi = $query->orderBy('nama_lokasi')
            ->paginate(20)
            ->withQueryString();
        
        return Inertia::render('Lokasi/Index', [
            'lokasi' => $lokasi,
            'filters' => [
                'search' => $search,
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
        
        return Inertia::render('Lokasi/Create');
    }
    
    public function store(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->isManager()) {
            abort(403, 'Unauthorized access.');
        }
        
        $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'kode_lokasi' => 'required|string|max:10|unique:lokasi,kode_lokasi',
        ]);
        
        Lokasi::create($request->all());
        
        return redirect()->route('lokasi.index')
            ->with('success', 'Lokasi berhasil ditambahkan.');
    }
    
    public function show(Lokasi $lokasi)
    {
        $user = auth()->user();
        
        $allowedGolonganIds = $user->getAllowedGolonganIds();
        
        $karyawan = $lokasi->karyawan()
            ->with('golongan')
            ->whereIn('golongan_id', $allowedGolonganIds)
            ->orderBy('nama_karyawan')
            ->paginate(20);
        
        return Inertia::render('Lokasi/Show', [
            'lokasi' => $lokasi,
            'karyawan' => $karyawan,
        ]);
    }
    
    public function edit(Lokasi $lokasi)
    {
        $user = auth()->user();
        
        if (!$user->isManager()) {
            abort(403, 'Unauthorized access.');
        }
        
        return Inertia::render('Lokasi/Edit', [
            'lokasi' => $lokasi,
        ]);
    }
    
    public function update(Request $request, Lokasi $lokasi)
    {
        $user = auth()->user();
        
        if (!$user->isManager()) {
            abort(403, 'Unauthorized access.');
        }
        
        $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'kode_lokasi' => 'required|string|max:10|unique:lokasi,kode_lokasi,' . $lokasi->id,
            'is_active' => 'boolean',
        ]);
        
        $lokasi->update($request->all());
        
        return redirect()->route('lokasi.index')
            ->with('success', 'Lokasi berhasil diperbarui.');
    }
    
    public function destroy(Lokasi $lokasi)
    {
        $user = auth()->user();
        
        if (!$user->isManager()) {
            abort(403, 'Unauthorized access.');
        }
        
        // Check if lokasi has karyawan
        if ($lokasi->karyawan()->count() > 0) {
            return redirect()->route('lokasi.index')
                ->with('error', 'Lokasi tidak dapat dihapus karena masih memiliki karyawan.');
        }
        
        $lokasi->delete();
        
        return redirect()->route('lokasi.index')
            ->with('success', 'Lokasi berhasil dihapus.');
    }
}