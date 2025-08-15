<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaturanHariLiburMingguan extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_hari_libur_mingguan';

    protected $fillable = [
        'hari',
        'is_libur',
        'keterangan'
    ];

    protected $casts = [
        'is_libur' => 'boolean'
    ];

    /**
     * Get day name in Indonesian
     */
    public function getNamaHariAttribute()
    {
        $days = [
            'senin' => 'Senin',
            'selasa' => 'Selasa', 
            'rabu' => 'Rabu',
            'kamis' => 'Kamis',
            'jumat' => 'Jumat',
            'sabtu' => 'Sabtu',
            'minggu' => 'Minggu'
        ];

        return $days[$this->hari] ?? $this->hari;
    }

    /**
     * Check if a specific day is holiday
     */
    public static function isHoliday($dayName)
    {
        $setting = self::where('hari', strtolower($dayName))->first();
        return $setting ? $setting->is_libur : false;
    }

    /**
     * Get all holiday days
     */
    public static function getHolidayDays()
    {
        return self::where('is_libur', true)->pluck('hari')->toArray();
    }
}