<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Minuman;
use Illuminate\Http\Request;

class MinumanController extends Controller
{
    // Tampilkan semua minuman
    public function index()
    {
        $minumans = Minuman::orderBy('nama')->get();
        return view('admin.minuman.index', compact('minumans'));
    }

    // Form tambah minuman
    public function create()
    {
        return view('admin.minuman.create');
    }

    // Simpan minuman baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok_hari_ini' => 'required|integer|min:0',
            'stok_besok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        Minuman::create($request->only('nama', 'harga', 'stok_hari_ini', 'stok_besok', 'deskripsi'));

        return redirect()->route('admin.minuman.index')
                         ->with('success', 'Minuman berhasil ditambahkan!');
    }

    // Form edit minuman
    public function edit(Minuman $minuman)
    {
        return view('admin.minuman.edit', compact('minuman'));
    }

    // Update minuman
    public function update(Request $request, Minuman $minuman)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok_hari_ini' => 'required|integer|min:0',
            'stok_besok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        $minuman->update($request->only('nama', 'harga', 'stok_hari_ini', 'stok_besok', 'deskripsi'));

        return redirect()->route('admin.minuman.index')
                         ->with('success', 'Minuman berhasil diperbarui!');
    }

    // Hapus minuman
    public function destroy(Minuman $minuman)
    {
        $minuman->delete();
        return redirect()->route('admin.minuman.index')
                         ->with('success', 'Minuman berhasil dihapus!');
    }
}
