<?php

namespace App\Http\Controllers;

use App\Models\SakitIzin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SakitIzinController extends Controller
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
            // Admin can see all sick/leave data, optionally filtered by branch
            $sakitIzins = SakitIzin::with(['user', 'cabang'])->latest()->get();
        } else {
            // Employee can see sick/leave data from their branch
            $sakitIzins = SakitIzin::whereHas('user', function($query) use ($user) {
                if ($user->id_cabang) {
                    $query->where('id_cabang', $user->id_cabang);
                }
            })->with(['user', 'cabang'])->latest()->get();
        }
        
        return view('user.sakit-izin.index', compact('sakitIzins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        
        // Get employees from the same branch for selection
        $userCabang = $user->id_cabang;
        $employees = User::where('role', '!=', 'manager');
        if ($userCabang) {
            $employees = $employees->where('id_cabang', $userCabang);
        }
        $employees = $employees->get();
        
        return view('user.sakit-izin.create', compact('employees'));
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
            'id_user' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'keterangan' => 'required|in:Sakit,Izin',
            'catatan' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $selectedEmployee = User::findOrFail($request->id_user);

        SakitIzin::create([
            'id_user' => $request->id_user,
            'id_cabang' => $selectedEmployee->id_cabang,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'catatan' => $request->catatan,
            'created_by' => $user->id,
        ]);

        return redirect()->route('user.sakit-izin.index')->with('success', 'Data sakit/izin berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SakitIzin  $sakitIzin
     * @return \Illuminate\Http\Response
     */
    public function show(SakitIzin $sakitIzin)
    {
        $user = Auth::user();
        
        // Check if user can view this record
        if ($user->role !== 'manager' && $sakitIzin->user->id_cabang !== $user->id_cabang) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('user.sakit-izin.show', compact('sakitIzin'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SakitIzin  $sakitIzin
     * @return \Illuminate\Http\Response
     */
    public function edit(SakitIzin $sakitIzin)
    {
        $user = Auth::user();
        
        // Check if user can edit this record
        if ($user->role !== 'manager' && $sakitIzin->user->id_cabang !== $user->id_cabang) {
            abort(403, 'Unauthorized action.');
        }
        
        // Get employees from the same branch for selection
        $employees = User::where('role', '!=', 'manager');
        if ($user->id_cabang) {
            $employees = $employees->where('id_cabang', $user->id_cabang);
        }
        $employees = $employees->get();
        
        return view('user.sakit-izin.edit', compact('sakitIzin', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SakitIzin  $sakitIzin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SakitIzin $sakitIzin)
    {
        $user = Auth::user();
        
        // Check if user can update this record
        if ($user->role !== 'manager' && $sakitIzin->user->id_cabang !== $user->id_cabang) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'id_user' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'keterangan' => 'required|in:Sakit,Izin',
            'catatan' => 'nullable|string|max:500',
        ]);

        $selectedEmployee = User::findOrFail($request->id_user);

        $sakitIzin->update([
            'id_user' => $request->id_user,
            'id_cabang' => $selectedEmployee->id_cabang,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('user.sakit-izin.index')->with('success', 'Data sakit/izin berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SakitIzin  $sakitIzin
     * @return \Illuminate\Http\Response
     */
    public function destroy(SakitIzin $sakitIzin)
    {
        $user = Auth::user();
        
        // Check if user can delete this record
        if ($user->role !== 'manager' && $sakitIzin->user->id_cabang !== $user->id_cabang) {
            abort(403, 'Unauthorized action.');
        }

        $sakitIzin->delete();

        return redirect()->route('user.sakit-izin.index')->with('success', 'Data sakit/izin berhasil dihapus.');
    }

    /**
     * Search employees by name (AJAX)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchEmployees(Request $request)
    {
        $user = Auth::user();
        $search = $request->get('q');
        
        $employees = User::where('role', '!=', 'manager')
            ->where('nama_pegawai', 'LIKE', "%{$search}%");
            
        if ($user->id_cabang) {
            $employees = $employees->where('id_cabang', $user->id_cabang);
        }
        
        $employees = $employees->select('id', 'nama_pegawai')->get();
        
        return response()->json($employees);
    }
}