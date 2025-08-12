<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bon extends Model
{
    use HasFactory;

    protected $table = 'bon';

    protected $fillable = [
        'karyawan_id',
        'jumlah_bon',
        'sisa_bon',
        'cicilan_per_bulan',
        'tanggal_bon',
        'keterangan',
        'status',
        'created_by',
    ];

    protected $casts = [
        'jumlah_bon' => 'decimal:2',
        'sisa_bon' => 'decimal:2',
        'cicilan_per_bulan' => 'decimal:2',
        'tanggal_bon' => 'date',
    ];

    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeByKaryawan($query, $karyawanId)
    {
        return $query->where('karyawan_id', $karyawanId);
    }

    public function bayarCicilan($jumlahBayar = null)
    {
        $jumlahBayar = $jumlahBayar ?? $this->cicilan_per_bulan;
        
        if ($this->sisa_bon >= $jumlahBayar) {
            $this->sisa_bon -= $jumlahBayar;
            
            if ($this->sisa_bon <= 0) {
                $this->status = 'lunas';
                $this->sisa_bon = 0;
            }
            
            $this->save();
            return true;
        }
        
        return false;
    }

    public function getPersentaseLunasAttribute()
    {
        if ($this->jumlah_bon <= 0) return 0;
        
        $terbayar = $this->jumlah_bon - $this->sisa_bon;
        return round(($terbayar / $this->jumlah_bon) * 100, 2);
    }

    public function getSisaBulanAttribute()
    {
        if ($this->cicilan_per_bulan <= 0) return 0;
        
        return ceil($this->sisa_bon / $this->cicilan_per_bulan);
    }
}