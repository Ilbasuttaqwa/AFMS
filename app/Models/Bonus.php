<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bonus extends Model
{
    use HasFactory;

    protected $table = 'bonus';

    protected $fillable = [
        'karyawan_id',
        'jumlah_bonus',
        'jenis_bonus',
        'bulan',
        'tahun',
        'keterangan',
        'created_by',
    ];

    protected $casts = [
        'jumlah_bonus' => 'decimal:2',
    ];

    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeByMonth($query, $month, $year)
    {
        return $query->where('bulan', $month)
                    ->where('tahun', $year);
    }

    public function scopeByKaryawan($query, $karyawanId)
    {
        return $query->where('karyawan_id', $karyawanId);
    }

    public function scopeByJenis($query, $jenisBonus)
    {
        return $query->where('jenis_bonus', $jenisBonus);
    }

    public function getJenisBonusLabelAttribute()
    {
        return match($this->jenis_bonus) {
            'tidak_libur' => 'Bonus Tidak Libur',
            'kinerja' => 'Bonus Kinerja',
            'lainnya' => 'Bonus Lainnya',
            default => 'Tidak Diketahui'
        };
    }

    public function getBulanNamaAttribute()
    {
        $bulanNama = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        return $bulanNama[$this->bulan] ?? 'Tidak Diketahui';
    }
}