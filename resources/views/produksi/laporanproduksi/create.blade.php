<!-- resources/views/produksi/laporanproduksi/create.blade.php -->
@extends('layouts.produksi') @section('title', 'Tambah Laporan Produksi')
@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">➕ Tambah Laporan Produksi</h1>

    <form
        action="{{ route('produksi.laporanproduksi.store') }}"
        method="POST"
        class="space-y-4"
    >
        @csrf

        <div>
            <label class="block font-semibold">Bahan Produksi</label>
            <select name="stock_id" class="w-full border rounded p-2" required>
                <option value="">-- Pilih Bahan --</option>
                @foreach($stocks as $item)
                <option value="{{ $item->id }}">
                    {{ $item->nama_bahan }} (Stok: {{ $item->jumlah }}
                    {{ $item->satuan }})
                </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-semibold">Jumlah Diproduksi</label>
            <input
                type="number"
                name="jumlah_digunakan"
                min="1"
                class="w-full border rounded p-2"
                required
            />
        </div>

        <div class="flex gap-4 mt-4">
            <button
                type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-800"
            >
                💾 Simpan
            </button>
            <a
                href="{{ route('produksi.dashboard') }}"
                class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-800"
            >
                ⬅️ Kembali
            </a>
        </div>
    </form>
</div>
@endsection
