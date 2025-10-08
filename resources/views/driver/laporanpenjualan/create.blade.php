@extends('layouts.driver') @section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">📋 Buat Laporan Penjualan</h1>

    <form
        action="{{ route('driver.laporanpenjualan.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf
        <div>
            <label class="block font-semibold">Minuman</label>
            <select
                name="minuman_id"
                class="w-full border rounded p-2"
                required
            >
                <option value="">Pilih Minuman</option>
                @foreach($minumans as $minuman)
                <option value="{{ $minuman->id }}">
                    {{ $minuman->nama }} (Stok hari ini:
                    {{ $minuman->stok_hari_ini }})
                </option>
                @endforeach
            </select>
        </div>

        <div class="mt-2">
            <label class="block font-semibold">Jumlah</label>
            <input
                type="number"
                name="jumlah"
                class="w-full border rounded p-2"
                min="1"
                required
            />
        </div>

        <div class="mt-2">
            <label class="block font-semibold">Status</label>
            <select name="status" class="w-full border rounded p-2" required>
                <option value="terjual">Terjual</option>
                <option value="expired">Expired</option>
                <option value="tumpah">Tumpah</option>
            </select>
        </div>

        <div class="mt-2">
            <label class="block font-semibold">Bukti Foto (opsional)</label>
            <input
                type="file"
                name="bukti_foto"
                class="w-full border rounded p-2"
            />
        </div>

        <button
            type="submit"
            class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-800"
        >
            💾 Simpan
        </button>
    </form>
</div>
@endsection
