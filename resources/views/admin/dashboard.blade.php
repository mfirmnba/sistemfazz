@extends('layouts.admin') @section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">📊 Dashboard Admin</h1>
    <p class="mb-6 text-gray-700">
        Selamat datang, <b>{{ auth()->user()->name }}</b
        >!
    </p>

    <!-- Statistik Ringkas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white shadow rounded-xl p-6">
            <h2 class="text-lg font-semibold text-gray-700">Total Minuman</h2>
            <p class="text-3xl font-bold text-indigo-600">
                {{ $minumansCount ?? 0 }}
            </p>
        </div>
        <div class="bg-white shadow rounded-xl p-6">
            <h2 class="text-lg font-semibold text-gray-700">Total Stock</h2>
            <p class="text-3xl font-bold text-green-600">
                {{ $stocksCount ?? 0 }}
            </p>
        </div>
    </div>

    <!-- Rincian Stock Bahan -->
    <div class="mb-8 bg-white p-6 rounded-xl shadow">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">📦 Rincian Stock Bahan</h2>
            <a
                href="{{ route('admin.stock.create') }}"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
            >
                + Tambah Stock
            </a>
        </div>

        <div class="overflow-x-auto">
            <table
                class="w-full text-sm text-left border border-gray-200 rounded-lg"
            >
                <thead class="bg-gray-100 text-gray-700">
                    <tr class="text-center">
                        <th class="p-3 border">#</th>
                        <th class="p-3 border">Nama Bahan</th>
                        <th class="p-3 border">Jumlah</th>
                        <th class="p-3 border">Satuan</th>
                        <th class="p-3 border">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stocks as $key => $stock)
                    <tr class="hover:bg-gray-50 text-gray-800 text-center">
                        <td class="p-3 border">{{ $key + 1 }}</td>
                        <td class="p-3 border">{{ $stock->nama_bahan }}</td>
                        <td class="p-3 border">{{ $stock->jumlah }}</td>
                        <td class="p-3 border">{{ $stock->satuan }}</td>
                        <td class="p-3 border flex justify-center gap-2">
                            <a
                                href="{{ route('admin.stock.edit', $stock->id) }}"
                                class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-700 transition"
                            >
                                ✏️ Edit
                            </a>
                            <form
                                class="delete-form"
                                action="{{ route('admin.stock.destroy', $stock->id) }}"
                                method="POST"
                            >
                                @csrf @method('DELETE')
                                <button
                                    type="button"
                                    class="delete-btn bg-red-600 text-white px-3 py-1 rounded hover:bg-red-800 transition"
                                >
                                    🗑️ Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr class="text-center">
                        <td colspan="5" class="p-3 border text-gray-500">
                            Belum ada stock bahan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex left-end mt-4">
            <a
                href="{{ route('admin.kirim-stock-wa') }}"
                target="_blank"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded transition"
            >
                📤 Kirim Laporan Stock ke WhatsApp
            </a>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll(".delete-btn").forEach((button) => {
            button.addEventListener("click", function () {
                const form = this.closest("form");
                Swal.fire({
                    title: "Yakin ingin hapus?",
                    text: "Data stock bahan akan dihapus permanen!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Ya, hapus!",
                    cancelButtonText: "Batal",
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>

    <!-- Rincian Minuman -->
    <div class="mb-8 bg-white p-6 rounded-xl shadow">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">🍹 Rincian Minuman</h2>
            <a
                href="{{ route('admin.minuman.create') }}"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
            >
                + Tambah Minuman
            </a>
        </div>

        <div class="overflow-x-auto">
            <table
                class="w-full text-sm text-left border border-gray-200 rounded-lg"
            >
                <thead class="bg-gray-100 text-gray-700">
                    <tr class="text-center">
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
                    <tr class="hover:bg-gray-50 text-gray-800 text-center">
                        <td class="p-3 border">{{ $key + 1 }}</td>
                        <td class="p-3 border">{{ $minuman->nama }}</td>
                        <td class="p-3 border">
                            Rp {{ number_format($minuman->harga, 0, ',', '.') }}
                        </td>
                        <td class="p-3 border">
                            {{ $minuman->stok_hari_ini }}
                        </td>
                        <td class="p-3 border">{{ $minuman->stok_besok }}</td>
                        <td class="p-3 border flex justify-center gap-2">
                            <form
                                class="delete-form"
                                action="{{ route('admin.minuman.destroy', $minuman->id) }}"
                                method="POST"
                            >
                                @csrf @method('DELETE')
                                <button
                                    type="button"
                                    class="delete-btn bg-red-600 text-white px-3 py-1 rounded hover:bg-red-800 transition"
                                >
                                    🗑️ Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr class="text-center">
                        <td colspan="6" class="p-3 border text-gray-500">
                            Belum ada minuman
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll(".delete-btn").forEach((button) => {
            button.addEventListener("click", function () {
                const form = this.closest("form");
                Swal.fire({
                    title: "Yakin ingin hapus?",
                    text: "Data minuman akan dihapus permanen!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Ya, hapus!",
                    cancelButtonText: "Batal",
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
</div>
@endsection
