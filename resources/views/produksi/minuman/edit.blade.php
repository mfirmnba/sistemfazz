@extends('layouts.produksi') @section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">🍹 Edit Minuman Produksi</h1>

    <form
        action="{{ route('produksi.minuman.update', $minuman->id) }}"
        method="POST"
        class="space-y-4"
    >
        @csrf @method('PUT')

        <!-- Nama Minuman - Tidak bisa diubah -->
        <div>
            <label class="block mb-1">Nama Minuman</label>
            <input
                type="text"
                name="nama"
                class="border rounded p-2 w-full"
                value="{{ old('nama', $minuman->nama) }}"
                disabled
            />
        </div>

        <!-- Stok Hari Ini - Tidak bisa diubah -->
        <div>
            <label class="block mb-1">Stok Hari Ini</label>
            <input
                type="number"
                name="stok_hari_ini"
                class="border rounded p-2 w-full"
                value="{{ old('stok_hari_ini', $minuman->stok_hari_ini) }}"
                disabled
            />
        </div>

        <!-- Stok Besok - Hanya ini yang bisa diubah -->
        <div>
            <label class="block mb-1">Stok Besok</label>
            <input
                type="number"
                name="stok_besok"
                class="border rounded p-2 w-full"
                value="{{ old('stok_besok', $minuman->stok_besok) }}"
                required
            />
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
            Update
        </button>

        <a
            href="{{ route('produksi.minuman.index') }}"
            class="bg-gray-300 px-4 py-2 rounded"
        >
            Batal
        </a>
    </form>
</div>
@endsection
