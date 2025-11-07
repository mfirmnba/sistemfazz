<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\LaporanPenjualan;
use App\Models\User;
use App\Models\Minuman;
use Carbon\Carbon;

class PenjualanController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $penjualanHariIni = LaporanPenjualan::with(['user', 'minuman'])
            ->whereDate('tanggal', $today)
            ->where('status', 'terjual')
            ->get();

        $totalCupTerjual = $penjualanHariIni->sum('jumlah');

        $penjualanPerDriver = LaporanPenjualan::join('users', 'laporan_penjualans.user_id', '=', 'users.id')
            ->join('minumans', 'laporan_penjualans.minuman_id', '=', 'minumans.id')
            ->where('laporan_penjualans.status', 'terjual')
            ->selectRaw('users.name as driver, SUM(laporan_penjualans.jumlah) as total_cup, SUM(laporan_penjualans.jumlah * minumans.harga) as pendapatan')
            ->groupBy('users.name')
            ->orderByDesc('pendapatan')
            ->get();

        return view('owner.laporan.penjualan', compact('penjualanHariIni', 'totalCupTerjual', 'penjualanPerDriver'));
    }
}
