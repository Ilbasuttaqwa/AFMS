<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PotonganGaji extends Model
{
    use HasFactory;
    
    protected $table = 'potongan_gaji';
    protected $fillable = ['id_user', 'jumlah_potongan', 'keterangan', 'bulan_tahun'];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}