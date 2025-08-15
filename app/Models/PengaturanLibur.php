<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaturanLibur extends Model
{
    use HasFactory;
    
    protected $table = 'pengaturan_libur';
    
    protected $fillable = [
        'tanggal_libur',
        'tanggal_selesai',
        'nama_libur',
        'keterangan',
        'jenis_libur',
        'is_active'
    ];
    
    protected $casts = [
        'tanggal_libur' => 'date',
        'tanggal_selesai' => 'date',
        'is_active' => 'boolean'
    ];

    /**
     * Check if a given date is a holiday
     */
    public static function isHoliday($date)
    {
        return self::where('is_active', true)
            ->where(function ($query) use ($date) {
                $query->where(function ($q) use ($date) {
                    // Single day holiday
                    $q->where('tanggal_libur', $date)
                      ->whereNull('tanggal_selesai');
                })->orWhere(function ($q) use ($date) {
                    // Range holiday
                    $q->where('tanggal_libur', '<=', $date)
                      ->where('tanggal_selesai', '>=', $date);
                });
            })
            ->exists();
    }

    /**
     * Get holiday info for a given date
     */
    public static function getHolidayInfo($date)
    {
        return self::where('is_active', true)
            ->where(function ($query) use ($date) {
                $query->where(function ($q) use ($date) {
                    // Single day holiday
                    $q->where('tanggal_libur', $date)
                      ->whereNull('tanggal_selesai');
                })->orWhere(function ($q) use ($date) {
                    // Range holiday
                    $q->where('tanggal_libur', '<=', $date)
                      ->where('tanggal_selesai', '>=', $date);
                });
            })
            ->first();
    }

    /**
     * Get formatted date range
     */
    public function getFormattedDateRangeAttribute()
    {
        if ($this->tanggal_selesai) {
            return $this->tanggal_libur->format('d/m/Y') . ' - ' . $this->tanggal_selesai->format('d/m/Y');
        }
        return $this->tanggal_libur->format('d/m/Y');
    }
}
