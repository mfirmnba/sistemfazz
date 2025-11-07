<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\LaporanPenjualan;

class ProfitController extends Controller
{
    public function index()
    {
        // ==============================
        // 🔹 Hitung Total Profit Keseluruhan
        // ==============================
        $totalProfit = LaporanPenjualan::with('minuman')
            ->where('status', 'terjual')
            ->get()
            ->sum(fn($item) => (($item->minuman->harga ?? 0) - ($item->minuman->hpp ?? 0)) * ($item->jumlah ?? 0));

        // ==============================
        // 🔹 Siapkan Data Grafik Bulanan
        // ==============================
        $monthlyData = LaporanPenjualan::with('minuman')
            ->where('status', 'terjual')
            ->get()
            ->groupBy(fn($i) => \Carbon\Carbon::parse($i->tanggal)->format('m'))
            ->map(function ($items) {
                return $items->sum(fn($i) => (($i->minuman->harga ?? 0) - ($i->minuman->hpp ?? 0)) * ($i->jumlah ?? 0));
            });

        $bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $profitData = [];

        foreach (range(1, 12) as $b) {
            $key = str_pad($b, 2, '0', STR_PAD_LEFT);
            $profitData[] = $monthlyData[$key] ?? 0;
        }

        // ==============================
        // 🔹 Return ke View
        // ==============================
        return view('owner.laporan.profit', compact('totalProfit', 'bulanLabels', 'profitData'));
    }
}
