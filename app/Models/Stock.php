<?php

// app/Models/Stock.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stock extends Model {
    use HasFactory;

    protected $fillable = [
        'nama_bahan',
        'jumlah',
        'satuan'
    ];
    public function laporanProduksi() {
        return $this->hasMany(LaporanProduksi::class);
    }

     public function histories()
    {
        return $this->hasMany(StockHistory::class);
    }

    public function latestHistory()
    {
        return $this->hasOne(StockHistory::class)->latestOfMany();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}

