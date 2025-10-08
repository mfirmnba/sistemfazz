<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPenjualanHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'minuman_id',
        'jumlah',
        'status',
        'bukti_foto',
        'tanggal',
    ];

    // Relasi ke user (driver)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke minuman
    public function minuman()
    {
        return $this->belongsTo(Minuman::class);
    }
}
