@extends('layouts.produksi') @section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">🍹 Laporan Minuman Produksi</h1>

    <!-- <div class="flex justify-end mb-4 gap-2">
        <a
            href="{{ route('produksi.minuman.create') }}"
            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700"
        >
            + Tambah Minuman
        </a>
    </div> -->

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300 shadow-md">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="p-3 border">#</th>
                    <th class="p-3 border">Nama Minuman</th>
                    <th class="p-3 border">Harga</th>
                    <th class="p-3 border">Stok Hari Ini</th>
                    <th class="p-3 border">Stok Besok</th>
                    <th class="p-3 border">Tanggal Dibuat</th>
                    <th class="p-3 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($minumans as $key => $minuman)
                <tr class="text-center hover:bg-gray-100">
                    <td class="p-3 border">{{ $key + 1 }}</td>
                    <td class="p-3 border">{{ $minuman->nama }}</td>
                    <td class="p-3 border">
                        Rp {{ number_format($minuman->harga,0,',','.') }}
                    </td>
                    <td class="p-3 border">{{ $minuman->stok_hari_ini }}</td>
                    <td class="p-3 border">{{ $minuman->stok_besok }}</td>
                    <td class="p-3 border">
                        {{ $minuman->created_at->format('Y-m-d') }}
                    </td>
                    <td class="p-3 border flex gap-1 justify-center">
                        <a
                            href="{{ route('produksi.minuman.edit', $minuman->id) }}"
                            class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-700"
                        >
                            ✏️ Edit
                        </a>
                    </td>
                </tr>
                @empty
                <tr class="text-center">
                    <td colspan="7" class="p-3 border">Belum ada minuman</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
