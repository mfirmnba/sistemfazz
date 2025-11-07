@extends('layouts.app')

@section('content')
<div class="p-6 bg-white rounded-xl shadow">
    <h2 class="text-2xl font-bold text-blue-700 mb-4">📦 Laporan Stok</h2>

    <form method="GET" action="{{ route('owner.laporan.stock') }}" class="mb-6">
        <label for="year" class="mr-2 font-semibold">Pilih Tahun:</label>
        <select name="year" id="year" onchange="this.form.submit()" class="border rounded p-2">
            @foreach ($availableYears as $year)
                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
            @endforeach
        </select>
    </form>

    <h3 class="text-lg font-semibold text-gray-700">Total Pemakaian Stok Tahun {{ $selectedYear }}</h3>
    <p class="text-2xl font-bold text-blue-600 mb-4">{{ number_format($totalStockUsed, 0, ',', '.') }} unit</p>

    <canvas id="stockChart" height="100"></canvas>
</div>

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
