@extends('layouts.admin') @section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">✏️ Edit Minuman</h1>

    <form
        action="{{ route('admin.minuman.update', $minuman->id) }}"
        method="POST"
        class="space-y-4"
    >
        @csrf @method('PUT')

        <div>
            <label class="block font-semibold">Nama Minuman</label>
            <input
                type="text"
                name="nama"
                value="{{ $minuman->nama }}"
                class="w-full border rounded p-2"
                required
            />
        </div>

        <div>
            <label class="block font-semibold">Harga</label>
            <input
                type="number"
                name="harga"
                value="{{ $minuman->harga }}"
                class="w-full border rounded p-2"
                min="0"
                required
            />
        </div>

        <div>
            <label class="block font-semibold">Stok Hari Ini</label>
            <input
                type="number"
                name="stok_hari_ini"
                value="{{ $minuman->stok_hari_ini }}"
                class="w-full border rounded p-2"
                min="0"
                required
            />
        </div>

        <div>
            <label class="block font-semibold">Stok Besok</label>
            <input
                type="number"
                name="stok_besok"
                value="{{ $minuman->stok_besok }}"
                class="w-full border rounded p-2"
                min="0"
                required
            />
        </div>

        <div>
            <label class="block font-semibold">Deskripsi</label>
            <textarea
                name="deskripsi"
                class="w-full border rounded p-2"
                rows="3"
                >{{ $minuman->deskripsi }}</textarea
            >
        </div>

        <div class="flex gap-4 mt-4">
            <button
                type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-800"
            >
                💾 Update
            </button>
            <a
                href="{{ route('admin.minuman.index') }}"
                class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-800"
                >⬅️ Kembali</a
            >
        </div>
    </form>
</div>
@endsection
