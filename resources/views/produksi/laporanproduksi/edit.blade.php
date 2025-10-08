<!-- resources/views/produksi/laporanproduksi/edit.blade.php -->
@extends('layouts.produksi') @section('title', 'Edit Laporan Produksi')
@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">✏️ Edit Laporan Produksi</h1>

    <form
        action="{{ route('produksi.laporanproduksi.update', $laporan->id) }}"
        method="POST"
        class="space-y-4"
    >
        @csrf @method('PUT')

        <div>
            <label class="block font-semibold">Bahan Produksi</label>
            <select name="stock_id" class="w-full border rounded p-2" required>
                @foreach($stocks as $item)
                <option value="{{ $item->id }}" {{ $laporan->
                    stock_id == $item->id ? 'selected' : '' }}>
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
                value="{{ $laporan->jumlah_digunakan }}"
                required
            />
        </div>

        <div class="flex gap-4 mt-4">
            <button
                type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-800"
            >
                💾 Update
            </button>
            <a
                href="{{ route('produksi.laporanproduksi.index') }}"
                class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-800"
            >
                ⬅️ Kembali
            </a>
        </div>
    </form>
</div>
@endsection
