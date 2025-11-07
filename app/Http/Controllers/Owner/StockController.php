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
        // Pilihan tahun & bulan
        $selectedYear = $request->get('year', Carbon::now()->year);
        $selectedMonth = $request->get('month');

        // Daftar tahun
        $availableYears = LaporanProduksi::selectRaw('YEAR(tanggal) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Label bulan
        $bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        // Data stok bulanan
        $monthlyStock = LaporanProduksi::selectRaw('MONTH(tanggal) as month, SUM(jumlah_digunakan) as total_digunakan')
            ->whereYear('tanggal', $selectedYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(fn($item) => [intval($item->month) => $item->total_digunakan]);

        $stockData = [];
        foreach (range(1, 12) as $b) {
            $stockData[] = $monthlyStock[$b] ?? 0;
        }

        // Jika pilih bulan, filter sesuai bulan itu
        $stockQuery = LaporanProduksi::whereYear('tanggal', $selectedYear);
        if ($selectedMonth) {
            $stockQuery->whereMonth('tanggal', $selectedMonth);
        }

        // Daftar stok digunakan per bahan
        $stockUsedList = $stockQuery
            ->select('stock_id', DB::raw('SUM(jumlah_digunakan) as total_digunakan'))
            ->groupBy('stock_id')
            ->with('stock:id,nama_bahan,satuan')
            ->get();

        $totalStockUsed = $stockUsedList->sum('total_digunakan');

        return view('owner.laporan.stock', compact(
            'selectedYear',
            'selectedMonth',
            'availableYears',
            'bulanLabels',
            'stockData',
            'totalStockUsed',
            'stockUsedList'
        ));
    }
}
