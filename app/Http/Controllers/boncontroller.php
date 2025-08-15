<?php

namespace App\Http\Controllers;

use App\Models\Bon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BonController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'manager') {
            // Admin can see all bon data, optionally filtered by branch
            $bons = Bon::with(['pegawai', 'creator'])->latest()->get();
        } else {
            // Employee can see bon data from users in the same branch
            $bons = Bon::whereHas('pegawai', function($query) use ($user) {
                        $query->where('id_cabang', $user->id_cabang);
                    })
                    ->with(['pegawai', 'creator'])
                    ->latest()
                    ->get();
        }
        
        return view('user.bon.index', compact('bons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        
        // Get all employees in the same branch/location
        $employees = \App\Models\User::where('id_cabang', $user->id_cabang)
                                    ->where('role', '!=', 'manager')
                                    ->with(['jabatan', 'cabang'])
                                    ->orderBy('nama_pegawai')
                                    ->get();
        
        return view('user.bon.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        
        // Verify that the selected employee is in the same branch
        $selectedEmployee = \App\Models\User::find($request->pegawai_id);
        if ($selectedEmployee->id_cabang !== $user->id_cabang) {
            return redirect()->back()->withErrors(['pegawai_id' => 'Anda hanya dapat menambahkan bon untuk karyawan di lokasi yang sama.']);
        }

        Bon::create([
            'pegawai_id' => $request->pegawai_id,
            'tanggal' => $request->tanggal,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
            'status' => 'approved',
            'created_by' => $user->id,
        ]);

        return redirect()->route('user.bon.index')->with('success', 'Bon berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bon  $bon
     * @return \Illuminate\Http\Response
     */
    public function show(Bon $bon)
    {
        $user = Auth::user();
        
        // Check if user can view this bon (same branch employees)
        if ($user->role !== 'manager') {
            $bonEmployee = \App\Models\User::find($bon->pegawai_id);
            if ($bonEmployee->id_cabang !== $user->id_cabang) {
                abort(403, 'Unauthorized action.');
            }
        }
        
        return view('user.bon.show', compact('bon'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bon  $bon
     * @return \Illuminate\Http\Response
     */
    public function edit(Bon $bon)
    {
        $user = Auth::user();
        
        // Check if user can edit this bon (same branch employees)
        if ($user->role !== 'manager') {
            $bonEmployee = \App\Models\User::find($bon->pegawai_id);
            if ($bonEmployee->id_cabang !== $user->id_cabang) {
                abort(403, 'Unauthorized action.');
            }
        }
        
        // Get all employees in the same branch/location
        $employees = \App\Models\User::where('id_cabang', $user->id_cabang)
                                    ->where('role', '!=', 'manager')
                                    ->with(['jabatan', 'cabang'])
                                    ->orderBy('nama_pegawai')
                                    ->get();
        
        return view('user.bon.edit', compact('bon', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bon  $bon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bon $bon)
    {
        $user = Auth::user();
        
        // Check if user can update this bon (same branch employees)
        if ($user->role !== 'manager') {
            $bonEmployee = \App\Models\User::find($bon->pegawai_id);
            if ($bonEmployee->id_cabang !== $user->id_cabang) {
                abort(403, 'Unauthorized action.');
            }
        }

        $request->validate([
            'pegawai_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'jumlah' => 'required|numeric|min:1',
            'keterangan' => 'required|string|max:255',
        ]);

        // Verify that the selected employee is in the same branch
        $selectedEmployee = \App\Models\User::find($request->pegawai_id);
        if ($selectedEmployee->id_cabang !== $user->id_cabang) {
            return redirect()->back()->withErrors(['pegawai_id' => 'Anda hanya dapat mengupdate bon untuk karyawan di lokasi yang sama.']);
        }

        $bon->update([
            'pegawai_id' => $request->pegawai_id,
            'tanggal' => $request->tanggal,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
            'status' => 'approved',
        ]);

        return redirect()->route('user.bon.index')->with('success', 'Bon berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bon  $bon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bon $bon)
    {
        $user = Auth::user();
        
        // Check if user can delete this bon (same branch employees)
        if ($user->role !== 'manager') {
            $bonEmployee = \App\Models\User::find($bon->pegawai_id);
            if ($bonEmployee->id_cabang !== $user->id_cabang) {
                abort(403, 'Unauthorized action.');
            }
        }

        $bon->delete();

        return redirect()->route('user.bon.index')->with('success', 'Bon berhasil dihapus.');
    }

    /**
     * Get employees for AJAX search
     */
    public function getEmployees(Request $request)
    {
        $user = Auth::user();
        $search = $request->get('search', '');
        
        $employees = \App\Models\User::where('id_cabang', $user->id_cabang)
                                    ->where('role', '!=', 'manager')
                                    ->when($search, function($query) use ($search) {
                                        $query->where('nama_pegawai', 'LIKE', '%' . $search . '%');
                                    })
                                    ->with(['jabatan', 'cabang'])
                                    ->orderBy('nama_pegawai')
                                    ->get();
        
        return response()->json($employees);
    }
}