<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\LaporanPenjualan;
use Illuminate\Support\Facades\DB;

class ProfitController extends Controller
{
    public function index()
    {
        // Total profit all time
        $totalProfit = LaporanPenjualan::with('minuman')
            ->where('status', 'terjual')
            ->get()
            ->sum(fn($i) => (($i->minuman->harga ?? 0) - ($i->minuman->hpp ?? 0)) * ($i->jumlah ?? 0));

        // Profit per bulan
        $monthlyProfit = LaporanPenjualan::with('minuman')
            ->where('status', 'terjual')
            ->get()
            ->groupBy(fn($i) => \Carbon\Carbon::parse($i->tanggal)->format('m'))
            ->map(fn($items) => $items->sum(fn($i) => (($i->minuman->harga ?? 0) - ($i->minuman->hpp ?? 0)) * ($i->jumlah ?? 0)));

        $bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $profitData = [];
        foreach (range(1, 12) as $b) {
            $key = str_pad($b, 2, '0', STR_PAD_LEFT);
            $profitData[] = $monthlyProfit[$key] ?? 0;
        }

        return view('owner.laporan.profit', compact('totalProfit', 'bulanLabels', 'profitData'));
    }
}
