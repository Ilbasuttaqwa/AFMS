<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ManajemenAkunController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Hanya menampilkan akun dengan role manager dan admin
        // Tidak menampilkan akun pegawai biasa
        $users = User::with('cabang')
                    ->whereIn('role', ['manager', 'admin'])
                    ->orderBy('created_at', 'desc')
                    ->get();
        
        $cabangs = Cabang::all();
        
        return view('admin.manajemen-akun.index', compact('users', 'cabangs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_pegawai' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'id_cabang' => 'required|exists:cabang,id',
            'role' => 'required|in:manager,admin',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        User::create([
            'nama_pegawai' => $request->nama_pegawai,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_cabang' => $request->id_cabang,
            'role' => $request->role,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('manajemen-akun.index')
            ->with('success', 'Akun karyawan berhasil dibuat!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'nama_pegawai' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'id_cabang' => 'required|exists:cabang,id',
            'role' => 'required|in:manager,admin',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $updateData = [
            'nama_pegawai' => $request->nama_pegawai,
            'email' => $request->email,
            'id_cabang' => $request->id_cabang,
            'role' => $request->role,
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('manajemen-akun.index')
            ->with('success', 'Akun karyawan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Mencegah penghapusan akun sendiri
        if ($user->id === auth()->id()) {
            return redirect()->route('manajemen-akun.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }
        
        // Simpan nama untuk pesan sukses
        $namaUser = $user->nama_pegawai;
        $roleUser = ucfirst($user->role);
        
        $user->delete();

        return redirect()->route('manajemen-akun.index')
            ->with('success', "Akun {$roleUser} '{$namaUser}' berhasil dihapus!");
    }
}