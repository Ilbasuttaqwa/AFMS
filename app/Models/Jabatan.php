<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'nama_jabatan', 'gaji_pokok', 'batas_keterlambatan', 'potongan_keterlambatan', 'sanksi_keterlambatan',
        'toleransi_keterlambatan', 'jam_masuk', 'jam_masuk_siang',
        // Lateness penalty ranges
        'potongan_0_30', 'potongan_31_45', 'potongan_46_60', 'potongan_61_100', 'potongan_101_200', 'potongan_200_plus',
        // Afternoon lateness penalty ranges
        'potongan_siang_0_30', 'potongan_siang_31_45', 'potongan_siang_46_60', 'potongan_siang_61_100', 'potongan_siang_101_200', 'potongan_siang_200_plus',
        // Minimum attendance time settings
        'minimal_absen_pagi', 'potongan_absen_awal', 'minimal_absen_siang',
        // Leave settings
        'jatah_libur_per_bulan', 'denda_per_hari_libur', 'bonus_tidak_libur'
    ];
    public $timestamp = true;

    public function pegawai()
    {
        return $this->hasMany(User::class, 'id_jabatan');
    }


}
