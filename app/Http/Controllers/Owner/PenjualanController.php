<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\LaporanPenjualan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        // Tahun dipilih, default tahun ini
        $selectedYear = $request->get('year', Carbon::now()->year);

        // Ambil daftar tahun tersedia
        $availableYears = LaporanPenjualan::selectRaw('YEAR(tanggal) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Total minuman terjual tahun terpilih
        $totalTerjual = LaporanPenjualan::where('status', 'terjual')
            ->whereYear('tanggal', $selectedYear)
            ->sum('jumlah');

        // Data per bulan
        $monthlyData = LaporanPenjualan::where('status', 'terjual')
            ->whereYear('tanggal', $selectedYear)
            ->get()
            ->groupBy(fn($i) => Carbon::parse($i->tanggal)->format('m'))
            ->map(fn($items) => $items->sum('jumlah'));

        $bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $penjualanData = [];

        foreach (range(1, 12) as $b) {
            $key = str_pad($b, 2, '0', STR_PAD_LEFT);
            $penjualanData[] = $monthlyData[$key] ?? 0;
        }

        return view('owner.laporan.penjualan', compact(
            'selectedYear',
            'availableYears',
            'totalTerjual',
            'bulanLabels',
            'penjualanData'
        ));
    }
}
