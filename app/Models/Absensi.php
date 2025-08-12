<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    protected $fillable = [
        'karyawan_id',
        'tanggal',
        'waktu_masuk_pagi',
        'waktu_keluar_istirahat',
        'waktu_masuk_istirahat',
        'waktu_pulang',
        'terlambat_pagi',
        'terlambat_siang',
        'menit_keterlambatan_pagi',
        'menit_keterlambatan_siang',
        'status',
        'keterangan',
        'fingerprint_device_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu_masuk_pagi' => 'datetime:H:i:s',
        'waktu_keluar_istirahat' => 'datetime:H:i:s',
        'waktu_masuk_istirahat' => 'datetime:H:i:s',
        'waktu_pulang' => 'datetime:H:i:s',
        'terlambat_pagi' => 'boolean',
        'terlambat_siang' => 'boolean',
    ];

    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function scopeByMonth($query, $month, $year)
    {
        return $query->whereMonth('tanggal', $month)
                    ->whereYear('tanggal', $year);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal', [$startDate, $endDate]);
    }

    public function scopeTerlambatHariIni($query)
    {
        return $query->whereDate('tanggal', today())
                    ->where(function($q) {
                        $q->where('terlambat_pagi', true)
                          ->orWhere('terlambat_siang', true);
                    });
    }

    public function calculateKeterlambatan()
    {
        $golongan = $this->karyawan->golongan;
        
        // Hitung keterlambatan pagi
        if ($this->waktu_masuk_pagi) {
            $jamMasukPagi = Carbon::createFromFormat('H:i:s', $golongan->jam_masuk_pagi);
            $waktuMasuk = Carbon::createFromFormat('H:i:s', $this->waktu_masuk_pagi);
            
            if ($waktuMasuk->gt($jamMasukPagi)) {
                $this->terlambat_pagi = true;
                $this->menit_keterlambatan_pagi = $waktuMasuk->diffInMinutes($jamMasukPagi);
            }
        }

        // Hitung keterlambatan siang
        if ($this->waktu_masuk_istirahat) {
            $jamMasukSiang = Carbon::createFromFormat('H:i:s', $golongan->jam_masuk_siang);
            $waktuMasukSiang = Carbon::createFromFormat('H:i:s', $this->waktu_masuk_istirahat);
            
            if ($waktuMasukSiang->gt($jamMasukSiang)) {
                $this->terlambat_siang = true;
                $this->menit_keterlambatan_siang = $waktuMasukSiang->diffInMinutes($jamMasukSiang);
            }
        }

        // Update status hadir jika ada waktu masuk
        if ($this->waktu_masuk_pagi || $this->waktu_masuk_istirahat) {
            $this->status = 'hadir';
        }

        $this->save();
    }

    public function getTotalMenitKeterlambatanAttribute()
    {
        return $this->menit_keterlambatan_pagi + $this->menit_keterlambatan_siang;
    }
}