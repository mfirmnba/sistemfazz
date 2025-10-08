<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPenjualan extends Model
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

    // relasi ke minuman
    public function minuman()
    {
        return $this->belongsTo(Minuman::class);
    }

    // relasi ke user (driver)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // atur tanggal otomatis saat create
    protected static function booted()
    {
        static::creating(function ($laporan) {
            if (empty($laporan->tanggal)) {
                $laporan->tanggal = now()->toDateString();
            }
        });
    }
}
