<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\LaporanPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        // Query laporan hari ini
        $query = LaporanPenjualan::with('minuman')->whereDate('tanggal', $today);

        // Jika ada kolom user_id, filter sesuai driver yang login
        if (Schema::hasColumn('laporan_penjualans', 'user_id')) {
            $query->where('user_id', auth()->id());
        }

        $laporanToday = $query->get();

        // Hitung total pendapatan hanya dari yang "terjual"
        $totalToday = $laporanToday->where('status', 'terjual')->sum(function ($item) {
            $harga = $item->minuman->harga ?? 0;
            return ($item->jumlah ?? 0) * $harga;
        });

        // Hitung jumlah laporan hari ini
        $countToday = $laporanToday->count();

        // Hitung total minuman terjual (cup)
        $totalCupTerjual = $laporanToday->where('status', 'terjual')->sum('jumlah');

        // Hitung total expired dan tumpah
        $totalExpired = $laporanToday->where('status', 'expired')->sum('jumlah');
        $totalTumpah  = $laporanToday->where('status', 'tumpah')->sum('jumlah');

        // Ambil laporan terakhir driver
        $lastReport = LaporanPenjualan::with('minuman')
            ->when(Schema::hasColumn('laporan_penjualans', 'user_id'), function ($q) {
                return $q->where('user_id', auth()->id());
            })
            ->latest('created_at')
            ->first();

        return view('driver.dashboard', compact(
            'laporanToday',
            'totalToday',
            'countToday',
            'totalCupTerjual',
            'totalExpired',
            'totalTumpah',
            'lastReport',
            'today'
        ));
    }
}
