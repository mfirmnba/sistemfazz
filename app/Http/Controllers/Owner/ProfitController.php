<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\LaporanPenjualan;
use App\Models\Minuman;

class ProfitController extends Controller
{
    public function index()
    {
        // Hitung total profit (keuntungan)
        $totalProfit = LaporanPenjualan::with('minuman')
            ->where('status', 'terjual')
            ->get()
            ->sum(fn($item) => (($item->minuman->harga ?? 0) - ($item->minuman->hpp ?? 0)) * ($item->jumlah ?? 0));

        // Kamu juga bisa tambahkan data lain kalau perlu
        $totalPenjualan = LaporanPenjualan::where('status', 'terjual')->sum('jumlah');
        $totalKeuntunganSemua = LaporanPenjualan::with('minuman')
            ->where('status', 'terjual')
            ->get()
            ->sum(fn($item) => (($item->minuman->harga ?? 0) - ($item->minuman->hpp ?? 0)) * ($item->jumlah ?? 0));

        $profitPerMinuman = LaporanPenjualan::join('minumans', 'laporan_penjualans.minuman_id', '=', 'minumans.id')
            ->where('laporan_penjualans.status', 'terjual')
            ->selectRaw('minumans.nama, SUM(laporan_penjualans.jumlah) as total_cup, SUM((minumans.harga - minumans.hpp) * laporan_penjualans.jumlah) as total_profit')
            ->groupBy('minumans.nama')
            ->orderByDesc('total_profit')
            ->get();

        return view('owner.laporan.profit', compact('totalKeuntunganSemua', 'profitPerMinuman'));
    }
}
