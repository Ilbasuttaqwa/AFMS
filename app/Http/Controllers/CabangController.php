<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\User;
use Illuminate\Http\Request;

class CabangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cabang = Cabang::all();
        return view('admin.cabang.index', compact('cabang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.cabang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_cabang' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'kode_cabang' => 'required|string|unique:cabang,kode_cabang',
        ]);

        Cabang::create([
            'nama_cabang' => $request->nama_cabang,
            'alamat' => $request->alamat,
            'kode_cabang' => $request->kode_cabang,
        ]);

        return redirect()->route('cabang.index')->with('success', 'Cabang berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cabang = Cabang::findOrFail($id);
        $users = User::where('id_cabang', $id)->get();
        return view('admin.cabang.show', compact('cabang', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $cabang = Cabang::findOrFail($id);
        return view('admin.cabang.edit', compact('cabang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_cabang' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'kode_cabang' => 'required|string|unique:cabang,kode_cabang,' . $id,
        ]);

        $cabang = Cabang::findOrFail($id);
        $cabang->update([
            'nama_cabang' => $request->nama_cabang,
            'alamat' => $request->alamat,
            'kode_cabang' => $request->kode_cabang,
        ]);

        return redirect()->route('cabang.index')->with('success', 'Cabang berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cabang = Cabang::findOrFail($id);
        
        // Check if there are users associated with this branch
        $usersCount = User::where('id_cabang', $id)->count();
        if ($usersCount > 0) {
            return redirect()->route('cabang.index')->with('error', 'Cabang tidak dapat dihapus karena masih memiliki pegawai!');
        }
        
        $cabang->delete();
        return redirect()->route('cabang.index')->with('success', 'Cabang berhasil dihapus!');
    }
}
