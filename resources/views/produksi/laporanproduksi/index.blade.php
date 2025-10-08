@extends('layouts.produksi') @section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">
        📦 Laporan Produksi Hari Ini ({{ $today }})
    </h1>

    @if($laporan->isEmpty())
    <div class="bg-yellow-100 text-yellow-800 p-4 rounded">
        Belum ada laporan untuk hari ini.
    </div>
    @else
    <table class="w-full border-collapse border border-gray-300 shadow-md">
        <thead class="bg-gray-800 text-white">
            <tr>
                <th class="p-3 border">No</th>
                <th class="p-3 border">Bahan</th>
                <th class="p-3 border">Jumlah</th>
                <th class="p-3 border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporan as $key => $item)
            <tr class="text-center hover:bg-gray-100">
                <td class="p-3 border">{{ $key + 1 }}</td>
                <td class="p-3 border">
                    {{ $item->stock->nama_bahan }}
                </td>
                <td class="p-3 border">
                    {{ $item->jumlah_digunakan }} {{ $item->stock->satuan }}
                </td>
                <td class="p-3 border">
                    <form
                        action="{{ route('produksi.laporanproduksi.destroy', $item->id) }}"
                        method="POST"
                        onsubmit="return confirm('Yakin hapus laporan ini?')"
                    >
                        @csrf @method('DELETE')
                        <button
                            type="submit"
                            class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-800"
                        >
                            🗑️ Hapus
                        </button>
                    </form>
                </td>
            </tr>

            @endforeach
        </tbody>
    </table>
    @endif

    <div class="mt-6 flex gap-4">
        <a
            href="{{ route('produksi.laporanproduksi.create') }}"
            class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-800"
        >
            ➕ Tambah Laporan Produksi
        </a>
        <a
            href="{{ route('produksi.dashboard') }}"
            class="bg-gray-700 text-white px-4 py-2 rounded shadow hover:bg-gray-900"
        >
            ⬅️ Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection
