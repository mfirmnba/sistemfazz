@extends('layouts.app')

@section('content')
<div class="p-6 bg-white rounded-xl shadow">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold text-blue-700">📦 Laporan Stok Tersedia</h2>

        <!-- Dropdown pilih tahun -->
        <form method="GET" action="{{ route('owner.stock') }}">
            <select name="year" onchange="this.form.submit()" class="border rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @foreach ($availableYears as $year)
                    <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-700">Total Stok Tahun {{ $selectedYear }}</h3>
        <p class="text-3xl font-bold text-blue-600">{{ number_format($totalStock, 0, ',', '.') }}</p>
    </div>

    <canvas id="stockChart" height="100"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('stockChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($bulanLabels),
        datasets: [
            {
                label: 'Stok Masuk',
                data: @json($masukData),
                backgroundColor: 'rgba(37, 99, 235, 0.5)',
                borderColor: 'rgb(37, 99, 235)',
                borderWidth: 2
            },
            {
                label: 'Stok Keluar (Penjualan)',
                data: @json($keluarData),
                backgroundColor: 'rgba(239, 68, 68, 0.5)',
                borderColor: 'rgb(239, 68, 68)',
                borderWidth: 2
            }
        ]
    },
    options: {
        responsive: true,
        scales: { y: { beginAtZero: true } },
        plugins: {
            legend: { display: true, position: 'top' }
        }
    }
});
</script>
@endsection
