<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    use HasFactory;
    
    protected $table = 'cabang';
    protected $fillable = [
        'nama_cabang', 
        'alamat', 
        'kode_cabang',
        'fingerprint_ip',
        'fingerprint_port',
        'fingerprint_active',
        'last_sync'
    ];
    
    protected $casts = [
        'fingerprint_active' => 'boolean',
        'last_sync' => 'datetime',
    ];
    
    public function users()
    {
        return $this->hasMany(User::class, 'id_cabang');
    }
}
