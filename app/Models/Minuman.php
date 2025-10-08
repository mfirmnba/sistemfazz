<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Minuman extends Model
{
    use HasFactory;

    protected $table = 'minumans';

    protected $fillable = [
        'nama',
        'harga',
        'hpp',
        'stok',
        'stok_hari_ini',
        'stok_besok',
    ];

    // ✅ Hitung otomatis keuntungan
    public function getKeuntunganAttribute()
    {
        return $this->harga - $this->hpp;
    }

    // ✅ Hitung otomatis margin (dalam persen)
    public function getMarginAttribute()
    {
        if ($this->harga > 0) {
            return round(($this->keuntungan / $this->harga) * 100, 2);
        }
        return 0;
    }
}
