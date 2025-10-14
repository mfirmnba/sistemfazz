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

        // =============================================================
        // 🔹 Pendapatan Harian Per Driver (Grafik per Hari - Tidak Ter-reset + Fallback 0)
        // =============================================================

        // Ambil semua driver aktif
        $drivers = User::where('role', 'driver')->get();

        // 🔹 Ambil tanggal paling awal & paling akhir dari semua data (aktif + history)
        $tanggalAwal = LaporanPenjualan::min('tanggal');
        $tanggalAwalHistory = LaporanPenjualanHistory::min('tanggal');
        $tanggalAwalFinal = min(array_filter([$tanggalAwal, $tanggalAwalHistory]));

        $tanggalAkhir = LaporanPenjualan::max('tanggal');
        $tanggalAkhirHistory = LaporanPenjualanHistory::max('tanggal');
        $tanggalAkhirFinal = max(array_filter([$tanggalAkhir, $tanggalAkhirHistory]));

        // 🔹 Jika belum ada data sama sekali, fallback ke hari ini
        if (!$tanggalAwalFinal || !$tanggalAkhirFinal) {
            $tanggalAwalFinal = now()->toDateString();
            $tanggalAkhirFinal = now()->toDateString();
        }

        // 🔹 Buat periode berdasarkan data nyata di database
        $periode = CarbonPeriod::create($tanggalAwalFinal, $tanggalAkhirFinal);

        // Ambil data dari tabel aktif
        $laporanAktif = LaporanPenjualan::with('user', 'minuman')
            ->join('minumans', 'laporan_penjualans.minuman_id', '=', 'minumans.id')
            ->join('users', 'laporan_penjualans.user_id', '=', 'users.id')
            ->where('laporan_penjualans.status', 'terjual')
            ->where('users.role', 'driver')
            ->selectRaw('DATE(laporan_penjualans.tanggal) as tanggal, users.id as user_id, users.name as nama_driver, SUM(laporan_penjualans.jumlah * minumans.harga) as total_pendapatan')
            ->groupBy('tanggal', 'users.id', 'users.name');

        // Ambil data dari tabel history
        $laporanHistory = LaporanPenjualanHistory::with('user', 'minuman')
            ->join('minumans', 'laporan_penjualan_histories.minuman_id', '=', 'minumans.id')
            ->join('users', 'laporan_penjualan_histories.user_id', '=', 'users.id')
            ->where('laporan_penjualan_histories.status', 'terjual')
            ->where('users.role', 'driver')
            ->selectRaw('DATE(laporan_penjualan_histories.tanggal) as tanggal, users.id as user_id, users.name as nama_driver, SUM(laporan_penjualan_histories.jumlah * minumans.harga) as total_pendapatan')
            ->groupBy('tanggal', 'users.id', 'users.name');

        // Gabungkan data aktif & history
        $laporanPendapatan = $laporanHistory
            ->unionAll($laporanAktif)
            ->get()
            ->groupBy(fn($item) => $item->user_id . '_' . $item->tanggal)
            ->map(function ($items) {
                $first = $items->first();
                return (object)[
                    'user_id' => $first->user_id,
                    'nama_driver' => $first->nama_driver,
                    'tanggal' => $first->tanggal,
                    'total_pendapatan' => $items->sum('total_pendapatan'),
                ];
            })
            ->values();

        // Kelompokkan per driver
        $pendapatanDriverHarian = [];

        foreach ($drivers as $driver) {
            foreach ($periode as $date) {
                $tanggal = $date->toDateString();

                // Cari apakah driver punya pendapatan pada tanggal ini
                $data = $laporanPendapatan->first(function ($item) use ($driver, $tanggal) {
                    return $item->user_id == $driver->id && $item->tanggal == $tanggal;
                });

                $pendapatanDriverHarian[$driver->name][] = [
                    'tanggal' => $tanggal,
                    'total_pendapatan' => $data ? $data->total_pendapatan : 0,
                ];
            }
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
