<?php

// app/Models/LaporanProduksi.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanProduksi extends Model {
    protected $fillable = ['user_id', 'stock_id', 'jumlah_digunakan', 'tanggal'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function stock() {
        return $this->belongsTo(Stock::class);
    }
}

