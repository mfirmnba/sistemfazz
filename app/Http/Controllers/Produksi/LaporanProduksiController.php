<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\LaporanProduksi;
use App\Models\Stock;
use Illuminate\Http\Request;
use App\Models\Minuman;

class LaporanProduksiController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $laporan = LaporanProduksi::whereDate('tanggal', $today)
                    ->with('stock')
                    ->latest()
                    ->get();

        return view('produksi.laporanproduksi.index', compact('laporan', 'today'));
    }

    public function create()
    {
        $stocks = Stock::orderBy('nama_bahan')->get();
        return view('produksi.laporanproduksi.create', compact('stocks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'stock_id'         => 'required|exists:stocks,id',
            'jumlah_digunakan' => 'required|integer|min:1',
        ]);

        $stock = Stock::findOrFail($request->stock_id);

        if ($stock->jumlah < $request->jumlah_digunakan) {
            return back()->with('error', "Stok {$stock->nama_bahan} tidak mencukupi.");
        }

        // Kurangi stok
        $stock->decrement('jumlah', $request->jumlah_digunakan);

        // Catat laporan
        LaporanProduksi::create([
            'user_id'          => auth()->id(),
            'stock_id'         => $request->stock_id,
            'jumlah_digunakan' => $request->jumlah_digunakan,
            'tanggal'          => now()->toDateString(),
        ]);

        return redirect()->route('produksi.laporanproduksi.index')
                         ->with('success', 'Laporan produksi berhasil dicatat.');
    }

    public function edit($id)
    {
        $laporan = LaporanProduksi::findOrFail($id);
        $stocks = Stock::orderBy('nama_bahan')->get();
        return view('produksi.laporanproduksi.edit', compact('laporan', 'stocks'));
    }

    public function update(Request $request, $id)
    {
        $laporan = LaporanProduksi::findOrFail($id);

        $request->validate([
            'stock_id'         => 'required|exists:stocks,id',
            'jumlah_digunakan' => 'required|integer|min:1',
        ]);

        // Kembalikan stok lama
        $oldStock = Stock::findOrFail($laporan->stock_id);
        $oldStock->increment('jumlah', $laporan->jumlah_digunakan);

        // Ambil stok baru
        $newStock = Stock::findOrFail($request->stock_id);
        if ($newStock->jumlah < $request->jumlah_digunakan) {
            return back()->with('error', "Stok {$newStock->nama_bahan} tidak mencukupi.");
        }

        // Kurangi stok baru
        $newStock->decrement('jumlah', $request->jumlah_digunakan);

        // Update laporan
        $laporan->update([
            'stock_id'         => $request->stock_id,
            'jumlah_digunakan' => $request->jumlah_digunakan,
            'tanggal'          => now()->toDateString(),
        ]);

        return redirect()->route('produksi.laporanproduksi.index')
                         ->with('success', 'Laporan produksi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $laporan = LaporanProduksi::findOrFail($id);

        // Kembalikan stok induk
        $stock = Stock::findOrFail($laporan->stock_id);
        $stock->increment('jumlah', $laporan->jumlah_digunakan);

        $laporan->delete();

        return redirect()->route('produksi.laporanproduksi.index')
                         ->with('success', 'Laporan produksi berhasil dihapus dan stok dikembalikan.');
    }

    public function buatMinuman(Request $request)
    {
        $request->validate([
            'minuman_id'   => 'required|exists:minumans,id',
            'jumlah_dibuat'=> 'required|integer|min:1',
        ]);

        $minuman = Minuman::findOrFail($request->minuman_id);
        $minuman->increment('stok', $request->jumlah_dibuat);

        return back()->with('success', "Stok minuman {$minuman->nama} berhasil ditambah.");
    }

    public function sendWa()
    {
        $today = now()->toDateString();
        $laporanToday = LaporanProduksi::with('stock')
                            ->whereDate('tanggal', $today)
                            ->get();

        if ($laporanToday->isEmpty()) {
            return back()->with('error', 'Belum ada laporan produksi hari ini.');
        }

        $message = "📋 *Laporan Bahan Terpakai Hari Ini*\nTanggal: $today\n\n";
        foreach ($laporanToday as $item) {
            $namaBahan = $item->stock->nama_bahan ?? 'Bahan dihapus';
            $satuan = $item->stock->satuan ?? '';
            $message .= "- {$namaBahan}: {$item->jumlah_digunakan} {$satuan}\n";
        }

        $phone = '6289504528079'; // nomor tujuan WA
        $waLink = "https://api.whatsapp.com/send?phone={$phone}&text=" . urlencode($message);

        return redirect($waLink);
    }
}
