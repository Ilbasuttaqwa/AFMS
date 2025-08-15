<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalKerja extends Model
{
    use HasFactory;
    
    protected $table = 'jadwal_kerja';
    protected $fillable = [
        'jam_masuk',
        'jam_masuk_siang',
        'toleransi_keterlambatan',
        'potongan_per_menit'
    ];
}