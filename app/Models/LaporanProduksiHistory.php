<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanProduksiHistory extends Model
{
    use HasFactory;

    protected $table = 'laporan_produksi_histories';

    protected $fillable = [
        'driver_id',
        'stock_id',
        'tanggal',
        'jumlah_produksi',
        'total_pendapatan',
    ];

    // Relasi ke driver (user dengan role driver)
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    // Relasi ke stock (jika kamu butuh info bahan yang dipakai)
    public function stock()
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }
}
