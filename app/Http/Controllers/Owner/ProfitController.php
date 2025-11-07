<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\LaporanPenjualan;

class ProfitController extends Controller
{
    public function index()
    {
        // Hitung total profit (keuntungan)
        $totalProfit = LaporanPenjualan::with('minuman')
            ->where('status', 'terjual')
            ->get()
            ->sum(fn($item) => (($item->minuman->harga ?? 0) - ($item->minuman->hpp ?? 0)) * ($item->jumlah ?? 0));

        // Kalau kamu ingin tampilkan juga total penjualan
        $totalPenjualan = LaporanPenjualan::where('status', 'terjual')->sum('jumlah');

        // Kirim ke view
        return view('owner.laporan.profit', compact('totalProfit', 'totalPenjualan'));
    }
}
