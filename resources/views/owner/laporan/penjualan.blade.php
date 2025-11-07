@extends('layouts.app')

@section('content')
<div class="p-6 bg-white rounded-xl shadow">
    <h2 class="text-2xl font-bold text-orange-700 mb-4">🧃 Laporan Penjualan</h2>

    <p class="text-gray-600 mb-2">Tanggal: <strong>{{ $today }}</strong></p>
    <p class="text-lg font-semibold mb-4">Total Cup Terjual: <span class="text-orange-600">{{ number_format($totalCupTerjual) }}</span></p>

    <div class="grid md:grid-cols-2 gap-6">
        <!-- Penjualan per driver -->
        <div>
            <h3 class="font-bold text-gray-700 mb-2">📦 Penjualan per Driver</h3>
            <table class="w-full border border-gray-200 text-sm">
                <thead class="bg-gray-100">
                    <tr class="text-left">
                        <th class="p-2 border">Driver</th>
                        <th class="p-2 border">Cup</th>
                        <th class="p-2 border">Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penjualanPerDriver as $d)
                    <tr class="hover:bg-gray-50">
                        <td class="p-2 border">{{ $d->driver }}</td>
                        <td class="p-2 border text-center">{{ $d->total_cup }}</td>
                        <td class="p-2 border text-right">Rp {{ number_format($d->pendapatan, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Penjualan per minuman -->
        <div>
            <h3 class="font-bold text-gray-700 mb-2">🥤 Penjualan per Minuman</h3>
            <table class="w-full border border-gray-200 text-sm">
                <thead class="bg-gray-100">
                    <tr class="text-left">
                        <th class="p-2 border">Nama Minuman</th>
                        <th class="p-2 border">Total Terjual</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penjualanMinuman as $m)
                    <tr class="hover:bg-gray-50">
                        <td class="p-2 border">{{ $m->minuman->nama_minuman ?? '-' }}</td>
                        <td class="p-2 border text-center">{{ $m->total_qty }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
