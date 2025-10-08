<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\LaporanProduksi;
use App\Models\Minuman;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        // Semua stock yang dibuat admin
        $stocks = Stock::all();

        // Hitung jumlah awal dan sisa stok hari ini
        $stocksWithAwal = $stocks->map(function ($stock) use ($today) {
            // Total semua penggunaan sebelumnya
            $totalDipakai = LaporanProduksi::where('stock_id', $stock->id)->sum('jumlah_digunakan');

            // Total dipakai hari ini
            $dipakaiHariIni = LaporanProduksi::where('stock_id', $stock->id)
                                ->whereDate('tanggal', $today)
                                ->sum('jumlah_digunakan');

            $stock->jumlah_awal = $stock->jumlah + $totalDipakai;       // jumlah awal
            $stock->jumlah_sekarang = $stock->jumlah; // Ini adalah kondisi terkini

            return $stock;
        });

        $totalStock = $stocksWithAwal->sum('jumlah_sekarang');

        // Laporan produksi hari ini
        $laporanToday = LaporanProduksi::with(['stock', 'user'])
            ->whereDate('tanggal', $today)
            ->orderBy('created_at', 'desc')
            ->get();

        $bahanTerpakaiHariIni = $laporanToday->sum('jumlah_digunakan');

        // Laporan terakhir (global)
        $lastLaporan = LaporanProduksi::with('stock')
            ->latest()
            ->first();

        // Data minuman produksi
        $minumans = Minuman::latest()->get();

        return view('produksi.dashboard', compact(
            'stocksWithAwal',
            'totalStock',
            'laporanToday',
            'bahanTerpakaiHariIni',
            'lastLaporan',
            'minumans'
        ));
    }

     // Fungsi untuk reset stok minuman
    public function resetStok($minumanId)
    {
        $minuman = Minuman::findOrFail($minumanId);

        // Reset stok hari ini dan tambahkan stok besok ke hari ini
        $minuman->stok_hari_ini += $minuman->stok_besok;

        // Set stok besok menjadi 0
        $minuman->stok_besok = 0;

        // Simpan perubahan
        $minuman->save();

        // Redirect kembali ke halaman dengan pesan sukses
        return redirect()->route('produksi.dashboard')->with('success', 'Stok berhasil direset.');
    }

        // Fungsi untuk reset semua stok minuman
    public function resetAllStok()
    {
        // Ambil semua minuman
        $minumans = Minuman::all();

        foreach ($minumans as $minuman) {
            // Tambahkan stok besok ke stok hari ini
            $minuman->stok_hari_ini += $minuman->stok_besok;

            // Set stok besok menjadi 0
            $minuman->stok_besok = 0;

            // Simpan perubahan
            $minuman->save();
        }

        // Redirect kembali ke halaman dengan pesan sukses
        return redirect()->route('produksi.dashboard')->with('success', 'Semua stok berhasil direset.');
    }
}
