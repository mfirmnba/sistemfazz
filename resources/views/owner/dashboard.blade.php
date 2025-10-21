@extends('layouts.app') @section('content')
<div class="container mx-auto p-6">
    <!-- Judul -->
    <div class="mb-8">
        <h1
            class="text-5xl md:text-2xl font-extrabold mb-4 text-gray-800 transform transition duration-700 hover:scale-105 animate-fade-up"
        >
            📊 Dashboard
        </h1>
        <p
            class="mb-8 text-lg md:text-1xl text-gray-600 animate-fade-up delay-100"
        >
            Selamat datang,
            <b class="text-gray-800">{{ auth()->user()->name }}</b> 👋 Anda
            login sebagai <b class="text-blue-600">Owner</b>.
        </p>
        <!-- Tailwind Animations -->
        <style>
            @keyframes fade-up {
                0% {
                    opacity: 0;
                    transform: translateY(20px);
                }
                100% {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            .animate-fade-up {
                animation: fade-up 0.8s ease forwards;
            }
            .delay-100 {
                animation-delay: 0.1s;
            }
        </style>
    </div>

    <!-- Statistik Ringkas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white p-4 shadow rounded-lg">
            <p class="text-gray-500">Profit</p>
            <h2 class="text-2xl font-bold">
                Rp{{ number_format($totalKeuntunganSemua ?? 0, 0, ",", ".") }}
            </h2>
            <a href="#" class="text-blue-500 text-sm">View data →</a>
        </div>
        <div class="bg-white p-4 shadow rounded-lg">
            <p class="text-gray-500">Omset</p>
            <h2 class="text-2xl font-bold">
                Rp{{ number_format($totalPendapatan ?? 0, 0, ",", ".") }}
            </h2>
            <a href="#" class="text-blue-500 text-sm">View data →</a>
        </div>

        <div class="bg-white p-4 shadow rounded-lg">
            <p class="text-gray-500">Minuman Terjual</p>
            <h2 class="text-2xl font-bold">{{ $totalOrdersAllTime ?? 0 }}</h2>
            <a href="#" class="text-blue-500 text-sm">View data →</a>
        </div>
        <div class="bg-white p-4 shadow rounded-lg">
            <p class="text-gray-500">Stock Tersedia</p>
            <h2 class="text-2xl font-bold">{{ $totalStock ?? 0 }}</h2>
            <a href="#" class="text-blue-500 text-sm">View data →</a>
        </div>
    </div>

    <!-- Data User -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10 mt-6">
        @foreach(['Admin' => $adminUsers, 'Driver' => $driverUsers, 'Produksi'
        => $produksiUsers] as $role => $users)
        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-lg font-semibold mb-4">
                @if($role == 'Admin') 👨‍💼 @elseif($role == 'Driver') 🚗 @else 🍽️
                @endif Data {{ $role }}
            </h2>
            <div class="overflow-x-auto">
                <table
                    class="w-full text-sm text-left border border-gray-200 rounded-lg"
                >
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="p-2 border">#</th>
                            <th class="p-2 border">Nama</th>
                            <th class="p-2 border">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $key => $user)
                        <tr class="hover:bg-gray-50">
                            <td class="p-2 border">{{ $key + 1 }}</td>
                            <td class="p-2 border">{{ $user->name }}</td>
                            <td class="p-2 border">{{ $user->email }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Chart: Keuntungan Minuman -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10 mt-6 ">
        <div class="mt-8 bg-white p-4 rounded-xl shadow">
            <h2 class="text-lg font-semibold mb-3 text-gray-800">
                💰 Grafik Keuntungan Minuman
            </h2>
            <div class="h-80">
                <!-- tinggi dibatasi agar tidak terlalu besar -->
                <canvas id="keuntunganChart"></canvas>
            </div>
        </div>
        <div class="p-4 bg-white rounded-lg shadow-md">
            <h2 class="text-lg font-semibold mb-3">
                📊 Penjualan vs Keuntungan Bulanan
            </h2>
            <div style="height: 320px">
                <canvas id="penjualanProfitChart"></canvas>
            </div>
        </div>
        <!-- 📊 Grafik Penjualan Minuman Terlaris -->
        <div class="bg-white p-6 rounded-xl shadow mt-8">
            <h2 class="text-xl font-semibold mb-4">
                🥤 Penjualan Minuman Terlaris
            </h2>
            <canvas id="penjualanMinumanChart" height="120"></canvas>
        </div>
    </div>

    <!-- Chart Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10 mt-6">
        <!-- Bar Chart: Bahan Terpakai Hari Ini -->
        <div
            class="bg-white p-4 shadow rounded-lg transform transition duration-700 hover:scale-105 animate-fade-in"
        >
            <h3 class="text-lg font-semibold mb-3">Stock Terpakai</h3>
            <canvas id="barChart"></canvas>
        </div>

        <!-- Doughnut Chart: Stock -->
        <div
            class="bg-white p-4 shadow rounded-lg text-center transform transition duration-700 hover:scale-105 animate-fade-in delay-100"
        >
            <h3 class="text-lg font-semibold mb-3">Stock</h3>
            <canvas id="doughnutChart" class="mx-auto"></canvas>
            <p class="mt-3 text-gray-600">
                Stock terpakai hari ini: {{ $bahanTerpakaiHariIni ?? 0 }}
            </p>
        </div>

        <!-- Line Chart: Total Cup Terjual -->
        <div
            class="bg-white p-4 shadow rounded-lg transform transition duration-700 hover:scale-105 animate-fade-in delay-200"
        >
            <h3 class="text-lg font-semibold mb-3">Minuman Terjual</h3>
            <canvas id="lineChart"></canvas>
            <div class="flex justify-around mt-2 text-sm">
                <span class="text-green-600"
                    >Expired: {{ $totalExpired ?? 0 }}</span
                >
                <span class="text-red-600"
                    >Tumpah: {{ $totalTumpah ?? 0 }}</span
                >
            </div>
            <div class="mt-4 flex justify-between text-sm">
                <span>
                    Rp
                    {{
                        number_format($totalPendapatanHariIni ?? 0, 0, ",", ".")
                    }}
                    Total
                </span>
                <span>{{ $totalCupTerjual ?? 0 }} Cup Terjual</span>
            </div>
        </div>
        <!-- Tailwind Animations -->
        <style>
            @keyframes fade-in {
                0% {
                    opacity: 0;
                    transform: translateY(20px);
                }
                100% {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            .animate-fade-in {
                animation: fade-in 0.8s ease forwards;
            }
            .delay-100 {
                animation-delay: 0.1s;
            }
            .delay-200 {
                animation-delay: 0.2s;
            }
        </style>
    </div>

    <!-- Statistik Ringkas Warna -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10 mt-6">
        <div
            class="bg-green-500 text-white rounded-xl p-6 shadow hover:shadow-lg transition"
        >
            <h2 class="text-lg font-semibold">Pendapatan Hari Ini</h2>
            <p class="text-2xl font-bold mt-2">
                Rp
                {{ number_format($totalPendapatanHariIni ?? 0, 0, ",", ".") }}
            </p>
        </div>
        <div
            class="bg-purple-500 text-white rounded-xl p-6 shadow hover:shadow-lg transition"
        >
            <h2 class="text-lg font-semibold">Cup Terjual Hari Ini</h2>
            <p class="text-2xl font-bold mt-2">
                {{ $totalCupTerjual ?? 0 }} cup
            </p>
        </div>
        <div
            class="bg-yellow-500 text-white rounded-xl p-6 shadow hover:shadow-lg transition"
        >
            <h2 class="text-lg font-semibold">Expired Hari Ini</h2>
            <p class="text-2xl font-bold mt-2">{{ $totalExpired ?? 0 }} cup</p>
        </div>
        <div
            class="bg-red-500 text-white rounded-xl p-6 shadow hover:shadow-lg transition"
        >
            <h2 class="text-lg font-semibold">Tumpah Hari Ini</h2>
            <p class="text-2xl font-bold mt-2">{{ $totalTumpah ?? 0 }} cup</p>
        </div>
    </div> 

    <div class="p-4 bg-white shadow rounded-lg mt-6">
    <h3 class="text-lg font-semibold mb-2">Grafik Pendapatan Harian per Driver</h3>
    <canvas id="chartPendapatanDriverHarian" height="120"></canvas>
    </div>

    <!-- 🥤 Minuman Terjual Hari Ini per Driver -->
    <div class="bg-white p-6 rounded-xl shadow mt-10 mb-10">
        <h2 class="text-xl font-semibold mb-4">
            🥤 Minuman Terjual Hari Ini per Driver
        </h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border border-gray-200 rounded-lg">
                <thead class="bg-gray-100 text-gray-700">
                    <tr class="text-center font-semibold">
                        <th class="p-3 border w-12">#</th>
                        <th class="p-3 border text-left">Nama Driver</th>
                        <th class="p-3 border text-left">Nama Minuman</th>
                        <th class="p-3 border text-center">Jumlah Terjual</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp @forelse($minumanTerjualPerDriverToday
                    as $driverId => $items) @foreach($items as $item)
                    <tr class="hover:bg-gray-50 even:bg-gray-50/50">
                        <td class="p-3 border text-center">{{ $no++ }}</td>
                        <td class="p-3 border text-left">
                            {{ $item->user->name ?? '-' }}
                        </td>
                        <td class="p-3 border text-left">
                            {{ $item->minuman->nama ?? '-' }}
                        </td>
                        <td
                            class="p-3 border text-center font-semibold text-blue-600"
                        >
                            {{ $item->total_terjual }}
                        </td>
                    </tr>
                    @endforeach @empty
                    <tr>
                        <td
                            colspan="4"
                            class="p-4 text-center text-gray-500 italic"
                        >
                            Belum ada data penjualan hari ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>

                @if($minumanTerjualPerDriverToday &&
                collect($minumanTerjualPerDriverToday)->flatten()->count() > 0)
                <tfoot class="bg-gray-100 text-gray-800 font-bold">
                    <tr>
                        <td colspan="3" class="p-3 border text-left">
                            TOTAL MINUMAN TERJUAL
                        </td>
                        <td class="p-3 border text-center text-green-700">
                            {{ collect($minumanTerjualPerDriverToday)->flatten()->sum('total_terjual') }}
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    <!-- Rincian Pendapatan Per User Hari Ini -->
    <div class="bg-white p-6 rounded-xl shadow mb-10 mt-6">
        <h2 class="text-lg font-semibold mb-4">
            📅 Pendapatan Rider (Hari Ini)
        </h2>
        @if($penjualanPerUserToday->isEmpty())
        <div class="bg-yellow-100 text-yellow-800 p-4 rounded">
            Belum ada data penjualan hari ini.
        </div>
        @else
        <div class="overflow-x-auto">
            <table
                class="w-full text-sm text-left border border-gray-200 rounded-lg"
            >
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="p-2 border">#</th>
                        <th class="p-2 border">Nama User</th>
                        <th class="p-2 border">Email</th>
                        <th class="p-2 border">Total Cup Terjual</th>
                        <th class="p-2 border">Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penjualanPerUserToday as $key => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="p-2 border">{{ $key + 1 }}</td>
                        <td class="p-2 border">
                            {{ $item->user?->name ?? 'User dihapus' }}
                        </td>
                        <td class="p-2 border">
                            {{ $item->user?->email ?? '-' }}
                        </td>
                        <td class="p-2 border font-bold text-green-600">
                            {{ $item->total_cup }} cup
                        </td>
                        <td class="p-2 border font-bold text-blue-600">
                            Rp
                            {{ number_format($item->pendapatan ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    <!-- Rincian Pendapatan Per User All Time -->
    <div class="bg-white p-6 rounded-xl shadow mb-10">
        <h2 class="text-lg font-semibold mb-4">
            📊 Pendapatan Rider (All Time)
        </h2>
        @if($penjualanPerUserAllTime->isEmpty())
        <div class="bg-yellow-100 text-yellow-800 p-4 rounded">
            Belum ada data penjualan.
        </div>
        @else
        <div class="overflow-x-auto">
            <table
                class="w-full text-sm text-left border border-gray-200 rounded-lg"
            >
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="p-2 border">#</th>
                        <th class="p-2 border">Nama User</th>
                        <th class="p-2 border">Email</th>
                        <th class="p-2 border">Total Cup Terjual</th>
                        <th class="p-2 border">Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penjualanPerUserAllTime as $key => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="p-2 border">{{ $key + 1 }}</td>
                        <td class="p-2 border">
                            {{ $item->user?->name ?? 'User dihapus' }}
                        </td>
                        <td class="p-2 border">
                            {{ $item->user?->email ?? '-' }}
                        </td>
                        <td class="p-2 border font-bold text-green-600">
                            {{ $item->total_cup }} cup
                        </td>
                        <td class="p-2 border font-bold text-blue-600">
                            Rp
                            {{ number_format($item->pendapatan ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    <!-- Grafik Stok Terpakai -->
    <div class="bg-white p-4 shadow rounded-lg mb-8 mt-6">
        <h2 class="text-xl font-semibold mb-2">📊 Grafik Stock</h2>
        <canvas id="stokTerpakaiChart" class="w-full h-64"></canvas>
    </div>

    <!-- Rincian Stock -->
    <div class="bg-white p-6 rounded-xl shadow mb-10">
        <h2 class="text-lg font-semibold mb-4">📦 Rincian Total Stock</h2>
        <div class="overflow-x-auto">
            <table
                class="w-full text-sm text-left border border-gray-200 rounded-lg"
            >
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="p-2 border">#</th>
                        <th class="p-2 border">Nama Bahan</th>
                        <th class="p-2 border">Jumlah</th>
                        <th class="p-2 border">Satuan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stocks as $key => $stock)
                    <tr class="hover:bg-gray-50">
                        <td class="p-2 border">{{ $key + 1 }}</td>
                        <td class="p-2 border">{{ $stock->nama_bahan }}</td>
                        <td class="p-2 border">{{ $stock->jumlah }}</td>
                        <td class="p-2 border">{{ $stock->satuan }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-2 border text-center">
                            Belum ada stock
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Rincian Produksi Hari Ini -->
    <div class="bg-white p-6 rounded-xl shadow">
        <h2 class="text-lg font-semibold mb-4">
            📋 Rincian Bahan Terpakai Hari Ini
        </h2>
        @if($laporanToday->isEmpty())
        <div class="bg-yellow-100 text-yellow-800 p-4 rounded">
            Belum ada produksi hari ini.
        </div>
        @else
        <div class="overflow-x-auto">
            <table
                class="w-full text-sm text-left border border-gray-200 rounded-lg"
            >
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="p-2 border">#</th>
                        <th class="p-2 border">Nama Bahan</th>
                        <th class="p-2 border">Jumlah Digunakan</th>
                        <th class="p-2 border">Satuan</th>
                        <th class="p-2 border">User Produksi</th>
                        <th class="p-2 border">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($laporanToday as $key => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="p-2 border">{{ $key + 1 }}</td>
                        <td class="p-2 border">
                            {{ $item->stock?->nama_bahan ?? 'Bahan dihapus' }}
                        </td>
                        <td class="p-2 border">
                            {{ $item->jumlah_digunakan }}
                        </td>
                        <td class="p-2 border">
                            {{ $item->stock?->satuan ?? '-' }}
                        </td>
                        <td class="p-2 border">
                            {{ $item->user?->name ?? 'Tidak diketahui' }}
                        </td>
                        <td class="p-2 border">{{ $item->tanggal }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    <!-- Rincian Minuman Produksi (Hanya Tampilan) -->
    <div class="bg-white p-6 rounded-xl shadow mt-8">
        <h2 class="text-lg font-semibold mb-4">🍹 Rincian Minuman Produksi</h2>
        @if($minumans->isEmpty())
        <div class="bg-yellow-100 text-yellow-800 p-4 rounded">
            Belum ada minuman.
        </div>
        @else
        <div class="overflow-x-auto">
            <table
                class="w-full text-sm text-left border border-gray-200 rounded-lg"
            >
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="p-2 border">#</th>
                        <th class="p-2 border">Nama Minuman</th>
                        <th class="p-2 border">Harga</th>
                        <th class="p-2 border">Stok Hari Ini</th>
                        <th class="p-2 border">Stok Besok</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($minumans as $key => $minuman)
                    <tr class="hover:bg-gray-50 text-center">
                        <td class="p-2 border">{{ $key + 1 }}</td>
                        <td class="p-2 border">{{ $minuman->nama }}</td>
                        <td class="p-2 border">
                            Rp {{ number_format($minuman->harga, 0, ',', '.') }}
                        </td>
                        <td class="p-2 border">
                            {{ $minuman->stok_hari_ini }}
                        </td>
                        <td class="p-2 border">{{ $minuman->stok_besok }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

    <!-- 📊 Data Keuntungan dan Margin -->
    <div class="bg-white p-6 rounded-xl shadow mt-8">
        <h2 class="text-xl font-semibold mb-4">
            💰 Keuntungan & Margin Minuman
        </h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border border-gray-200 rounded-lg">
                <thead class="bg-gray-100 text-gray-700">
                    <tr class="text-center font-semibold">
                        <th class="p-3 border">#</th>
                        <th class="p-3 border text-left">Nama Minuman</th>
                        <th class="p-3 border text-right">Harga Jual</th>
                        <th class="p-3 border text-right">HPP</th>
                        <th class="p-3 border text-right">Keuntungan / Cup</th>
                        <th class="p-3 border text-center">Margin (%)</th>
                        <th class="p-3 border">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($minumans as $key => $m)
                    <tr class="hover:bg-gray-50 even:bg-gray-50/50">
                        <td class="p-3 border text-center">{{ $key + 1 }}</td>
                        <td class="p-3 border text-left">{{ $m->nama }}</td>
                        <td class="p-3 border text-right">
                            Rp {{ number_format($m->harga, 0, ',', '.') }}
                        </td>
                        <td class="p-3 border text-right">
                            Rp {{ number_format($m->hpp ?? 0, 0, ',', '.') }}
                        </td>
                        <td
                            class="p-3 border text-right font-semibold text-green-600"
                        >
                            Rp {{ number_format($m->keuntungan, 0, ',', '.') }}
                        </td>
                        <td class="p-3 border text-center">
                            @php $margin = $m->margin ?? 0; $color = $margin >=
                            50 ? 'text-green-600 font-bold' : ($margin >= 30 ?
                            'text-yellow-600 font-semibold' : 'text-red-600
                            font-semibold'); @endphp
                            <span class="{{ $color }}">
                                {{ number_format($margin, 2, ",", ".") }}%
                            </span>
                        </td>
                        <td class="p-3 border text-center">
                            <a
                                href="{{ route('owner.minuman.edit', $m->id) }}"
                                class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-700 transition"
                            >
                                ✏️ Edit HPP
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td
                            colspan="7"
                            class="p-4 text-center text-gray-500 italic"
                        >
                            Belum ada data minuman.
                        </td>
                    </tr>
                    @endforelse
                </tbody>

                @if($minumans->count() > 0)
                <tfoot class="bg-gray-100 text-gray-800 font-bold">
                    <tr>
                        <td colspan="4" class="p-2 border text-left">
                            TOTAL KEUNTUNGAN
                        </td>
                        <td class="p-3 border text-left text-green-700">
                            Rp
                            {{ number_format($minumans->sum->keuntungan, 0, ',', '.') }}
                        </td>
                        <td colspan="3" class="p-3 border"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="p-3 border text-left">
                            RATA-RATA MARGIN
                        </td>
                        <td colspan="3" class="p-3 border text-left">
                            {{ number_format($minumans->avg->margin, 2, ',', '.') 

                            }}%
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

<div class="container mx-auto p-6 space-y-8">

    <h1 class="text-2xl font-bold mb-6 text-gray-800">
        Dashboard Driver - Statistik Penjualan & Pendapatan
    </h1>

    {{-- ====================== --}}
    {{-- 1. Grafik Cup Terjual per Driver --}}
    {{-- ====================== --}}
    <div class="bg-white shadow rounded-xl p-6">
        <h2 class="text-lg font-semibold mb-3 text-gray-700">
            ☕ Jumlah Cup Terjual per Driver
        </h2>
        <div class="relative h-80">
            <canvas id="chartCupDriver"></canvas>
        </div>
    </div>

    {{-- ====================== --}}
    {{-- 2. Grafik Pendapatan per Driver (Line Harian) --}}
    {{-- ====================== --}}
    <div class="bg-white shadow rounded-xl p-6">
        <h2 class="text-lg font-semibold mb-3 text-gray-700">
            💰 Pendapatan Harian per Driver
        </h2>
        <div class="relative h-96">
            <canvas id="chartPendapatanDriverHarian"></canvas>
        </div>
    </div>

    {{-- ====================== --}}
    {{-- 3. Grafik Pendapatan Total per Driver --}}
    {{-- ====================== --}}
    <div class="bg-white shadow rounded-xl p-6">
        <h2 class="text-lg font-semibold mb-3 text-gray-700">
            📊 Total Pendapatan per Driver
        </h2>
        <div class="relative h-80">
            <canvas id="chartPendapatanTotalDriver"></canvas>
        </div>
    </div>

</div>
@section('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {

    // =========================
    // 1. Bar Chart: Cup Terjual per Driver
    // =========================
    const ctxCupDriver = document.getElementById('chartCupDriver').getContext('2d');
    const cupDriverLabels = @json(array_keys($cupTerjualPerDriver ?? []));
    const cupDriverData = @json(array_values($cupTerjualPerDriver ?? []));

    new Chart(ctxCupDriver, {
        type: 'bar',
        data: {
            labels: cupDriverLabels,
            datasets: [{
                label: 'Cup Terjual',
                data: cupDriverData,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderRadius: 6,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Jumlah Cup Terjual per Driver'
                },
                tooltip: {
                    callbacks: {
                        label: ctx => `${ctx.parsed.y} Cup`
                    }
                }
            },
            scales: {
                y: { beginAtZero: true, title: { display: true, text: 'Jumlah Cup' } }
            }
        }
    });

    // =========================
    // 2. Line Chart: Pendapatan Harian per Driver
    // =========================
    const ctxPendapatanDriver = document.getElementById('chartPendapatanDriverHarian').getContext('2d');
    const pendapatanData = @json($pendapatanDriverHarian ?? []);

    const warna = [
        'rgba(54, 162, 235, 0.8)',
        'rgba(255, 99, 132, 0.8)',
        'rgba(75, 192, 192, 0.8)',
        'rgba(255, 206, 86, 0.8)',
        'rgba(153, 102, 255, 0.8)',
        'rgba(255, 159, 64, 0.8)',
    ];

    const datasetsPendapatan = Object.entries(pendapatanData).map(([driver, items], i) => {
        const sorted = items.sort((a, b) => new Date(a.tanggal) - new Date(b.tanggal));
        return {
            label: driver,
            data: sorted.map(x => ({ x: x.tanggal, y: x.total_pendapatan })),
            borderColor: warna[i % warna.length],
            backgroundColor: warna[i % warna.length],
            borderWidth: 2,
            fill: false,
            tension: 0.3,
            pointRadius: 3,
        };
    });

    new Chart(ctxPendapatanDriver, {
        type: 'line',
        data: { datasets: datasetsPendapatan },
        options: {
            responsive: true,
            parsing: false,
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'day',
                        tooltipFormat: 'dd MMM yyyy',
                        displayFormats: { day: 'dd MMM' }
                    },
                    title: { display: true, text: 'Tanggal' }
                },
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Pendapatan (Rp)' },
                    ticks: { callback: v => 'Rp ' + v.toLocaleString('id-ID') }
                }
            },
            plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: {
                        label: ctx => `${ctx.dataset.label}: Rp ${ctx.parsed.y.toLocaleString('id-ID')}`
                    }
                }
            }
        }
    });

    // =========================
    // 3. Bar Chart: Pendapatan Total per Driver
    // =========================
    const ctxTotalPendapatan = document.getElementById('chartPendapatanTotalDriver').getContext('2d');
    const totalDriverLabels = @json(array_keys($totalPendapatanPerDriver ?? []));
    const totalDriverData = @json(array_values($totalPendapatanPerDriver ?? []));

    new Chart(ctxTotalPendapatan, {
        type: 'bar',
        data: {
            labels: totalDriverLabels,
            datasets: [{
                label: 'Total Pendapatan (Rp)',
                data: totalDriverData,
                backgroundColor: 'rgba(16, 185, 129, 0.7)',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: { display: true, text: 'Total Pendapatan per Driver' },
                tooltip: {
                    callbacks: {
                        label: ctx => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: v => 'Rp ' + v.toLocaleString('id-ID') },
                    title: { display: true, text: 'Total Pendapatan' }
                }
            }
        }
    });
});
</script>
@endsection
<style>
    /* ======== RESPONSIVE FIXES FOR MOBILE ======== */

    /* Container utama biar ada padding di sisi layar kecil */
    @media (max-width: 640px) {
        .container {
            padding: 1rem !important;
        }

        /* Headings biar tidak kepanjangan */
        h1.text-5xl {
            font-size: 1.8rem !important;
            text-align: center;
        }

        h2.text-xl,
        h2.text-lg {
            font-size: 1.1rem !important;
            text-align: center;
        }

        /* Grid dipecah jadi 1 kolom */
        .grid {
            grid-template-columns: 1fr !important;
        }

        /* Tabel bisa digeser horizontal */
        table {
            min-width: 600px !important;
        }

        /* Bungkus tabel auto scroll */
        .overflow-x-auto {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch;
        }

        /* Chart menyesuaikan lebar layar */
        canvas {
            max-width: 100% !important;
            height: auto !important;
        }

        /* Padding box statistik diperkecil */
        .p-6,
        .p-4 {
            padding: 1rem !important;
        }

        /* Tombol edit HPP biar lebih kecil */
        a.bg-blue-500 {
            display: inline-block;
            padding: 0.4rem 0.6rem !important;
            font-size: 0.8rem;
        }

        /* Jarak antar elemen dirapikan */
        .mb-6,
        .mt-6,
        .mb-8,
        .mt-8 {
            margin: 1rem 0 !important;
        }
    }
</style>

@endsection
<!-- {_P!b4eAmhHd -->
