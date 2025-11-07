<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\LaporanPenjualan;
use App\Models\Minuman;

class OmsetController extends Controller
{
    public function index()
    {
        $totalPendapatan = LaporanPenjualan::with('minuman')
            ->where('status', 'terjual')
            ->get()
            ->sum(fn($item) => ($item->jumlah ?? 0) * ($item->minuman->harga ?? 0));

        $omsetPerMinuman = LaporanPenjualan::join('minumans', 'laporan_penjualans.minuman_id', '=', 'minumans.id')
            ->where('laporan_penjualans.status', 'terjual')
            ->selectRaw('minumans.nama, SUM(laporan_penjualans.jumlah) as total_cup, SUM(laporan_penjualans.jumlah * minumans.harga) as total_omset')
            ->groupBy('minumans.nama')
            ->orderByDesc('total_omset')
            ->get();

        return view('owner.laporan.omset', compact('totalPendapatan', 'omsetPerMinuman'));
    }
}
