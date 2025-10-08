<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Stock;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::latest()->paginate(10);
        return view('admin.stock.index', compact('stocks'));
    }

    public function create()
    {
        return view('admin.stock.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_bahan' => 'required|string|max:255',
            'jumlah'     => 'required|numeric|min:0',
            'satuan'     => 'required|string|max:50',
        ]);

        // Simpan data ke database
        Stock::create([
            'nama_bahan' => $request->nama_bahan,
            'jumlah'     => $request->jumlah,
            'satuan'     => $request->satuan,
        ]);

        // Redirect ke index dengan pesan sukses
        return redirect()->route('admin.stock.index')
                        ->with('success', 'Stock berhasil ditambahkan.');
    }

    public function edit($id)
    {
        // Mengambil data stock berdasarkan ID
        $stock = Stock::findOrFail($id);

        // Mengembalikan view dengan data stock
        return view('admin.stock.edit', compact('stock'));
    }

    public function update(Request $request, $id)
    {
        // Validasi hanya untuk 'jumlah' stok yang diperbarui
        $validated = $request->validate([
            'jumlah' => 'required|numeric|min:0',  // Pastikan jumlah lebih besar dari 0
        ]);

        // Ambil stock berdasarkan ID
        $stock = Stock::findOrFail($id);

        // Update hanya jumlah stok
        $stock->update([
            'jumlah' => $request->jumlah, // Hanya update jumlah
        ]);

        // Redirect ke halaman daftar stok dengan pesan sukses
        return redirect()->route('admin.stock.index')->with('success', 'Stok berhasil diperbarui!');
    }


    public function destroy($id)
    {
        $stock = Stock::findOrFail($id);
        $stock->delete();

        return redirect()->route('admin.stock.index')
                         ->with('success', 'Stock berhasil dihapus.');
    }
}
