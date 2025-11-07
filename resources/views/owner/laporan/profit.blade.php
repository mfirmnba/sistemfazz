@extends('layouts.app')

@section('content')
<div class="p-6 bg-white rounded-xl shadow">
    <h2 class="text-2xl font-bold text-green-700 mb-4">💹 Laporan Profit</h2>

    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-700">Total Profit</h3>
        <p class="text-3xl font-bold text-green-600">Rp {{ number_format($totalProfit, 0, ',', '.') }}</p>
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
        scales: { y: { beginAtZero: true } }
    }
});
</script>
@endsection
