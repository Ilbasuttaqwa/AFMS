<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bon extends Model
{
    use HasFactory;

    protected $fillable = [
        'pegawai_id',
        'jumlah',
        'keterangan',
        'tanggal',
        'status',
        'created_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
    ];

    /**
     * Relationship dengan Pegawai (User)
     */
    public function pegawai()
    {
        return $this->belongsTo(User::class, 'pegawai_id');
    }

    /**
     * Relationship dengan User yang membuat
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
