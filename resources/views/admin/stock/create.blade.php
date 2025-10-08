@extends('layouts.admin') @section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Tambah Stock Bahan</h1>

    <form
        action="{{ route('admin.stock.store') }}"
        method="POST"
        class="space-y-4"
    >
        @csrf

        <div>
            <label class="block mb-1">Nama Bahan</label>
            <input
                type="text"
                name="nama_bahan"
                class="border rounded p-2 w-full"
                value="{{ old('nama') }}"
                required
            />
        </div>

        <div>
            <label class="block mb-1">Jumlah</label>
            <input
                type="number"
                name="jumlah"
                class="border rounded p-2 w-full"
                value="{{ old('jumlah') }}"
                required
            />
        </div>

        <div>
            <label class="block mb-1">Satuan</label>
            <input
                type="text"
                name="satuan"
                class="border rounded p-2 w-full"
                value="{{ old('satuan') }}"
                required
            />
        </div>

        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">
            Simpan
        </button>
        <a
            href="{{ route('admin.stock.index') }}"
            class="bg-gray-300 px-4 py-2 rounded"
            >Batal</a
        >
    </form>
</div>
@endsection
