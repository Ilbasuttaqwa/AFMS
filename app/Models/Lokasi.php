<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lokasi extends Model
{
    use HasFactory;

    protected $table = 'lokasi';

    protected $fillable = [
        'nama_lokasi',
        'alamat',
        'kode_lokasi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function karyawan(): HasMany
    {
        return $this->hasMany(Karyawan::class);
    }

    public function getJumlahKaryawanAttribute()
    {
        return $this->karyawan()->count();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}