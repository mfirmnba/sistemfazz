@extends('layouts.app')

@section('content')
<div class="p-6 bg-white rounded-xl shadow">
    <h2 class="text-2xl font-bold text-blue-700 mb-4">💰 Laporan Omset</h2>

    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-700">Total Omset</h3>
        <p class="text-3xl font-bold text-blue-600">Rp {{ number_format($totalOmset, 0, ',', '.') }}</p>
    </div>

    <canvas id="omsetChart" height="100"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctxOmset = document.getElementById('omsetChart').getContext('2d');
new Chart(ctxOmset, {
    type: 'line',
    data: {
        labels: @json($bulanLabels),
        datasets: [{
            label: 'Omset Bulanan (Rp)',
            data: @json($omsetData),
            borderColor: 'rgb(59,130,246)',
            backgroundColor: 'rgba(59,130,246,0.2)',
            borderWidth: 2,
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        scales: { y: { beginAtZero: true } }
    }
});
</script>
@endsection
