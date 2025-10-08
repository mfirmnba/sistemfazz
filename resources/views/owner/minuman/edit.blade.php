@extends('layouts.app') @section('content')
<div class="max-w-lg mx-auto bg-white p-6 rounded-xl shadow mt-8">
    <h1 class="text-2xl font-bold mb-4 text-center">✏️ Edit HPP Minuman</h1>

    <form
        action="{{ route('owner.minuman.update', $minuman->id) }}"
        method="POST"
    >
        @csrf @method('PUT')

        <div class="mb-4">
            <label class="block font-semibold mb-2">Nama Minuman</label>
            <input
                type="text"
                value="{{ $minuman->nama }}"
                disabled
                class="w-full border rounded p-2 bg-gray-100"
            />
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-2"
                >Harga Pokok Produksi (HPP)</label
            >
            <input
                type="number"
                step="0.01"
                name="hpp"
                value="{{ $minuman->hpp }}"
                class="w-full border rounded p-2"
                required
            />
        </div>

        <button
            type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-800 w-full"
        >
            💾 Simpan
        </button>
    </form>
</div>
@endsection
