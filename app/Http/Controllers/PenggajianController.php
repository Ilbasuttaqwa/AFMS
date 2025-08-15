<?php

namespace App\Http\Controllers;

use App\Models\Penggajian;
use App\Models\User; // Pastikan menggunakan User
use App\Models\Jabatan;
use App\Models\JadwalKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenggajianController extends Controller
{
    public function index()
    {
        $penggajian = Penggajian::latest()->get();
        $pegawai = User::all(); // Mengambil semua pegawai
        $jabatan = Jabatan::all(); // Mengambil semua jabatan/golongan
        $jadwalKerja = DB::table('jadwal_kerja')->first() ?? (object)[
            'jam_masuk' => '08:00',
            'jam_pulang' => '17:00',
            'toleransi_keterlambatan' => 15,
            'potongan_per_menit' => 1000
        ];
        
        confirmDelete('Hapus Penggajian!', 'Apakah Anda Yakin?');
        return view('admin.penggajian.index', compact('penggajian', 'pegawai', 'jabatan', 'jadwalKerja'));
    }

    public function index1()
    {
        $penggajian = Penggajian::latest()->get();
        $pegawai = User::all(); // Mengambil semua pegawai
        confirmDelete('Hapus Penggajian!', 'Apakah Anda Yakin?');
        return view('user.penggajian.index', compact('penggajian', 'pegawai'));
    }

    public function create()
    {
        $penggajian = Penggajian::all();
        $pegawai = User::all(); // Mengambil semua pegawai
        return view('admin.penggajian.index', compact('penggajian', 'pegawai'));
    }

    public function create1()
    {
        $penggajian = Penggajian::all();
        $pegawai = User::all(); // Mengambil semua pegawai
        return view('user.penggajian.index1', compact('penggajian', 'pegawai'));
    }

    public function store(Request $request)
    {
        // Validasi input
        // $request->validate([...]);

        $penggajian = new Penggajian();
        $penggajian->id_user = $request->id_user; // Menggunakan id_user
        $penggajian->tanggal_gaji = $request->tanggal_gaji;
        $penggajian->jumlah_gaji = $request->jumlah_gaji;
        $penggajian->bonus = $request->bonus;
        $penggajian->potongan = $request->potongan;
        $penggajian->save();

        // Update total gaji pegawai
        $pegawai = User::find($request->id_user);
        if ($pegawai) {
            $total_gaji = $request->jumlah_gaji + ($request->bonus) - ($request->potongan);
            $pegawai->gaji += $total_gaji;
            $pegawai->save();
        }

        return redirect()->route('penggajian.index')->with('success', 'Penggajian berhasil ditambahkan dan total gaji diperbarui.');
    }

    public function store1(Request $request)
    {
        // Validasi input
        // $request->validate([...]);

        $penggajian = new Penggajian();
        $penggajian->id_user = $request->id_user; // Menggunakan id_user
        $penggajian->tanggal_gaji = $request->tanggal_gaji;
        $penggajian->jumlah_gaji = $request->jumlah_gaji;
        $penggajian->bonus = $request->bonus;
        $penggajian->potongan = $request->potongan;
        $penggajian->save();

        // Update total gaji pegawai
        $pegawai = User::find($request->id_user);
        if ($pegawai) {
            $total_gaji = $request->jumlah_gaji + ($request->bonus) - ($request->potongan);
            $pegawai->gaji += $total_gaji;
            $pegawai->save();
        }

        return redirect()->route('penggajian.index1')->with('success', 'Penggajian berhasil ditambahkan dan total gaji diperbarui.');
    }

    public function update(Request $request, $id)
    {
        $penggajian = Penggajian::findOrFail($id);
        $penggajian->id_user = $request->id_user; // Menggunakan id_user
        $penggajian->tanggal_gaji = $request->tanggal_gaji;
        $penggajian->jumlah_gaji = $request->jumlah_gaji;
        $penggajian->bonus = $request->bonus;
        $penggajian->potongan = $request->potongan;
        $penggajian->save();

        // Update total gaji pegawai
        $pegawai = User::find($request->id_user);
        if ($pegawai) {
            $pegawai->gaji = $penggajian->jumlah_gaji + ($request->bonus) - ($request->potongan);
            $pegawai->save();
        }

        return redirect()->route('penggajian.index')->with('success', 'Penggajian berhasil diperbarui dan total gaji diperbarui.');
    }

    public function destroy($id)
    {
        $penggajian = Penggajian::findOrFail($id);

        $pegawai = User::find($penggajian->id_user);
        if ($pegawai) {
            $pegawai->gaji -= ($penggajian->jumlah_gaji + $penggajian->bonus - $penggajian->potongan);
            $pegawai->save();
        }

        $penggajian->delete();
        return redirect()->route('penggajian.index')->with('danger', 'Penggajian berhasil dihapus dan gaji diperbarui!');
    }
    
    /**
     * Display the bonus management page.
     *
     * @return \Illuminate\Http\Response
     */
    public function bonus()
    {
        $pegawai = User::all();
        $jabatan = Jabatan::all();
        return view('admin.penggajian.bonus', compact('pegawai', 'jabatan'));
    }
    
    /**
     * Store a new bonus.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeBonus(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id',
            'jumlah_bonus' => 'required|numeric|min:0',
            'keterangan' => 'required|string'
        ]);
        
        $pegawai = User::find($request->id_user);
        if ($pegawai) {
            // Create a new penggajian record for the bonus
            $penggajian = new Penggajian();
            $penggajian->id_user = $request->id_user;
            $penggajian->tanggal_gaji = now();
            $penggajian->jumlah_gaji = 0; // No regular salary
            $penggajian->bonus = $request->jumlah_bonus;
            $penggajian->potongan = 0;
            $penggajian->keterangan = $request->keterangan;
            $penggajian->save();
            
            // Update the employee's total salary
            $pegawai->gaji += $request->jumlah_bonus;
            $pegawai->save();
        }
        
        return redirect()->route('penggajian.bonus')
            ->with('success', 'Bonus berhasil ditambahkan');
    }
    
    /**
     * Display the potongan management page.
     *
     * @return \Illuminate\Http\Response
     */
    public function potongan()
    {
        $pegawai = User::all();
        $jabatan = Jabatan::all();
        return view('admin.penggajian.potongan', compact('pegawai', 'jabatan'));
    }
    
    /**
     * Store a new potongan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storePotongan(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id',
            'jumlah_potongan' => 'required|numeric|min:0',
            'keterangan' => 'required|string'
        ]);
        
        $pegawai = User::find($request->id_user);
        if ($pegawai) {
            // Create a new penggajian record for the potongan
            $penggajian = new Penggajian();
            $penggajian->id_user = $request->id_user;
            $penggajian->tanggal_gaji = now();
            $penggajian->jumlah_gaji = 0; // No regular salary
            $penggajian->bonus = 0;
            $penggajian->potongan = $request->jumlah_potongan;
            $penggajian->keterangan = $request->keterangan;
            $penggajian->save();
            
            // Update the employee's total salary
            $pegawai->gaji -= $request->jumlah_potongan;
            $pegawai->save();
        }
        
        return redirect()->route('penggajian.index')
            ->with('success', 'Potongan berhasil ditambahkan');
    }
    
    /**
     * Update gaji for a specific golongan/jabatan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateGolongan(Request $request, $id)
    {
        $request->validate([
            'gaji_pokok' => 'required|numeric|min:0',
        ]);
        
        $jabatan = Jabatan::findOrFail($id);
        $jabatan->gaji_pokok = $request->gaji_pokok;
        $jabatan->save();
        
        // Update gaji for all employees with this jabatan
        $pegawai = User::where('id_jabatan', $id)->get();
        foreach ($pegawai as $p) {
            // You might want to implement a more sophisticated logic here
            // For now, we just update the base salary
            $p->gaji = $request->gaji_pokok;
            $p->save();
        }
        
        return redirect()->route('penggajian.index')
            ->with('success', 'Gaji golongan berhasil diperbarui');
    }
    
    /**
     * Update work schedule settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateJadwal(Request $request)
    {
        $request->validate([
            'jam_masuk' => 'required',
            'jam_pulang' => 'required',
            'toleransi_keterlambatan' => 'required|integer|min:0',
            'potongan_per_menit' => 'required|integer|min:0',
        ]);
        
        // Check if jadwal_kerja table exists, if not create it
        if (!DB::table('jadwal_kerja')->exists()) {
            DB::statement('CREATE TABLE jadwal_kerja (
                id INT AUTO_INCREMENT PRIMARY KEY,
                jam_masuk TIME NOT NULL,
                jam_pulang TIME NOT NULL,
                toleransi_keterlambatan INT NOT NULL DEFAULT 15,
                potongan_per_menit INT NOT NULL DEFAULT 1000,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )');            
        }
        
        // Update or create jadwal kerja settings
        DB::table('jadwal_kerja')->updateOrInsert(
            ['id' => 1],
            [
                'jam_masuk' => $request->jam_masuk,
                'jam_pulang' => $request->jam_pulang,
                'toleransi_keterlambatan' => $request->toleransi_keterlambatan,
                'potongan_per_menit' => $request->potongan_per_menit,
                'updated_at' => now()
            ]
        );
        
        return redirect()->route('penggajian.index')
            ->with('success', 'Jadwal kerja berhasil diperbarui');
    }
}
