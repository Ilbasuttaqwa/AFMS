<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FingerprintAttendance extends Model
{
    protected $table = 'fingerprint_attendance';
    
    protected $fillable = [
        'device_user_id',
        'device_ip',
        'attendance_time',
        'attendance_type',
        'verification_type',
        'is_processed',
        'user_id',
        'cabang_id',
        'raw_data'
    ];

    protected $casts = [
        'attendance_time' => 'datetime',
        'is_processed' => 'boolean',
        'attendance_type' => 'integer'
    ];

    // Relasi ke User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Cabang
    public function cabang(): BelongsTo
    {
        return $this->belongsTo(Cabang::class);
    }

    // Scope untuk data yang belum diproses
    public function scopeUnprocessed($query)
    {
        return $query->where('is_processed', false);
    }

    // Scope untuk filter berdasarkan cabang
    public function scopeByCabang($query, $cabangId)
    {
        return $query->where('cabang_id', $cabangId);
    }

    // Scope untuk filter berdasarkan tanggal
    public function scopeByDate($query, $date)
    {
        return $query->whereDate('attendance_time', $date);
    }

    // Scope untuk filter berdasarkan range tanggal
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('attendance_time', [$startDate, $endDate]);
    }

    // Helper method untuk mendapatkan nama tipe absensi
    public function getAttendanceTypeNameAttribute()
    {
        $types = [
            1 => 'Masuk',
            2 => 'Keluar',
            3 => 'Istirahat Keluar',
            4 => 'Istirahat Masuk'
        ];

        return $types[$this->attendance_type] ?? 'Unknown';
    }
}
