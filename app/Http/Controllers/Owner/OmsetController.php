<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\LaporanPenjualan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OmsetController extends Controller
{
    public function index(Request $request)
    {
        // Tahun terpilih (default tahun ini)
        $selectedYear = $request->get('year', Carbon::now()->year);

        // Daftar tahun yang tersedia di database
        $availableYears = LaporanPenjualan::selectRaw('YEAR(tanggal) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Hitung total omset (harga jual × jumlah) untuk tahun terpilih
        $totalOmset = LaporanPenjualan::with('minuman')
            ->where('status', 'terjual')
            ->whereYear('tanggal', $selectedYear)
            ->get()
            ->sum(fn($item) => ($item->minuman->harga ?? 0) * ($item->jumlah ?? 0));

        // Data omset per bulan
        $monthlyData = LaporanPenjualan::with('minuman')
            ->where('status', 'terjual')
            ->whereYear('tanggal', $selectedYear)
            ->get()
            ->groupBy(fn($i) => Carbon::parse($i->tanggal)->format('m'))
            ->map(fn($items) => $items->sum(fn($i) => ($i->minuman->harga ?? 0) * ($i->jumlah ?? 0)));

        $bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $omsetData = [];

        foreach (range(1, 12) as $b) {
            $key = str_pad($b, 2, '0', STR_PAD_LEFT);
            $omsetData[] = $monthlyData[$key] ?? 0;
        }

        return view('owner.laporan.omset', compact(
            'selectedYear',
            'availableYears',
            'totalOmset',
            'bulanLabels',
            'omsetData'
        ));
    }
}
