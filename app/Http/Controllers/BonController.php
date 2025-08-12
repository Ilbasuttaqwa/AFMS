<?php

namespace App\Http\Controllers;

use App\Models\Bon;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BonController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $allowedGolonganIds = $user->getAllowedGolonganIds();
        
        $search = $request->get('search');
        $status = $request->get('status');
        $karyawanId = $request->get('karyawan_id');
        
        $query = Bon::with(['karyawan.golongan', 'karyawan.lokasi', 'createdBy'])
            ->whereHas('karyawan', function($q) use ($allowedGolonganIds) {
                $q->whereIn('golongan_id', $allowedGolonganIds);
            });
        
        if ($search) {
            $query->whereHas('karyawan', function($q) use ($search) {
                $q->where('nama_karyawan', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
            });
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        if ($karyawanId) {
            $query->where('karyawan_id', $karyawanId);
        }
        
        $bon = $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();
        
        // Get karyawan for filter
        $karyawan = Karyawan::whereIn('golongan_id', $allowedGolonganIds)
            ->orderBy('nama_karyawan')
            ->get(['id', 'nama_karyawan', 'nip']);
        
        return Inertia::render('Bon/Index', [
            'bon' => $bon,
            'filters' => [
                'search' => $search,
                'status' => $status,
                'karyawan_id' => $karyawanId ? (int) $karyawanId : null,
            ],
            'karyawan' => $karyawan,
            'user_role' => $user->role,
        ]);
    }
    
    public function create()
    {
        $user = auth()->user();
        $allowedGolonganIds = $user->getAllowedGolonganIds();
        
        $karyawan = Karyawan::with('golongan')
            ->whereIn('golongan_id', $allowedGolonganIds)
            ->orderBy('nama_karyawan')
            ->get();
        
        return Inertia::render('Bon/Create', [
            'karyawan' => $karyawan,
        ]);
    }
    
    public function store(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'jumlah_bon' => 'required|numeric|min:1',
            'cicilan_per_bulan' => 'required|numeric|min:1',
            'tanggal_bon' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);
        
        // Check if user can access this karyawan's golongan
        $karyawan = Karyawan::findOrFail($request->karyawan_id);
        if (!$user->canAccessGolongan($karyawan->golongan_id)) {
            abort(403, 'Unauthorized access to this karyawan.');
        }
        
        // Check if karyawan has active bon
        $activeBon = Bon::where('karyawan_id', $request->karyawan_id)
            ->where('status', 'aktif')
            ->exists();
        
        if ($activeBon) {
            return redirect()->back()
                ->withErrors(['karyawan_id' => 'Karyawan masih memiliki bon aktif.']);
        }
        
        $data = $request->all();
        $data['sisa_bon'] = $request->jumlah_bon;
        $data['created_by'] = $user->id;
        
        Bon::create($data);
        
        return redirect()->route('bon.index')
            ->with('success', 'Bon berhasil ditambahkan.');
    }
    
    public function show(Bon $bon)
    {
        $user = auth()->user();
        
        if (!$user->canAccessGolongan($bon->karyawan->golongan_id)) {
            abort(403, 'Unauthorized access.');
        }
        
        $bon->load(['karyawan.golongan', 'karyawan.lokasi', 'createdBy']);
        
        return Inertia::render('Bon/Show', [
            'bon' => $bon,
        ]);
    }
    
    public function edit(Bon $bon)
    {
        $user = auth()->user();
        
        if (!$user->isManager() || !$user->canAccessGolongan($bon->karyawan->golongan_id)) {
            abort(403, 'Unauthorized access.');
        }
        
        if ($bon->status !== 'aktif') {
            return redirect()->route('bon.index')
                ->with('error', 'Hanya bon aktif yang dapat diedit.');
        }
        
        $allowedGolonganIds = $user->getAllowedGolonganIds();
        
        $karyawan = Karyawan::with('golongan')
            ->whereIn('golongan_id', $allowedGolonganIds)
            ->orderBy('nama_karyawan')
            ->get();
        
        return Inertia::render('Bon/Edit', [
            'bon' => $bon,
            'karyawan' => $karyawan,
        ]);
    }
    
    public function update(Request $request, Bon $bon)
    {
        $user = auth()->user();
        
        if (!$user->isManager() || !$user->canAccessGolongan($bon->karyawan->golongan_id)) {
            abort(403, 'Unauthorized access.');
        }
        
        if ($bon->status !== 'aktif') {
            return redirect()->route('bon.index')
                ->with('error', 'Hanya bon aktif yang dapat diedit.');
        }
        
        $request->validate([
            'cicilan_per_bulan' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:aktif,lunas,dibatalkan',
        ]);
        
        $bon->update($request->only(['cicilan_per_bulan', 'keterangan', 'status']));
        
        return redirect()->route('bon.index')
            ->with('success', 'Bon berhasil diperbarui.');
    }
    
    public function bayarCicilan(Request $request, Bon $bon)
    {
        $user = auth()->user();
        
        if (!$user->canAccessGolongan($bon->karyawan->golongan_id)) {
            abort(403, 'Unauthorized access.');
        }
        
        $request->validate([
            'jumlah_bayar' => 'nullable|numeric|min:1|max:' . $bon->sisa_bon,
        ]);
        
        $jumlahBayar = $request->jumlah_bayar ?? $bon->cicilan_per_bulan;
        
        if ($bon->bayarCicilan($jumlahBayar)) {
            return redirect()->back()
                ->with('success', 'Cicilan berhasil dibayar.');
        }
        
        return redirect()->back()
            ->with('error', 'Gagal membayar cicilan.');
    }
    
    public function destroy(Bon $bon)
    {
        $user = auth()->user();
        
        if (!$user->isManager() || !$user->canAccessGolongan($bon->karyawan->golongan_id)) {
            abort(403, 'Unauthorized access.');
        }
        
        if ($bon->status === 'lunas') {
            return redirect()->route('bon.index')
                ->with('error', 'Bon yang sudah lunas tidak dapat dihapus.');
        }
        
        $bon->delete();
        
        return redirect()->route('bon.index')
            ->with('success', 'Bon berhasil dihapus.');
    }
}