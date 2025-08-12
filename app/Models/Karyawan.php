<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawan';

    protected $fillable = [
        'nama_karyawan',
        'nip',
        'email',
        'no_telepon',
        'alamat',
        'tanggal_lahir',
        'jenis_kelamin',
        'tanggal_masuk',
        'golongan_id',
        'lokasi_id',
        'fingerprint_id',
        'is_active',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'date',
        'is_active' => 'boolean',
    ];

    public function golongan(): BelongsTo
    {
        return $this->belongsTo(Golongan::class);
    }

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    public function bon(): HasMany
    {
        return $this->hasMany(Bon::class);
    }

    public function bonus(): HasMany
    {
        return $this->hasMany(Bonus::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByGolongan($query, $golonganIds)
    {
        if (is_array($golonganIds)) {
            return $query->whereIn('golongan_id', $golonganIds);
        }
        return $query->where('golongan_id', $golonganIds);
    }

    public function scopeByLokasi($query, $lokasiId)
    {
        return $query->where('lokasi_id', $lokasiId);
    }

    public function getUmurAttribute()
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->age : null;
    }

    public function getMasaKerjaAttribute()
    {
        return $this->tanggal_masuk->diffInYears(now());
    }
}