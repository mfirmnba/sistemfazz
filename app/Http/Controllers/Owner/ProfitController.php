<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\LaporanPenjualan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProfitController extends Controller
{
    public function index(Request $request)
    {
        // Ambil tahun dari request, default tahun sekarang
        $selectedYear = $request->get('year', Carbon::now()->year);

        // Ambil semua tahun yang tersedia dari data penjualan
        $availableYears = LaporanPenjualan::selectRaw('YEAR(tanggal) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Hitung total profit untuk tahun terpilih
        $totalProfit = LaporanPenjualan::with('minuman')
            ->where('status', 'terjual')
            ->whereYear('tanggal', $selectedYear)
            ->get()
            ->sum(fn($item) => (($item->minuman->harga ?? 0) - ($item->minuman->hpp ?? 0)) * ($item->jumlah ?? 0));

        // Data profit per bulan untuk tahun terpilih
        $monthlyData = LaporanPenjualan::with('minuman')
            ->where('status', 'terjual')
            ->whereYear('tanggal', $selectedYear)
            ->get()
            ->groupBy(fn($i) => Carbon::parse($i->tanggal)->format('m'))
            ->map(fn($items) => $items->sum(fn($i) => (($i->minuman->harga ?? 0) - ($i->minuman->hpp ?? 0)) * ($i->jumlah ?? 0)));

        $bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $profitData = [];

        foreach (range(1, 12) as $b) {
            $key = str_pad($b, 2, '0', STR_PAD_LEFT);
            $profitData[] = $monthlyData[$key] ?? 0;
        }

        return view('owner.laporan.profit', compact(
            'selectedYear',
            'availableYears',
            'totalProfit',
            'bulanLabels',
            'profitData'
        ));
    }
}
