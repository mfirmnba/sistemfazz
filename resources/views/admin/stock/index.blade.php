@extends('layouts.admin') @section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">📦 Daftar Stock Bahan</h1>

    <a
        href="{{ route('admin.stock.create') }}"
        class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block"
        >+ Tambah Stock</a
    >

    @if(session('success'))
    <div class="bg-green-200 text-green-800 p-2 rounded mb-4">
        {{ session("success") }}
    </div>
    @endif

    <table class="w-full border">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-2 border">Nama Bahan</th>
                <th class="p-2 border">Jumlah</th>
                <th class="p-2 border">Satuan</th>
                <th class="p-2 border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stocks as $stock)
            <tr>
                <td class="p-2 border">{{ $stock->nama_bahan }}</td>
                <td class="p-2 border">{{ $stock->jumlah }}</td>
                <td class="p-2 border">{{ $stock->satuan }}</td>
                <td class="p-2 border">
                    <a
                        href="{{ route('admin.stock.edit', $stock->id) }}"
                        class="bg-yellow-500 text-white px-2 py-1 rounded"
                        >Edit</a
                    >
                    <form
                        action="{{ route('admin.stock.destroy', $stock->id) }}"
                        method="POST"
                        class="inline"
                    >
                        @csrf @method('DELETE')
                        <button
                            onclick="return confirm('Yakin hapus?')"
                            class="bg-red-500 text-white px-2 py-1 rounded"
                        >
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center p-4">
                    Belum ada stock bahan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $stocks->links() }}
    </div>
</div>
@endsection
