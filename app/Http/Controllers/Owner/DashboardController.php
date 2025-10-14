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

        // Ambil semua minuman
        $minumans = Minuman::orderBy('nama')->get();
        
        // Hitung keuntungan & margin per minuman
        $minumans->map(function ($m) {
            $m->keuntungan_per_cup = $m->harga - ($m->hpp ?? 0);
            $m->margin_persen = $m->hpp > 0 ? round(($m->keuntungan_per_cup / $m->hpp) * 100, 2) : 0;
            return $m;
        });

        // 🔹 Hitung total keuntungan keseluruhan (semua minuman terjual)
        $totalKeuntunganSemua = LaporanPenjualan::with('minuman')
            ->where('status', 'terjual')
            ->get()
            ->sum(function ($item) {
                $hpp = $item->minuman->hpp ?? 0;
                $harga = $item->minuman->harga ?? 0;
                return ($harga - $hpp) * ($item->jumlah ?? 0);
            });

        // Ambil semua stock + total terpakai
        $stocks = Stock::withSum(['laporanProduksi as terpakai_hari_ini' => function($query) use ($today) {
            $query->whereDate('tanggal', $today);
        }], 'jumlah_digunakan')
        ->withSum('laporanProduksi as terpakai_total', 'jumlah_digunakan')
        ->orderBy('nama_bahan')
        ->get();

        // User berdasarkan role
        $adminUsers = User::where('role', 'admin')->get();
        $driverUsers = User::where('role', 'driver')->get();
        $produksiUsers = User::where('role', 'produksi')->get();

        // Laporan produksi hari ini
        $laporanToday = LaporanProduksi::with(['stock', 'user'])
            ->whereDate('tanggal', $today)
            ->get();

        $bahanTerpakaiHariIni = $laporanToday->sum('jumlah_digunakan');

        // Laporan penjualan hari ini
        $laporanPenjualanToday = LaporanPenjualan::with('minuman')
            ->whereDate('tanggal', $today)
            ->get();

        // 🔹 Total pendapatan hari ini
        $totalPendapatanHariIni = $laporanPenjualanToday->where('status', 'terjual')->sum(function ($item) {
            return ($item->jumlah ?? 0) * ($item->minuman->harga ?? 0);
        });

        // 🔹 Total pendapatan semua waktu
        $totalPendapatan = LaporanPenjualan::with('minuman')
            ->where('status', 'terjual')
            ->get()
            ->sum(function ($item) {
                return ($item->jumlah ?? 0) * ($item->minuman->harga ?? 0);
            });

        // Cup terjual, expired, tumpah (hari ini)
        $totalCupTerjual = $laporanPenjualanToday->where('status', 'terjual')->sum('jumlah');
        $totalExpired = $laporanPenjualanToday->where('status', 'expired')->sum('jumlah');
        $totalTumpah = $laporanPenjualanToday->where('status', 'tumpah')->sum('jumlah');

        // Total stok
        $totalStock = $stocks->sum('jumlah');

        // Total cup terjual keseluruhan
        $totalOrdersAllTime = LaporanPenjualan::where('status', 'terjual')->sum('jumlah');

        // Penjualan per minuman (hari ini)
        $laporanPenjualanGrouped = $laporanPenjualanToday
            ->where('status', 'terjual')
            ->groupBy(fn($item) => $item->minuman->nama ?? 'Tanpa Nama')
            ->map(fn($items) => $items->sum('jumlah'));

        // Penjualan per user total
        $penjualanPerUserAllTime = LaporanPenjualan::with('user')
            ->join('minumans', 'laporan_penjualans.minuman_id', '=', 'minumans.id')
            ->where('laporan_penjualans.status', 'terjual')
            ->selectRaw('laporan_penjualans.user_id, SUM(laporan_penjualans.jumlah) as total_cup, SUM(laporan_penjualans.jumlah * minumans.harga) as pendapatan')
            ->groupBy('laporan_penjualans.user_id')
            ->get();

        // Penjualan per user hari ini
        $penjualanPerUserToday = LaporanPenjualan::with('user')
            ->join('minumans', 'laporan_penjualans.minuman_id', '=', 'minumans.id')
            ->whereDate('laporan_penjualans.tanggal', $today)
            ->where('laporan_penjualans.status', 'terjual')
            ->selectRaw('laporan_penjualans.user_id, SUM(laporan_penjualans.jumlah) as total_cup, SUM(laporan_penjualans.jumlah * minumans.harga) as pendapatan')
            ->groupBy('laporan_penjualans.user_id')
            ->get();

        // 🔹 Data bulanan untuk grafik Penjualan vs Profit
        $monthlyData = LaporanPenjualan::with('minuman')
            ->where('status', 'terjual')
            ->get()
            ->groupBy(fn($item) => \Carbon\Carbon::parse($item->tanggal)->format('m'))
            ->map(function ($items) {
                $totalPenjualan = $items->sum(function ($i) {
                    return ($i->jumlah ?? 0) * ($i->minuman->harga ?? 0);
                });
                $totalProfit = $items->sum(function ($i) {
                    $hpp = $i->minuman->hpp ?? 0;
                    $harga = $i->minuman->harga ?? 0;
                    return ($harga - $hpp) * ($i->jumlah ?? 0);
                });
                return [
                    'penjualan' => $totalPenjualan,
                    'profit' => $totalProfit,
                ];
            });

        // Pastikan semua bulan (1–12) ada
        $bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        $penjualanData = [];
        $profitData = [];
        foreach (range(1, 12) as $bulan) {
            $key = str_pad($bulan, 2, '0', STR_PAD_LEFT);
            $penjualanData[] = $monthlyData[$key]['penjualan'] ?? 0;
            $profitData[] = $monthlyData[$key]['profit'] ?? 0;
        }

        // ✅ tambahan untuk Blade biar nggak error
        $totalKeuntunganBulanan = $profitData;

        // 🔹 Penjualan semua minuman (all time, tidak di-limit)
        $penjualanMinuman = LaporanPenjualan::with('minuman')
            ->where('status', 'terjual')
            ->selectRaw('minuman_id, SUM(jumlah) as total_qty')
            ->groupBy('minuman_id')
            ->orderByDesc('total_qty')
            ->get();

            // =============================================================
        // 🔹 Pendapatan Harian Per Driver (Grafik per Hari)
        // =============================================================
        $pendapatanDriverHarian = LaporanPenjualan::join('minumans', 'laporan_penjualans.minuman_id', '=', 'minumans.id')
            ->join('users', 'laporan_penjualans.user_id', '=', 'users.id')
            ->where('users.role', 'driver')
            ->where('laporan_penjualans.status', 'terjual')
            ->selectRaw('DATE(laporan_penjualans.tanggal) as tanggal, users.id as user_id, users.name as nama_driver, SUM(laporan_penjualans.jumlah * minumans.harga) as total_pendapatan')
            ->groupBy('tanggal', 'users.id', 'users.name')
            ->orderBy('tanggal')
            ->get()
            ->groupBy('user_id');


        // =============================================================
        // 🔹 Daftar Minuman Terjual per Driver (Hari Ini)
        // =============================================================
        $minumanTerjualPerDriverToday = LaporanPenjualan::with('user', 'minuman')
            ->whereDate('tanggal', $today)
            ->where('status', 'terjual')
            ->selectRaw('user_id, minuman_id, SUM(jumlah) as total_terjual')
            ->groupBy('user_id', 'minuman_id')
            ->get()
            ->groupBy('user_id');

        // =============================================================
        // 🔹 Pendapatan Harian Semua Driver (Total Harian, untuk Grafik Owner)
        // =============================================================
        $pendapatanSemuaDriverHarian = LaporanPenjualan::with('minuman')
            ->join('users', 'laporan_penjualans.user_id', '=', 'users.id')
            ->where('users.role', 'driver')
            ->where('laporan_penjualans.status', 'terjual')
            ->selectRaw('DATE(laporan_penjualans.tanggal) as tanggal, SUM(laporan_penjualans.jumlah * minumans.harga) as total_pendapatan')
            ->join('minumans', 'laporan_penjualans.minuman_id', '=', 'minumans.id')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // =============================================================
        // 🔹 Total Minuman Terjual Per Hari (Semua Driver)
        // =============================================================
        $minumanTerjualPerHari = LaporanPenjualan::with('minuman')
            ->join('users', 'laporan_penjualans.user_id', '=', 'users.id')
            ->where('users.role', 'driver')
            ->where('laporan_penjualans.status', 'terjual')
            ->selectRaw('DATE(laporan_penjualans.tanggal) as tanggal, minuman_id, SUM(jumlah) as total_terjual')
            ->groupBy('tanggal', 'minuman_id')
            ->orderBy('tanggal')
            ->get()
            ->groupBy('tanggal');

        // ======================
        // Pendapatan harian per driver
        // ======================
        $pendapatanDriverHarian = [];

        // Gabungkan dari tabel aktif + history
        $dataAktif = LaporanPenjualan::select(
                'user_id',
                DB::raw('DATE(tanggal) as tanggal'),
                DB::raw('SUM(total_harga) as total_pendapatan')
            )
            ->groupBy('user_id', 'tanggal')
            ->get();

        $dataHistory = LaporanPenjualanHistory::select(
                'user_id',
                DB::raw('DATE(tanggal) as tanggal'),
                DB::raw('SUM(total_harga) as total_pendapatan')
            )
            ->groupBy('user_id', 'tanggal')
            ->get();

        $gabung = $dataAktif->concat($dataHistory);

        // Kelompokkan per driver
        foreach ($gabung as $row) {
            if (!$row->driver) continue;
            $nama = $row->driver->nama ?? 'Tanpa Nama';
            $pendapatanDriverHarian[$nama][] = [
                'tanggal' => $row->tanggal,
                'total_pendapatan' => (float) $row->total_pendapatan,
            ];
        }

        return view('owner.dashboard', compact(
            'minumans',
            'stocks',
            'adminUsers',
            'driverUsers',
            'produksiUsers',
            'laporanToday',
            'laporanPenjualanToday',
            'bahanTerpakaiHariIni',
            'totalPendapatan',
            'totalPendapatanHariIni',
            'totalKeuntunganSemua',
            'totalCupTerjual',
            'totalOrdersAllTime',
            'totalExpired',
            'totalTumpah',
            'totalStock',
            'laporanPenjualanGrouped',
            'penjualanPerUserToday',
            'penjualanPerUserAllTime',
            'bulanLabels',
            'penjualanData',
            'profitData',
            'totalKeuntunganBulanan',
            'penjualanMinuman',
            'pendapatanDriverHarian', 
            'minumanTerjualPerDriverToday',
            'pendapatanSemuaDriverHarian',
            'minumanTerjualPerHari'
        ));

        }
    }
