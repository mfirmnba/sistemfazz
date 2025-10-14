<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Minuman;
use App\Models\Stock;
use App\Models\User;
use App\Models\LaporanPenjualan; // Model LaporanPenjualan

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil semua minuman
        $minumans = Minuman::orderBy('nama')->get();

        // Ambil semua stock bahan
        $stocks = Stock::orderBy('nama_bahan')->get();

        // Ambil data penjualan per hari, termasuk keuntungan
        $penjualanHarian = LaporanPenjualan::selectRaw('
                DATE(tanggal) as tanggal,
                SUM(jumlah) as jumlah_terjual,
                SUM(jumlah * minumans.harga) as keuntungan
            ')
            ->join('minumans', 'laporan_penjualans.minuman_id', '=', 'minumans.id')
            ->where('laporan_penjualans.status', 'terjual') // Menghitung hanya yang terjual
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        return view('admin.dashboard', [
            'minumans'         => $minumans,
            'minumansCount'    => $minumans->count(),
            'totalStokHariIni' => $minumans->sum('stok_hari_ini'),
            'totalStokBesok'   => $minumans->sum('stok_besok'),
            'usersCount'       => User::count(),
            'stocksCount'      => $stocks->count(),
            'stocks'           => $stocks,
            'penjualanHarian'  => $penjualanHarian, // Kirim data penjualan ke view
        ]);
    }

        public function kirimStockKeWA()
    {
        $stocks = Stock::orderBy('nama_bahan')->get();

        // Buat pesan
        $message = "📦 *Laporan Stock Hari Ini*\n\n";
        foreach ($stocks as $stock) {
            $message .= "{$stock->nama_bahan}: {$stock->jumlah} {$stock->satuan}\n";
        }

        // Nomor WA tujuan (contoh +6281234567890)
        $phoneNumber = '6289504528079';

        // Encode URL
        $url = 'https://wa.me/' . $phoneNumber . '?text=' . urlencode($message);

        // Redirect ke WA Web
        return redirect($url);
    }
}
