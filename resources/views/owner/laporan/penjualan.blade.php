@extends('layouts.app')

@section('content')
<div class="p-6 bg-white rounded-xl shadow">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold text-orange-700">🍹 Laporan Penjualan Minuman</h2>

        <!-- Dropdown pilih tahun -->
        <form method="GET" action="{{ route('owner.penjualan') }}">
            <select name="year" onchange="this.form.submit()" class="border rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-500">
                @foreach ($availableYears as $year)
                    <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-700">Total Minuman Terjual Tahun {{ $selectedYear }}</h3>
        <p class="text-3xl font-bold text-orange-600">{{ number_format($totalTerjual, 0, ',', '.') }}</p>
    </div>

    <canvas id="penjualanChart" height="100"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('penjualanChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($bulanLabels),
        datasets: [{
            label: 'Jumlah Minuman Terjual',
            data: @json($penjualanData),
            backgroundColor: 'rgba(249, 115, 22, 0.5)',
            borderColor: 'rgb(249, 115, 22)',
            borderWidth: 2
        }]
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
