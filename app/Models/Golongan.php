<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Golongan extends Model
{
    use HasFactory;

    protected $table = 'golongan';

    protected $fillable = [
        'nama_golongan',
        'gaji_pokok',
        'jam_masuk_pagi',
        'jam_pulang_siang',
        'jam_masuk_siang',
        'jam_pulang_sore',
        'maksimal_ceklok_pagi',
        'maksimal_ceklok_siang',
        'denda_keterlambatan',
        'potongan_tidak_masuk',
        'jatah_libur',
        'bonus_tidak_libur',
    ];

    protected $casts = [
        'gaji_pokok' => 'decimal:2',
        'denda_keterlambatan' => 'decimal:2',
        'potongan_tidak_masuk' => 'decimal:2',
        'bonus_tidak_libur' => 'decimal:2',
        'jam_masuk_pagi' => 'datetime:H:i',
        'jam_pulang_siang' => 'datetime:H:i',
        'jam_masuk_siang' => 'datetime:H:i',
        'jam_pulang_sore' => 'datetime:H:i',
    ];

    public function karyawan(): HasMany
    {
        return $this->hasMany(Karyawan::class);
    }

    public function getJumlahKaryawanAttribute()
    {
        return $this->karyawan()->count();
    }
}