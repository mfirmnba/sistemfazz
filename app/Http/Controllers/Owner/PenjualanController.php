<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\LaporanPenjualan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        // Total cup terjual
        $totalCupTerjual = LaporanPenjualan::where('status', 'terjual')->sum('jumlah');

        // Penjualan per driver hari ini
        $penjualanPerDriver = LaporanPenjualan::join('users', 'laporan_penjualans.user_id', '=', 'users.id')
            ->join('minumans', 'laporan_penjualans.minuman_id', '=', 'minumans.id')
            ->whereDate('laporan_penjualans.tanggal', $today)
            ->where('laporan_penjualans.status', 'terjual')
            ->select('users.name as driver', DB::raw('SUM(laporan_penjualans.jumlah) as total_cup'), DB::raw('SUM(laporan_penjualans.jumlah * minumans.harga) as pendapatan'))
            ->groupBy('users.name')
            ->orderByDesc('pendapatan')
            ->get();

        // Penjualan per minuman (all time)
        $penjualanMinuman = LaporanPenjualan::with('minuman')
            ->where('status', 'terjual')
            ->selectRaw('minuman_id, SUM(jumlah) as total_qty')
            ->groupBy('minuman_id')
            ->orderByDesc('total_qty')
            ->get();

        return view('owner.laporan.penjualan', compact('today', 'totalCupTerjual', 'penjualanPerDriver', 'penjualanMinuman'));
    }
}
