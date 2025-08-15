<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusGaji extends Model
{
    use HasFactory;
    
    protected $table = 'bonus_gaji';
    protected $fillable = ['id_user', 'jumlah_bonus', 'keterangan', 'bulan_tahun'];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}