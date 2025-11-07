@extends('layouts.app')

@section('content')
<div class="p-6 bg-white rounded-xl shadow">
    <h2 class="text-2xl font-bold text-blue-700 mb-4">📦 Laporan Stok</h2>

    {{-- 🔹 Total Stok Digunakan --}}
    <h3 class="text-lg font-semibold text-gray-700">
        Total Pemakaian Stok {{ $selectedMonth ? 'Bulan '.$bulanLabels[$selectedMonth-1].' ' : 'Tahun ' }}{{ $selectedYear }}
    </h3>
    <p class="text-2xl font-bold text-blue-600 mb-4">
        {{ number_format($totalStockUsed, 0, ',', '.') }} unit
    </p>

    {{-- 🔹 Grafik Stok --}}
    <canvas id="stockChart" height="100"></canvas>
</div>

{{-- 🔹 Daftar Stok yang Digunakan --}}
<div class="bg-white p-6 rounded-xl shadow mt-6">
        {{-- 🔹 Filter Tahun & Bulan --}}
    <form method="GET" action="{{ route('owner.laporan.stock') }}" class="flex flex-wrap items-center gap-3 mb-6">
        <div>
            <label for="year" class="mr-2 font-semibold">Pilih Tahun:</label>
            <select name="year" id="year" onchange="this.form.submit()" class="border rounded p-2">
                @foreach ($availableYears as $year)
                    <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endforeach
            </select>
        </div>
            {{-- 🔹 Total Stok Digunakan --}}
    <h3 class="text-lg font-semibold text-gray-700">
        Total Pemakaian Stok {{ $selectedMonth ? 'Bulan '.$bulanLabels[$selectedMonth-1].' ' : 'Tahun ' }}{{ $selectedYear }}
    </h3>
    <p class="text-2xl font-bold text-blue-600 mb-4">
        {{ number_format($totalStockUsed, 0, ',', '.') }} unit
    </p>

        <div>
            <label for="month" class="mr-2 font-semibold">Pilih Bulan:</label>
            <select name="month" id="month" onchange="this.form.submit()" class="border rounded p-2">
                <option value="">Semua Bulan</option>
                @foreach ($bulanLabels as $index => $bulan)
                    <option value="{{ $index+1 }}" {{ $selectedMonth == $index+1 ? 'selected' : '' }}>
                        {{ $bulan }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>
    <h2 class="text-xl font-semibold text-gray-800 mb-4">📋 Daftar Stok yang Digunakan</h2>

    <table class="w-full text-sm border border-gray-200 rounded-lg">
        <thead class="bg-gray-100 text-gray-700">
            <tr class="text-center font-semibold">
                <th class="p-3 border w-12">#</th>
                <th class="p-3 border text-left">Nama Bahan</th>
                <th class="p-3 border text-center">Total Digunakan</th>
                <th class="p-3 border text-center">Satuan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stockUsedList as $i => $item)
                <tr class="hover:bg-gray-50 even:bg-gray-50/50">
                    <td class="p-3 border text-center">{{ $i + 1 }}</td>
                    <td class="p-3 border text-left">{{ $item->stock->nama_bahan ?? '-' }}</td>
                    <td class="p-3 border text-center font-semibold text-blue-600">
                        {{ $item->total_digunakan }}
                    </td>
                    <td class="p-3 border text-center">{{ $item->stock->satuan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-4 text-center text-gray-500 italic">
                        Belum ada data penggunaan stok untuk periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- 🔹 ChartJS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('stockChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($bulanLabels),
        datasets: [{
            label: 'Stok Digunakan (per Bulan)',
            data: @json($stockData),
            backgroundColor: 'rgba(59, 130, 246, 0.5)',
            borderColor: 'rgb(59, 130, 246)',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        scales: { y: { beginAtZero: true } }
    }
});
</script>
@endsection
