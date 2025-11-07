@extends('layouts.app')

@section('content')
<div class="p-6 bg-white rounded-xl shadow">
    <h2 class="text-2xl font-bold text-purple-700 mb-4">📦 Laporan Stok Bahan</h2>

    <p class="text-lg font-semibold mb-4">Total Stok: <span class="text-purple-600">{{ number_format($totalStock, 0, ',', '.') }}</span></p>

    <div class="overflow-x-auto mb-6">
        <table class="w-full border border-gray-200 text-sm">
            <thead class="bg-gray-100">
                <tr class="text-left">
                    <th class="p-2 border">Nama Bahan</th>
                    <th class="p-2 border">Jumlah</th>
                    <th class="p-2 border">Terpakai Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stocks as $s)
                <tr class="hover:bg-gray-50">
                    <td class="p-2 border">{{ $s->nama_bahan }}</td>
                    <td class="p-2 border text-center">{{ $s->jumlah }}</td>
                    <td class="p-2 border text-center">{{ $s->terpakai_total ?? 0 }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <canvas id="stokChart" height="100"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctxStock = document.getElementById('stokChart').getContext('2d');
new Chart(ctxStock, {
    type: 'bar',
    data: {
        labels: @json($stokMingguan->pluck('tanggal')),
        datasets: [{
            label: 'Pemakaian Stok (7 Hari Terakhir)',
            data: @json($stokMingguan->pluck('total_stok')),
            backgroundColor: 'rgba(147, 51, 234, 0.5)',
            borderColor: 'rgb(147, 51, 234)',
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
