<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\StockMasuk;
use App\Models\LaporanPenjualan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StockController extends Controller
{
    public function index(Request $request)
    {
        // Tahun dipilih, default tahun ini
        $selectedYear = $request->get('year', Carbon::now()->year);

        // Ambil daftar tahun yang tersedia
        $availableYears = StockMasuk::selectRaw('YEAR(tanggal) as year')
            ->distinct()
            ->union(
                LaporanPenjualan::selectRaw('YEAR(tanggal) as year')->distinct()
            )
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Total stok tersedia = stok masuk - stok keluar (penjualan)
        $totalMasuk = StockMasuk::whereYear('tanggal', $selectedYear)->sum('kuantitas');
        $totalKeluar = LaporanPenjualan::where('status', 'terjual')
            ->whereYear('tanggal', $selectedYear)
            ->sum('jumlah');
        $totalStock = $totalMasuk - $totalKeluar;

        // Data per bulan
        $stockMasukBulanan = StockMasuk::whereYear('tanggal', $selectedYear)->get()
            ->groupBy(fn($i) => Carbon::parse($i->tanggal)->format('m'))
            ->map(fn($items) => $items->sum('kuantitas'));

        $stockKeluarBulanan = LaporanPenjualan::where('status', 'terjual')
            ->whereYear('tanggal', $selectedYear)->get()
            ->groupBy(fn($i) => Carbon::parse($i->tanggal)->format('m'))
            ->map(fn($items) => $items->sum('jumlah'));

        $bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $masukData = [];
        $keluarData = [];

        foreach (range(1, 12) as $b) {
            $key = str_pad($b, 2, '0', STR_PAD_LEFT);
            $masukData[] = $stockMasukBulanan[$key] ?? 0;
            $keluarData[] = $stockKeluarBulanan[$key] ?? 0;
        }

        return view('owner.laporan.stock', compact(
            'selectedYear',
            'availableYears',
            'totalStock',
            'bulanLabels',
            'masukData',
            'keluarData'
        ));
    }
}
