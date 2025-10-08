@extends('layouts.produksi') @section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4 text-center md:text-left">
        📊 Dashboard Produksi
    </h1>
    <p class="text-center md:text-left">
        Halo, <b>{{ auth()->user()->name }}</b> 👋
    </p>

    <!-- Statistik Ringkas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <!-- Total Stock Produksi -->
        <div
            class="bg-blue-600 text-white rounded-lg p-6 shadow-md text-center md:text-left"
        >
            <h2 class="text-lg font-semibold">Total Stock Tersedia</h2>
            <p class="text-3xl font-bold mt-2">{{ $totalStock ?? 0 }}</p>
        </div>

        <!-- Bahan Terpakai Hari Ini -->
        <div
            class="bg-green-600 text-white rounded-lg p-6 shadow-md text-center md:text-left"
        >
            <h2 class="text-lg font-semibold">Bahan Terpakai Hari Ini</h2>
            <p class="text-3xl font-bold mt-2">
                {{ $bahanTerpakaiHariIni ?? 0 }}
            </p>
        </div>

        <!-- Laporan Terakhir -->
        <div
            class="bg-gray-700 text-white rounded-lg p-6 shadow-md text-center md:text-left"
        >
            <h2 class="text-lg font-semibold">Laporan Terakhir</h2>
            @if($lastLaporan && $lastLaporan->stock)
            <p class="mt-2">
                {{ $lastLaporan->stock->nama_bahan }} -
                {{ $lastLaporan->jumlah_digunakan }}
                {{ $lastLaporan->stock->satuan }}
            </p>
            <small class="text-gray-300"
                >Tanggal: {{ $lastLaporan->tanggal }}</small
            >
            @else
            <p class="mt-2">Belum ada laporan atau stock sudah dihapus admin</p>
            @endif
        </div>
    </div>

    <!-- Rincian Total Stock Produksi -->
    <div class="mt-8">
        <h2 class="text-xl font-semibold mb-2 text-center md:text-left">
            📦 Rincian Total Stock Produksi
        </h2>
        <div class="overflow-x-auto">
            <table
                class="w-full border-collapse border border-gray-300 shadow-md text-sm md:text-base"
            >
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="p-3 border">#</th>
                        <th class="p-3 border">Nama Bahan</th>
                        <th class="p-3 border">Jumlah Awal</th>
                        <th class="p-3 border">Jumlah Sekarang</th>
                        <th class="p-3 border">Satuan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stocksWithAwal as $key => $stock)
                    <tr class="text-center hover:bg-gray-100">
                        <td class="p-3 border">{{ $key + 1 }}</td>
                        <td class="p-3 border">{{ $stock->nama_bahan }}</td>
                        <td class="p-3 border">{{ $stock->jumlah_awal }}</td>
                        <td class="p-3 border">
                            {{ $stock->jumlah_sekarang }}
                        </td>
                        <td class="p-3 border">{{ $stock->satuan }}</td>
                    </tr>
                    @empty
                    <tr class="text-center">
                        <td colspan="5" class="p-3 border">
                            Belum ada stock produksi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Rincian Bahan Terpakai Hari Ini -->
    <div class="mt-8">
        <h2 class="text-xl font-semibold mb-2 text-center md:text-left">
            📋 Rincian Bahan Terpakai Hari Ini
        </h2>
        @if($laporanToday->isEmpty())
        <div
            class="bg-yellow-100 text-yellow-800 p-4 rounded text-center md:text-left"
        >
            Belum ada produksi hari ini.
        </div>
        @else
        <div class="overflow-x-auto">
            <table
                class="w-full border-collapse border border-gray-300 shadow-md text-sm md:text-base"
            >
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="p-3 border">#</th>
                        <th class="p-3 border">Nama Bahan</th>
                        <th class="p-3 border">Jumlah Digunakan</th>
                        <th class="p-3 border">Satuan</th>
                        <th class="p-3 border">User Produksi</th>
                        <th class="p-3 border">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($laporanToday as $key => $item)
                    <tr class="text-center hover:bg-gray-100">
                        <td class="p-3 border">{{ $key + 1 }}</td>
                        <td class="p-3 border">
                            {{ $item->stock?->nama_bahan ?? 'Bahan dihapus' }}
                        </td>
                        <td class="p-3 border">
                            {{ $item->jumlah_digunakan }}
                        </td>
                        <td class="p-3 border">
                            {{ $item->stock?->satuan ?? '-' }}
                        </td>
                        <td class="p-3 border">
                            {{ $item->user?->name ?? 'Tidak diketahui' }}
                        </td>
                        <td class="p-3 border">{{ $item->tanggal }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    <!-- Rincian Minuman Produksi (CRUD) -->
    <div class="mt-8">
        <h2 class="text-xl font-semibold mb-2 text-center md:text-left">
            🍹 Edit Stock Minuman
        </h2>
        <div class="overflow-x-auto">
            <table
                class="w-full border-collapse border border-gray-300 shadow-md text-sm md:text-base"
            >
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="p-3 border">#</th>
                        <th class="p-3 border">Nama Minuman</th>
                        <th class="p-3 border">Harga</th>
                        <th class="p-3 border">Stok Hari Ini</th>
                        <th class="p-3 border">Stok Besok</th>
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
                        <td class="p-3 border">
                            {{ $minuman->stok_hari_ini }}
                        </td>
                        <td class="p-3 border">{{ $minuman->stok_besok }}</td>
                        <td
                            class="p-3 border flex justify-center gap-2 flex-wrap"
                        >
                            <a
                                href="{{ route('produksi.minuman.edit', $minuman->id) }}"
                                class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-700"
                                >✏️ Edit</a
                            >
                            <form
                                action="{{ route('produksi.minuman.destroy', $minuman->id) }}"
                                method="POST"
                                onsubmit="return confirm('Yakin hapus minuman ini?')"
                            ></form>
                        </td>
                    </tr>
                    @empty
                    <tr class="text-center">
                        <td colspan="6" class="p-3 border">
                            Belum ada minuman
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Konfirmasi Reset Stok -->
    <div
        id="resetModal"
        class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center hidden"
    >
        <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 md:w-1/3">
            <h3 class="text-xl font-semibold mb-4 text-center md:text-left">
                Update Stok Hari Ini
            </h3>
            <p class="text-center md:text-left">
                Rider Nungguin Nih Yuk Buruan!
            </p>
            <div class="flex justify-end mt-4 gap-2">
                <button
                    id="cancelBtn"
                    class="bg-gray-300 text-black px-4 py-2 rounded"
                    onclick="closeResetModal()"
                >
                    Batal
                </button>
                <form
                    id="resetForm"
                    action="{{ route('produksi.minuman.resetAll') }}"
                    method="POST"
                    style="display: inline"
                    onsubmit="return confirmReset()"
                >
                    @csrf
                    <button
                        type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded"
                    >
                        Ya, Update
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openResetModal() {
            document.getElementById("resetModal").classList.remove("hidden");
        }
        function closeResetModal() {
            document.getElementById("resetModal").classList.add("hidden");
        }
        function confirmReset() {
            return true;
        }
    </script>

    <!-- Tombol Aksi Laporan Produksi -->
    <div class="mt-6 flex flex-wrap gap-4 justify-center md:justify-start">
        <a
            href="{{ route('produksi.laporanproduksi.create') }}"
            class="bg-blue-500 text-white px-4 py-2 rounded shadow hover:bg-blue-700"
        >
            + Tambah Laporan Produksi
        </a>
        <a
            href="{{ route('produksi.laporanproduksi.index') }}"
            class="bg-gray-700 text-white px-4 py-2 rounded shadow hover:bg-gray-900"
        >
            📋 Lihat Laporan Stock
        </a>
        <a
            href="{{ route('produksi.laporanproduksi.sendWa') }}"
            target="_blank"
            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-800"
        >
            📤 Kirim ke WhatsApp
        </a>
        <button
            onclick="openResetModal()"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-800"
        >
            🔄 Update Stock Minuman
        </button>
    </div>
</div>
@endsection
