<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Stock;
use App\Models\LaporanProduksi;
use Carbon\Carbon;

class StockController extends Controller
{
    public function index(Request $request)
    {
        // Tahun dipilih (default: tahun ini)
        $selectedYear = $request->get('year', Carbon::now()->year);

        // Ambil daftar tahun yang tersedia dari data produksi
        $availableYears = LaporanProduksi::selectRaw('YEAR(tanggal) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Ambil data stok bulanan berdasarkan tahun yang dipilih
        $monthlyStock = LaporanProduksi::selectRaw('MONTH(tanggal) as month, SUM(jumlah_digunakan) as total_digunakan')
            ->whereYear('tanggal', $selectedYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(function ($item) {
                return [intval($item->month) => $item->total_digunakan];
            });

        // Siapkan label bulan (Jan - Des)
        $bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $stockData = [];
        foreach (range(1, 12) as $b) {
            $stockData[] = $monthlyStock[$b] ?? 0;
        }

        // Total pemakaian stok per tahun
        $totalStockUsed = array_sum($stockData);

        // Ambil data stok terkini per bahan
        $stocks = Stock::orderBy('nama_bahan')->get();

        return view('owner.laporan.stock', compact(
            'selectedYear',
            'availableYears',
            'bulanLabels',
            'stockData',
            'totalStockUsed',
            'stocks'
        ));
    }
}
