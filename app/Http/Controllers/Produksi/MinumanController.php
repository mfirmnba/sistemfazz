<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\Minuman;
use Illuminate\Http\Request;

class MinumanController extends Controller
{
    public function index()
    {
        $minumans = Minuman::orderBy('nama')->get();
        return view('produksi.minuman.index', compact('minumans'));
    }

    public function create()
    {
        return view('produksi.minuman.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok_hari_ini' => 'required|integer|min:0',
            'stok_besok' => 'required|integer|min:0',
        ]);

        Minuman::create($request->only('nama','harga','stok_hari_ini','stok_besok'));

        return redirect()->route('produksi.minuman.index')
                         ->with('success', 'Minuman berhasil ditambahkan.');
    }

    // MinumanController.php

// MinumanController.php

    public function edit($id)
    {
        // Mengambil data minuman berdasarkan ID
        $minuman = Minuman::findOrFail($id);

        // Menampilkan halaman edit dengan data minuman
        return view('produksi.minuman.edit', compact('minuman'));
    }

// MinumanController.php

    public function update(Request $request, $id)
    {
        // Validasi stok besok
        $validated = $request->validate([
            'stok_besok' => 'required|numeric|min:0', // Validasi stok besok
        ]);

        // Ambil data minuman berdasarkan ID
        $minuman = Minuman::findOrFail($id);

        // Update hanya stok besok (stok hari ini tetap sama)
        $minuman->update([
            'stok_besok' => $request->stok_besok,  // Update stok besok
            // 'stok_hari_ini' => $minuman->stok_hari_ini, // Stok hari ini tetap sama
        ]);

        // Redirect kembali ke halaman daftar minuman dengan pesan sukses
        return redirect()->route('produksi.minuman.index')->with('success', 'Stok minuman berhasil diperbarui');
    }



}
