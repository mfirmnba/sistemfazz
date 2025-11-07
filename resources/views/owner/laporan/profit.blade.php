@extends('layouts.app')

@section('content')
<div class="p-6 bg-white rounded-xl shadow">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold text-green-700">💹 Laporan Profit</h2>

        <!-- 🔹 Dropdown Pilih Tahun -->
        <form method="GET" action="{{ route('owner.profit') }}">
            <select name="year" onchange="this.form.submit()" class="border rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                @foreach ($availableYears as $year)
                    <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-700">Total Profit Tahun {{ $selectedYear }}</h3>
        <p class="text-3xl font-bold text-green-600">
            Rp {{ number_format($totalProfit, 0, ',', '.') }}
        </p>
    </div>

    <canvas id="profitChart" height="100"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('profitChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($bulanLabels),
        datasets: [{
            label: 'Profit Bulanan (Rp)',
            data: @json($profitData),
            backgroundColor: 'rgba(16, 185, 129, 0.5)',
            borderColor: 'rgb(16, 185, 129)',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        },
        plugins: {
            legend: { display: true, position: 'top' }
        }
    }
});
</script>
@endsection
