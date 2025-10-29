<?php

namespace App\Http\Controllers\Owner;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Minuman;
use App\Models\LaporanProduksiHistory;
use App\Models\Stock;
use App\Models\User;
use App\Models\LaporanPenjualan;
use App\Models\LaporanProduksi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\LaporanPenjualanHistory; 

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        // =============================================================
        // 🔹 Data Dasar
        // =============================================================
        $minumans = Minuman::orderBy('nama')->get();
        $minumans->map(function ($m) {
            $m->keuntungan_per_cup = $m->harga - ($m->hpp ?? 0);
            $m->margin_persen = $m->hpp > 0 ? round(($m->keuntungan_per_cup / $m->hpp) * 100, 2) : 0;
            return $m;
        });

        $totalKeuntunganSemua = LaporanPenjualan::with('minuman')
            ->where('status', 'terjual')
            ->get()
            ->sum(fn($item) => (($item->minuman->harga ?? 0) - ($item->minuman->hpp ?? 0)) * ($item->jumlah ?? 0));

        $stocks = Stock::withSum(['laporanProduksi as terpakai_hari_ini' => function($q) use ($today) {
            $q->whereDate('tanggal', $today);
        }], 'jumlah_digunakan')
        ->withSum('laporanProduksi as terpakai_total', 'jumlah_digunakan')
        ->orderBy('nama_bahan')
        ->get();

        $adminUsers = User::where('role', 'admin')->get();
        $driverUsers = User::where('role', 'driver')->get();
        $produksiUsers = User::where('role', 'produksi')->get();

        // =============================================================
        // 🔹 Laporan Hari Ini
        // =============================================================
        $laporanToday = LaporanProduksi::with(['stock', 'user'])
            ->whereDate('tanggal', $today)
            ->get();

        $bahanTerpakaiHariIni = $laporanToday->sum('jumlah_digunakan');

        $laporanPenjualanToday = LaporanPenjualan::with('minuman')
            ->whereDate('tanggal', $today)
            ->get();

        $totalPendapatanHariIni = $laporanPenjualanToday->where('status', 'terjual')
            ->sum(fn($item) => ($item->jumlah ?? 0) * ($item->minuman->harga ?? 0));

        $totalPendapatan = LaporanPenjualan::with('minuman')
            ->where('status', 'terjual')
            ->get()
            ->sum(fn($item) => ($item->jumlah ?? 0) * ($item->minuman->harga ?? 0));

        $totalCupTerjual = $laporanPenjualanToday->where('status', 'terjual')->sum('jumlah');
        $totalExpired = $laporanPenjualanToday->where('status', 'expired')->sum('jumlah');
        $totalTumpah = $laporanPenjualanToday->where('status', 'tumpah')->sum('jumlah');
        $totalStock = $stocks->sum('jumlah');
        $totalOrdersAllTime = LaporanPenjualan::where('status', 'terjual')->sum('jumlah');

        // =============================================================
        // 🔹 Grafik Bulanan & Tahunan per Driver
        // =============================================================
        $grafikBulananDriver = LaporanPenjualan::join('minumans', 'laporan_penjualans.minuman_id', '=', 'minumans.id')
            ->join('users', 'laporan_penjualans.user_id', '=', 'users.id')
            ->where('users.role', 'driver')
            ->where('laporan_penjualans.status', 'terjual')
            ->selectRaw('users.name as nama_driver, MONTH(laporan_penjualans.tanggal) as bulan, 
                        SUM(laporan_penjualans.jumlah) as total_cup, 
                        SUM(laporan_penjualans.jumlah * minumans.harga) as total_pendapatan')
            ->groupBy('users.name', 'bulan')
            ->orderBy('bulan')
            ->get()
            ->groupBy('nama_driver');

        $grafikTahunanDriver = LaporanPenjualan::join('minumans', 'laporan_penjualans.minuman_id', '=', 'minumans.id')
            ->join('users', 'laporan_penjualans.user_id', '=', 'users.id')
            ->where('users.role', 'driver')
            ->where('laporan_penjualans.status', 'terjual')
            ->selectRaw('users.name as nama_driver, YEAR(laporan_penjualans.tanggal) as tahun, 
                        SUM(laporan_penjualans.jumlah) as total_cup, 
                        SUM(laporan_penjualans.jumlah * minumans.harga) as total_pendapatan')
            ->groupBy('users.name', 'tahun')
            ->orderBy('tahun')
            ->get()
            ->groupBy('nama_driver');

        // =============================================================
        // 🔹 Grafik Stok Mingguan (biar gak error)
        // =============================================================
        $stokMingguan = LaporanProduksi::selectRaw('DATE(tanggal) as tanggal, SUM(jumlah_digunakan) as total_stok')
            ->whereBetween('tanggal', [now()->subDays(6), now()])
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // =============================================================
        // 🔹 RETURN VIEW
        // =============================================================
        return view('owner.dashboard', compact(
            'minumans', 'stocks', 'adminUsers', 'driverUsers', 'produksiUsers',
            'laporanToday', 'laporanPenjualanToday', 'bahanTerpakaiHariIni',
            'totalPendapatan', 'totalPendapatanHariIni', 'totalKeuntunganSemua',
            'totalCupTerjual', 'totalOrdersAllTime', 'totalExpired', 'totalTumpah',
            'totalStock', 'grafikBulananDriver', 'grafikTahunanDriver', 'stokMingguan'
        ));
    }
}
