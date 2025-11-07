@extends('layouts.app')

@section('content')
<div class="p-6 bg-white rounded-xl shadow">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold text-blue-700">📊 Laporan Omset</h2>

        <!-- Dropdown pilih tahun -->
        <form method="GET" action="{{ route('owner.omset') }}">
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
        <h3 class="text-lg font-semibold text-gray-700">Total Omset Tahun {{ $selectedYear }}</h3>
        <p class="text-3xl font-bold text-blue-600">
            Rp {{ number_format($totalOmset, 0, ',', '.') }}
        </p>
    </div>

    <canvas id="omsetChart" height="100"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('omsetChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($bulanLabels),
        datasets: [{
            label: 'Omset Bulanan (Rp)',
            data: @json($omsetData),
            backgroundColor: 'rgba(59, 130, 246, 0.3)',
            borderColor: 'rgb(59, 130, 246)',
            borderWidth: 3,
            fill: true,
            tension: 0.3
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
