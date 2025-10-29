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
use Carbon\CarbonPeriod;
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
        // 🔹 Grafik Bulanan Penjualan vs Profit
        // =============================================================
        $monthlyData = LaporanPenjualan::with('minuman')
            ->where('status', 'terjual')
            ->get()
            ->groupBy(fn($i) => \Carbon\Carbon::parse($i->tanggal)->format('m'))
            ->map(function ($items) {
                $penjualan = $items->sum(fn($i) => ($i->jumlah ?? 0) * ($i->minuman->harga ?? 0));
                $profit = $items->sum(fn($i) => (($i->minuman->harga ?? 0) - ($i->minuman->hpp ?? 0)) * ($i->jumlah ?? 0));
                return ['penjualan' => $penjualan, 'profit' => $profit];
            });

        $bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $penjualanData = [];
        $profitData = [];
        foreach (range(1, 12) as $b) {
            $key = str_pad($b, 2, '0', STR_PAD_LEFT);
            $penjualanData[] = $monthlyData[$key]['penjualan'] ?? 0;
            $profitData[] = $monthlyData[$key]['profit'] ?? 0;
        }
        $totalKeuntunganBulanan = $profitData;

        // =============================================================
        // 🔹 Penjualan Per User
        // =============================================================
        $penjualanPerUserAllTime = LaporanPenjualan::join('minumans', 'laporan_penjualans.minuman_id', '=', 'minumans.id')
            ->where('laporan_penjualans.status', 'terjual')
            ->selectRaw('laporan_penjualans.user_id, SUM(laporan_penjualans.jumlah) as total_cup, SUM(laporan_penjualans.jumlah * minumans.harga) as pendapatan')
            ->groupBy('laporan_penjualans.user_id')
            ->get();

        $penjualanPerUserToday = LaporanPenjualan::join('minumans', 'laporan_penjualans.minuman_id', '=', 'minumans.id')
            ->whereDate('laporan_penjualans.tanggal', $today)
            ->where('laporan_penjualans.status', 'terjual')
            ->selectRaw('laporan_penjualans.user_id, SUM(laporan_penjualans.jumlah) as total_cup, SUM(laporan_penjualans.jumlah * minumans.harga) as pendapatan')
            ->groupBy('laporan_penjualans.user_id')
            ->get();

        // =============================================================
        // 🔹 Grafik Pendapatan Harian Per Driver (Gabungan History)
        // =============================================================
        $pendapatanDriverHarian = [];
        $dataAktif = LaporanPenjualan::join('minumans', 'laporan_penjualans.minuman_id', '=', 'minumans.id')
            ->select('laporan_penjualans.user_id', DB::raw('DATE(laporan_penjualans.tanggal) as tanggal'), DB::raw('SUM(laporan_penjualans.jumlah * minumans.harga) as total_pendapatan'))
            ->groupBy('laporan_penjualans.user_id', 'tanggal')
            ->get();

        $dataHistory = LaporanPenjualanHistory::join('minumans', 'laporan_penjualan_histories.minuman_id', '=', 'minumans.id')
            ->select('laporan_penjualan_histories.user_id', DB::raw('DATE(laporan_penjualan_histories.tanggal) as tanggal'), DB::raw('SUM(laporan_penjualan_histories.jumlah * minumans.harga) as total_pendapatan'))
            ->groupBy('laporan_penjualan_histories.user_id', 'tanggal')
            ->get();

        $gabung = $dataAktif->concat($dataHistory);
        foreach ($gabung as $row) {
            $driver = User::find($row->user_id);
            if (!$driver) continue;
            $nama = $driver->name ?? 'Tanpa Nama';
            $pendapatanDriverHarian[$nama][] = [
                'tanggal' => $row->tanggal,
                'total_pendapatan' => (float) $row->total_pendapatan,
            ];
        }

        // =============================================================
        // 🔹 Grafik Bulanan & Tahunan Per Driver
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
        // 🔹 Data Lain-lain
        // =============================================================
        $laporanPenjualanGrouped = $laporanPenjualanToday->where('status', 'terjual')
            ->groupBy(fn($i) => $i->minuman->nama ?? 'Tanpa Nama')
            ->map(fn($i) => $i->sum('jumlah'));

        $penjualanMinuman = LaporanPenjualan::with('minuman')
            ->where('status', 'terjual')
            ->selectRaw('minuman_id, SUM(jumlah) as total_qty')
            ->groupBy('minuman_id')
            ->orderByDesc('total_qty')
            ->get();

        $minumanTerjualPerDriverToday = LaporanPenjualan::with('user', 'minuman')
            ->whereDate('tanggal', $today)
            ->where('status', 'terjual')
            ->selectRaw('user_id, minuman_id, SUM(jumlah) as total_terjual')
            ->groupBy('user_id', 'minuman_id')
            ->get()
            ->groupBy('user_id');

        $pendapatanSemuaDriverHarian = LaporanPenjualan::join('users', 'laporan_penjualans.user_id', '=', 'users.id')
            ->join('minumans', 'laporan_penjualans.minuman_id', '=', 'minumans.id')
            ->where('users.role', 'driver')
            ->where('laporan_penjualans.status', 'terjual')
            ->selectRaw('DATE(laporan_penjualans.tanggal) as tanggal, SUM(laporan_penjualans.jumlah * minumans.harga) as total_pendapatan')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        $minumanTerjualPerHari = LaporanPenjualan::join('users', 'laporan_penjualans.user_id', '=', 'users.id')
            ->join('minumans', 'laporan_penjualans.minuman_id', '=', 'minumans.id')
            ->where('users.role', 'driver')
            ->where('laporan_penjualans.status', 'terjual')
            ->selectRaw('DATE(laporan_penjualans.tanggal) as tanggal, minuman_id, SUM(jumlah) as total_terjual')
            ->groupBy('tanggal', 'minuman_id')
            ->orderBy('tanggal')
            ->get()
            ->groupBy('tanggal');

        // =============================================================
        // 🔹 RETURN
        // =============================================================
        return view('owner.dashboard', compact(
            'minumans', 'stocks', 'adminUsers', 'driverUsers', 'produksiUsers',
            'laporanToday', 'laporanPenjualanToday', 'bahanTerpakaiHariIni',
            'totalPendapatan', 'totalPendapatanHariIni', 'totalKeuntunganSemua',
            'totalCupTerjual', 'totalOrdersAllTime', 'totalExpired', 'totalTumpah',
            'totalStock', 'laporanPenjualanGrouped', 'penjualanPerUserToday',
            'penjualanPerUserAllTime', 'bulanLabels', 'penjualanData', 'profitData',
            'totalKeuntunganBulanan', 'penjualanMinuman', 'pendapatanDriverHarian',
            'minumanTerjualPerDriverToday', 'pendapatanSemuaDriverHarian',
            'minumanTerjualPerHari', 'grafikBulananDriver', 'grafikTahunanDriver',
            'stokMingguan'
        ));
    }
}
