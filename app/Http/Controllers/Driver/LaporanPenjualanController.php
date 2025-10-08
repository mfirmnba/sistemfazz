<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\LaporanPenjualan;
use App\Models\Minuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaporanPenjualanController extends Controller
{
    // Tampilkan semua laporan driver hari ini
    public function index()
    {
        $today = now()->toDateString();

        $laporan = LaporanPenjualan::where('user_id', auth()->id())
                    ->whereDate('tanggal', $today)
                    ->with('minuman')
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('driver.laporanpenjualan.index', compact('laporan', 'today'));
    }

    // Form input laporan
    public function create()
    {
        // Hanya minuman yang stok hari ini > 0
        $minumans = Minuman::where('stok_hari_ini', '>', 0)
                           ->orderBy('nama')
                           ->get();

        return view('driver.laporanpenjualan.create', compact('minumans'));
    }

    // Form edit laporan
    public function edit($id)
    {
        $laporan = LaporanPenjualan::where('id', $id)
                    ->where('user_id', Auth::id()) // hanya bisa edit miliknya
                    ->firstOrFail();

        $minumans = Minuman::orderBy('nama')->get();

        return view('driver.laporanpenjualan.edit', compact('laporan', 'minumans'));
    }

    // Update laporan
    public function update(Request $request, $id)
    {
        $laporan = LaporanPenjualan::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

        $request->validate([
            'minuman_id' => 'required|exists:minumans,id',
            'jumlah' => 'required|integer|min:1',
            'status' => 'required|in:terjual,expired,tumpah',
            'bukti_foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Kembalikan stok lama dulu
        $minumanLama = Minuman::find($laporan->minuman_id);
        $minumanLama->stok_hari_ini += $laporan->jumlah;
        $minumanLama->save();

        // Cek stok baru
        $minumanBaru = Minuman::findOrFail($request->minuman_id);
        if ($minumanBaru->stok_hari_ini < $request->jumlah) {
            return back()->with('error', "Stok minuman {$minumanBaru->nama} tidak cukup.");
        }

        // Kurangi stok baru
        $minumanBaru->stok_hari_ini -= $request->jumlah;
        $minumanBaru->save();

        $data = $request->only(['minuman_id','jumlah','status']);

        if ($request->hasFile('bukti_foto')) {
            // hapus file lama jika ada
            if ($laporan->bukti_foto) {
                Storage::disk('public')->delete($laporan->bukti_foto);
            }
            $data['bukti_foto'] = $request->file('bukti_foto')->store('bukti_foto', 'public');
        }

        $laporan->update($data);

        return redirect()->route('driver.laporanpenjualan.index')
                        ->with('success', "Laporan berhasil diperbarui.");
    }


    // Simpan laporan driver
    public function store(Request $request)
    {
        $request->validate([
            'minuman_id' => 'required|exists:minumans,id',
            'jumlah' => 'required|integer|min:1',
            'status' => 'required|in:terjual,expired,tumpah',
            'bukti_foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $minuman = Minuman::findOrFail($request->minuman_id);

        // Cek stok hari ini
        if ($minuman->stok_hari_ini < $request->jumlah) {
            return back()->with('error', "Stok minuman {$minuman->nama} tidak cukup untuk hari ini.");
        }

        // Kurangi stok hari ini
        $minuman->stok_hari_ini -= $request->jumlah;
        $minuman->save();

        $data = $request->only(['minuman_id','jumlah','status']);
        $data['user_id'] = Auth::id();
        $data['tanggal'] = now()->toDateString();

        if ($request->hasFile('bukti_foto')) {
            $data['bukti_foto'] = $request->file('bukti_foto')->store('bukti_foto', 'public');
        }

        LaporanPenjualan::create($data);

        return redirect()->route('driver.laporanpenjualan.index')
                         ->with('success', "Penjualan {$minuman->nama} berhasil dicatat.");
    }

    // Kirim laporan WA
    public function sendWhatsapp()
    {
        $today = now()->toDateString();

        $laporans = auth()->user()
            ->laporanPenjualan()
            ->with('minuman')
            ->whereDate('tanggal', $today)
            ->get();

        if ($laporans->isEmpty()) {
            return redirect()->back()->with('error', 'Belum ada laporan hari ini.');
        }

        $message = "📊 Laporan Penjualan {$today}\n\n";

        foreach ($laporans as $laporan) {
            $hargaText = $laporan->status === 'terjual' 
                ? " = Rp " . number_format($laporan->minuman->harga * $laporan->jumlah, 0, ',', '.') 
                : '';
            $message .= "  {$laporan->minuman->nama} x{$laporan->jumlah}{$hargaText}\n";
        }

        $terjual  = $laporans->where('status', 'terjual')->sum('jumlah');
        $expired  = $laporans->where('status', 'expired')->sum('jumlah');
        $tumpah   = $laporans->where('status', 'tumpah')->sum('jumlah');
        $totalKeluar = $laporans->sum('jumlah');
        $pendapatan  = $laporans->where('status', 'terjual')->sum(function($item) {
            return $item->jumlah * $item->minuman->harga;
        });

        $message .= "\nRingkasan Hari Ini:\n";
        $message .= "  Terjual: {$terjual} cup\n";
        $message .= "  Expired: {$expired} cup\n";
        $message .= "  Tumpah: {$tumpah} cup\n";
        $message .= "  Total Minuman Keluar: {$totalKeluar} cup\n";
        $message .= "  Pendapatan (hanya terjual): Rp " . number_format($pendapatan,0,',','.') . "\n";

        $phone = '62895630447306'; // ganti sesuai tujuan
        $url = 'https://wa.me/' . $phone . '?text=' . urlencode($message);

        return redirect($url);
    }
}
