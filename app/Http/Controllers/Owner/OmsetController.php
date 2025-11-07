<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\LaporanPenjualan;
use Illuminate\Support\Facades\DB;

class OmsetController extends Controller
{
    public function index()
    {
        // Total pendapatan (omset)
        $totalOmset = LaporanPenjualan::with('minuman')
            ->where('status', 'terjual')
            ->get()
            ->sum(fn($i) => ($i->jumlah ?? 0) * ($i->minuman->harga ?? 0));

        // Omset per bulan
        $monthlyOmset = LaporanPenjualan::with('minuman')
            ->where('status', 'terjual')
            ->get()
            ->groupBy(fn($i) => \Carbon\Carbon::parse($i->tanggal)->format('m'))
            ->map(fn($items) => $items->sum(fn($i) => ($i->jumlah ?? 0) * ($i->minuman->harga ?? 0)));

        $bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $omsetData = [];
        foreach (range(1, 12) as $b) {
            $key = str_pad($b, 2, '0', STR_PAD_LEFT);
            $omsetData[] = $monthlyOmset[$key] ?? 0;
        }

        return view('owner.laporan.omset', compact('totalOmset', 'bulanLabels', 'omsetData'));
    }
}
