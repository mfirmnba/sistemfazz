@extends('layouts.admin') @section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Edit Stock Bahan</h1>

    <form
        action="{{ route('admin.stock.update', $stock->id) }}"
        method="POST"
        class="space-y-4"
    >
        @csrf @method('PUT')

        <!-- Nama Bahan - Dihapus atau Disembunyikan -->
        <div>
            <label class="block mb-1">Nama Bahan</label>
            <input
                type="text"
                name="nama"
                class="border rounded p-2 w-full"
                value="{{ old('nama', $stock->nama) }}"
                disabled
                <!--
                Field
                ini
                hanya
                untuk
                tampilan,
                tidak
                bisa
                diubah
                --
            />
            />
        </div>

        <!-- Jumlah Stok yang Dapat Diperbarui -->
        <div>
            <label class="block mb-1">Jumlah Stok</label>
            <input
                type="number"
                name="jumlah"
                class="border rounded p-2 w-full"
                value="{{ old('jumlah', $stock->jumlah) }}"
                required
            />
        </div>

        <!-- Satuan - Dihapus atau Disembunyikan -->
        <div>
            <label class="block mb-1">Satuan</label>
            <input
                type="text"
                name="satuan"
                class="border rounded p-2 w-full"
                value="{{ old('satuan', $stock->satuan) }}"
                disabled
                <!--
                Field
                ini
                hanya
                untuk
                tampilan,
                tidak
                bisa
                diubah
                --
            />
            />
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
            Update
        </button>
        <a
            href="{{ route('admin.stock.index') }}"
            class="bg-gray-300 px-4 py-2 rounded"
        >
            Batal
        </a>
    </form>
</div>
@endsection
