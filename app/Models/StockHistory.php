<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class StockHistory extends Model
{
    use HasFactory;

    protected $fillable = ['stock_id', 'jumlah', 'tanggal'];

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }
}
