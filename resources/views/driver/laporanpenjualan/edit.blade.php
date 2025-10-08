@extends('layouts.driver') @section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">✏️ Edit Laporan Penjualan</h1>

    <form
        action="{{ route('driver.laporanpenjualan.update', $laporan->id) }}"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-4"
    >
        @csrf @method('PUT')

        <!-- Pilih Minuman -->
        <div>
            <label class="block font-semibold">Minuman</label>
            <select
                name="minuman_id"
                class="w-full border rounded p-2"
                required
            >
                <option value="">-- Pilih Minuman --</option>
                @foreach($minumans as $item)
                <option value="{{ $item->id }}" {{ $laporan->
                    minuman_id == $item->id ? 'selected' : '' }}>
                    {{ $item->nama }} - Rp
                    {{ number_format($item->harga, 0, ',', '.') }} (stok hari
                    ini: {{ $item->stok_hari_ini }})
                </option>
                @endforeach
            </select>
        </div>

        <!-- Jumlah -->
        <div>
            <label class="block font-semibold">Jumlah</label>
            <input
                type="number"
                name="jumlah"
                min="1"
                class="w-full border rounded p-2"
                value="{{ $laporan->jumlah }}"
                required
            />
        </div>

        <!-- Status -->
        <div>
            <label class="block font-semibold">Status</label>
            <select name="status" class="w-full border rounded p-2" required>
                <option value="terjual" {{ $laporan->
                    status == 'terjual' ? 'selected' : '' }}>✅ Terjual
                </option>
                <option value="expired" {{ $laporan->
                    status == 'expired' ? 'selected' : '' }}>⚠️ Expired
                </option>
                <option value="tumpah" {{ $laporan->
                    status == 'tumpah' ? 'selected' : '' }}>💧 Tumpah
                </option>
            </select>
        </div>

        <!-- Upload Foto (opsional) -->
        <div>
            <label class="block font-semibold">Bukti Foto (opsional)</label
            ><br />
            @if($laporan->bukti_foto)
            <img
                src="{{ asset('storage/'.$laporan->bukti_foto) }}"
                alt="Bukti Foto"
                class="h-24 mb-2 rounded"
            />
            @endif
            <input
                type="file"
                name="bukti_foto"
                accept="image/*"
                class="w-full border rounded p-2"
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
                href="{{ route('driver.laporanpenjualan.index') }}"
                class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-800"
            >
                ⬅️ Kembali
            </a>
        </div>
    </form>
</div>
@endsection
