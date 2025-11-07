<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\LaporanProduksi;

class StockController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $stocks = Stock::withSum(['laporanProduksi as terpakai_hari_ini' => function($q) use ($today) {
            $q->whereDate('tanggal', $today);
        }], 'jumlah_digunakan')
        ->withSum('laporanProduksi as terpakai_total', 'jumlah_digunakan')
        ->orderBy('nama_bahan')
        ->get();

        $totalStock = $stocks->sum('jumlah');

        $stokMingguan = LaporanProduksi::selectRaw('DATE(tanggal) as tanggal, SUM(jumlah_digunakan) as total_stok')
            ->whereBetween('tanggal', [now()->subDays(6), now()])
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        return view('owner.laporan.stock', compact('stocks', 'totalStock', 'stokMingguan'));
    }
}
