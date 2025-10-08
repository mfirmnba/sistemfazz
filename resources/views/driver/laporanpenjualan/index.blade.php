@extends('layouts.driver') @section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">
        📋 Laporan Penjualan Hari Ini ({{ $today }})
    </h1>

    @if($laporan->isEmpty())
    <div class="bg-yellow-100 text-yellow-800 p-4 rounded">
        Belum ada laporan untuk hari ini.
    </div>
    @else
    <!-- WRAPPER AGAR TABEL BISA DISCROLL DI HP -->
    <div class="overflow-x-auto">
        <table
            class="w-full border-collapse border border-gray-300 shadow-md min-w-[600px]"
        >
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="p-3 border border-gray-300">#</th>
                    <th class="p-3 border border-gray-300">Minuman</th>
                    <th class="p-3 border border-gray-300">Jumlah</th>
                    <th class="p-3 border border-gray-300">Status</th>
                    <th class="p-3 border border-gray-300">Total</th>
                    <th class="p-3 border border-gray-300">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($laporan as $key => $item)
                <tr class="text-center hover:bg-gray-100">
                    <td class="p-3 border border-gray-300">{{ $key + 1 }}</td>
                    <td class="p-3 border border-gray-300">
                        {{ $item->minuman->nama }}
                    </td>
                    <td class="p-3 border border-gray-300">
                        {{ $item->jumlah }}
                    </td>
                    <td class="p-3 border border-gray-300">
                        @if($item->status === 'terjual') ✅ Terjual
                        @elseif($item->status === 'expired') ⚠️ Expired
                        @elseif($item->status === 'tumpah') 💧 Tumpah @endif
                    </td>
                    <td class="p-3 border border-gray-300">
                        @if($item->status === 'terjual') Rp
                        {{ number_format($item->jumlah * $item->minuman->harga, 0, ',', '.') }}
                        @else Rp 0 @endif
                    </td>
                    <td
                        class="p-3 border border-gray-300 flex gap-2 justify-center flex-wrap"
                    >
                        <a
                            href="{{ route('driver.laporanpenjualan.edit', $item->id) }}"
                            class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-700 text-sm"
                            >✏️ Edit</a
                        >

                        <form
                            action="{{ route('driver.laporanpenjualan.destroy', $item->id) }}"
                            method="POST"
                            onsubmit="return confirm('Yakin hapus laporan ini?')"
                        ></form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="mt-6 flex flex-wrap gap-3">
        <a
            href="{{ route('driver.laporanpenjualan.create') }}"
            class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-800 text-sm sm:text-base"
        >
            ➕ Tambah Laporan
        </a>
        <a
            href="{{ route('driver.dashboard') }}"
            class="bg-gray-700 text-white px-4 py-2 rounded shadow hover:bg-gray-900 text-sm sm:text-base"
        >
            ⬅️ Kembali ke Dashboard
        </a>
        @if(!$laporan->isEmpty())
        <a
            href="{{ route('driver.laporanpenjualan.sendWhatsapp') }}"
            target="_blank"
            class="bg-green-500 text-white px-4 py-2 rounded shadow hover:bg-green-700 text-sm sm:text-base"
        >
            📲 Kirim ke WhatsApp
        </a>
        @endif
    </div>
</div>

<!-- CSS TAMBAHAN UNTUK RESPONSIF DI HP -->
<style>
    @media (max-width: 640px) {
        .container {
            padding: 1rem !important;
        }

        h1.text-2xl {
            font-size: 1.3rem !important;
            text-align: center;
        }

        table {
            font-size: 0.85rem !important;
        }

        th,
        td {
            padding: 0.5rem !important;
        }

        .flex.gap-2.justify-center {
            flex-direction: column !important;
            align-items: center !important;
        }

        .mt-6.flex.gap-4,
        .mt-6.flex.flex-wrap.gap-3 {
            flex-direction: column !important;
            align-items: stretch !important;
        }

        a.px-4.py-2 {
            text-align: center;
        }
    }
</style>
@endsection
