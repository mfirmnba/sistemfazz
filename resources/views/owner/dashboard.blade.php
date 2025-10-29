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

    <!-- Statistik Ringkas urutan nomor 1 -->
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

        <!-- Statistik Ringkas urutan nomor 2 -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white p-4 shadow rounded-lg">
            <p class="text-gray-500">Pendapatan Hari Ini</p>
            <h2 class="text-2xl font-bold">
                Rp{{ number_format($totalPendapatanHariIni  ?? 0, 0, ",", ".") }}
            </h2>
            <a href="#" class="text-blue-500 text-sm">View data →</a>
        </div>
        <div class="bg-white p-4 shadow rounded-lg">
            <p class="text-gray-500">Cup Terjual Hari Ini</p>
            <h2 class="text-2xl font-bold">
                {{ $totalCupTerjual ?? 0 }} cup
            </h2>
            <a href="#" class="text-blue-500 text-sm">View data →</a>
        </div>

        <div class="bg-white p-4 shadow rounded-lg">
            <p class="text-gray-500">Expired Hari Ini</p>
            <h2 class="text-2xl font-bold">{{ $totalExpired ?? 0 }}</h2>
            <a href="#" class="text-blue-500 text-sm">View data →</a>
        </div>
        <div class="bg-white p-4 shadow rounded-lg">
            <p class="text-gray-500">Tumpah Hari Ini</p>
            <h2 class="text-2xl font-bold">{{ $totalTumpah ?? 0 }}</h2>
            <a href="#" class="text-blue-500 text-sm">View data →</a>
        </div>
    </div>

        <!-- 🥤 Minuman Terjual Hari Ini per Driver urutan nomor 3 -->
    <div class="bg-white p-6 rounded-xl shadow mt-10 mb-10">
        <h2 class="text-xl font-semibold mb-4">
             Minuman Terjual Hari Ini per Rider
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

    <!-- Rincian Pendapatan Per User Hari Ini urutan nomor 4 -->
    <div class="bg-white p-6 rounded-xl shadow mb-10 mt-6">
        <h2 class="text-lg font-semibold mb-4">
             Pendapatan Rider (Hari Ini)
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

    <!-- Rincian Pendapatan Per User All Time urutan nomor 5 -->
    <div class="bg-white p-6 rounded-xl shadow mb-10">
        <h2 class="text-lg font-semibold mb-4">
             Pendapatan Rider (All Time)
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

        <!-- ============================= -->
    <!-- 📊 Grafik Driver: Bulanan & Tahunan (Side-by-Side) -->
    <!-- ============================= -->
    <div class="w-full flex flex-row justify-between items-stretch gap-6 mt-8 mb-10 overflow-x-auto">

        <!-- 📊 Grafik Bulanan -->
        <div class="w-1/2 min-w-[600px] bg-white dark:bg-gray-800 p-6 shadow rounded-xl hover:shadow-lg transition duration-300">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4 text-center">
                📊 Grafik Bulanan Pendapatan & Cup Terjual per Driver
            </h2>
            <div class="h-[400px] w-full flex items-center justify-center">
                <canvas id="chartBulananDriver" class="w-full h-full"></canvas>
            </div>
        </div>

        <!-- 📈 Grafik Tahunan -->
        <div class="w-1/2 min-w-[600px] bg-white dark:bg-gray-800 p-6 shadow rounded-xl hover:shadow-lg transition duration-300">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4 text-center">
                📈 Grafik Tahunan Pendapatan & Cup Terjual per Driver
            </h2>
            <div class="h-[400px] w-full flex items-center justify-center">
                <canvas id="chartTahunanDriver" class="w-full h-full"></canvas>
            </div>
        </div>

    </div>

    <!-- Data User -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10 mt-6">
        @foreach(['Admin' => $adminUsers, 'Driver' => $driverUsers, 'Produksi'
        => $produksiUsers] as $role => $users)
        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-lg font-semibold mb-4">
                @if($role == 'Admin')  @elseif($role == 'Driver')  @else 
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
                 Grafik Keuntungan Minuman
            </h2>
            <div class="h-80">
                <!-- tinggi dibatasi agar tidak terlalu besar -->
                <canvas id="keuntunganChart"></canvas>
            </div>
        </div>
        <div class="p-4 bg-white rounded-lg shadow-md">
            <h2 class="text-lg font-semibold mb-3">
                 Penjualan vs Keuntungan Bulanan
            </h2>
            <div style="height: 320px">
                <canvas id="penjualanProfitChart"></canvas>
            </div>
        </div>
        <!-- 📊 Grafik Penjualan Minuman Terlaris -->
        <div class="bg-white p-6 rounded-xl shadow mt-8">
            <h2 class="text-xl font-semibold mb-4">
                 Penjualan Minuman Terlaris
            </h2>
            <canvas id="penjualanMinumanChart" height="120"></canvas>
        </div>
    </div>

    <!-- ============================= -->
    <!-- 📊 Chart Grid -->
    <!-- ============================= -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6 mb-10">

        <!-- 📦 Grafik Stok Terpakai -->
        <div class="bg-white dark:bg-gray-800 p-5 shadow rounded-xl hover:shadow-lg transition duration-300">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-3">
                Stok Terpakai
            </h2>
            <div class="h-64">
                <canvas id="stokTerpakaiChart" class="w-full h-full"></canvas>
            </div>
        </div>

        <!-- 🍩 Doughnut Chart: Stock -->
        <div class="bg-white dark:bg-gray-800 p-5 shadow rounded-xl text-center transform transition duration-500 hover:scale-[1.03] animate-fade-in delay-100">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-3">
                Komposisi Stock
            </h3>
            <div class="h-56 flex items-center justify-center">
                <canvas id="doughnutChart"></canvas>
            </div>
            <p class="mt-3 text-gray-600 dark:text-gray-400 text-sm">
                Stock terpakai hari ini: 
                <span class="font-semibold text-green-600">{{ $bahanTerpakaiHariIni ?? 0 }}</span>
            </p>
        </div>

        <!-- 🚴 Grafik Gabungan: Penjualan & Omset Rider -->
        <div class="bg-white dark:bg-gray-800 p-5 shadow rounded-xl hover:shadow-lg transition duration-300 animate-fade-in delay-200">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-3">
                Penjualan & Omset Rider
            </h2>
            <div class="h-64 w-full">
                <canvas id="chartGabungDriverLine" class="w-full h-full"></canvas>
            </div>
        </div>

    </div>

    <!-- ============================= -->
    <!-- ✨ Tailwind Animations -->
    <!-- ============================= -->
    <style>
    @keyframes fade-in {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fade-in 0.8s ease forwards; }
    .delay-100 { animation-delay: 0.1s; }
    .delay-200 { animation-delay: 0.2s; }
    </style>

    {{-- ============================= --}}
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

     <!-- Bar Chart: Bahan Terpakai Hari Ini -->
        <div
            class="bg-white p-4 shadow rounded-lg transform transition duration-700 hover:scale-105 animate-fade-in"
        >
            <h3 class="text-lg font-semibold mb-3">Stock Terpakai</h3>
            <canvas id="barChart"></canvas>
        </div>

    <!-- Rincian Stock -->
    <div class="bg-white p-6 rounded-xl shadow mb-10">
        <h2 class="text-lg font-semibold mb-4"> Rincian Total Stock</h2>
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
             Rincian Bahan Terpakai Hari Ini
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
        <h2 class="text-lg font-semibold mb-4"> Rincian Minuman Produksi</h2>
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
             Keuntungan & Margin Minuman
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

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
<script>
                                // =========================
                                // 1. Bar Chart: Bahan Terpakai Hari Ini
                                // =========================
                                new Chart(document.getElementById("barChart"), {
                                    type: "bar",
                                    data: {
                                        labels: @json($laporanToday->pluck('stock.nama_bahan')),
                                        datasets: [{
                                            label: "Jumlah Stok Dipakai Hari ini",
                                            data: @json($laporanToday->pluck('jumlah_digunakan')),
                                            backgroundColor: "#10b981",
                                            borderRadius: 5
                                        }],
                                    },
                                    options: {
                                        responsive: true,
                                        plugins: { legend: { display: true } },
                                        scales: { y: { beginAtZero: true } },
                                    },
                                });

                                // =========================
                                // 2. Doughnut Chart: Stok tersedia vs terpakai
                                // =========================
                                new Chart(document.getElementById("doughnutChart"), {
                                    type: "doughnut",
                                    data: {
                                        labels: ["Tersedia", "Terpakai"],
                                        datasets: [{
                                            data: [{{ $totalStock }}, {{ $bahanTerpakaiHariIni }}],
                                            backgroundColor: ["#3b82f6", "#ef4444"],
                                            borderWidth: 2,
                                            borderColor: "#fff"
                                        }],
                                    },
                                    options: {
                                        responsive: true,
                                        plugins: { legend: { position: "bottom" } }
                                    }
                                });

                                // =========================
                                // 3. Line Chart: Penjualan Minuman Hari Ini
                                // =========================
                                new Chart(document.getElementById("lineChart"), {
                                    type: "line",
                                    data: {
                                        labels: @json($laporanPenjualanGrouped->keys()),
                                        datasets: [{
                                            label: "Jumlah Minuman Terjual Hari Ini",
                                            data: @json($laporanPenjualanGrouped->values()),
                                            borderColor: "red",
                                            backgroundColor: "rgba(255, 99, 132, 0.2)",
                                            fill: true,
                                            tension: 0.3,
                                        }],
                                    },
                                    options: {
                                        responsive: true,
                                        plugins: { legend: { display: true } },
                                        scales: { y: { beginAtZero: true } },
                                    },
                                });

                                // =========================
                                // 4. Bar Chart: Stok vs Terpakai Hari Ini
                                // =========================
                                const ctxStok = document.getElementById('stokTerpakaiChart').getContext('2d');
                                const labelsStok = @json($stocks->pluck('nama_bahan'));
                                const jumlahBahan = @json($stocks->pluck('jumlah'));

                                const terpakaiMap = {};
                                @foreach($laporanToday as $item)
                                    @if($item->stock)
                                        terpakaiMap["{{ $item->stock->nama_bahan }}"] = {{ $item->jumlah_digunakan }};
                                    @endif
                                @endforeach
                                const stokTerpakai = labelsStok.map(nama => terpakaiMap[nama] ?? 0);

                                const bgColors = jumlahBahan.map(stok => stok < 500 ? 'rgba(220,38,38,0.7)' : 'rgba(59,130,246,0.7)');

                                new Chart(ctxStok, {
                                    type: 'bar',
                                    data: {
                                        labels: labelsStok,
                                        datasets: [
                                            {
                                                label: 'Jumlah Stok',
                                                data: jumlahBahan,
                                                backgroundColor: bgColors,
                                                borderRadius: 5
                                            },
                                            {
                                                label: 'Stok Terpakai Hari Ini',
                                                data: stokTerpakai,
                                                backgroundColor: 'rgba(16,185,129,0.7)',
                                                borderRadius: 5
                                            }
                                        ]
                                    },
                                    options: {
                                        responsive: true,
                                        plugins: {
                                            legend: { position: 'bottom' },
                                            tooltip: { mode: 'index', intersect: false }
                                        },
                                        scales: {
                                            x: { stacked: false, title: { display: true, text: 'Nama Bahan' } },
                                            y: { beginAtZero: true, title: { display: true, text: 'Jumlah' } }
                                        }
                                    }
                                });

                                // =========================
                                // 5. Bar Chart: Keuntungan per Minuman (urut terbanyak)
                                // =========================
                                const ctxKeuntungan = document.getElementById("keuntunganChart").getContext("2d");
                                const labelsKeuntungan = @json($minumans->pluck('nama'));
                                const hargas = @json($minumans->pluck('harga'));
                                const hpps = @json($minumans->pluck('hpp'));
                                const keuntunganData = hargas.map((harga, i) => harga - (hpps[i] ?? 0));

                                const dataGabung = labelsKeuntungan.map((nama, i) => ({
                                    nama,
                                    keuntungan: keuntunganData[i]
                                })).sort((a, b) => b.keuntungan - a.keuntungan);

                                const sortedLabels = dataGabung.map(d => d.nama);
                                const sortedData = dataGabung.map(d => d.keuntungan);

                                new Chart(ctxKeuntungan, {
                                    type: 'bar',
                                    data: {
                                        labels: sortedLabels,
                                        datasets: [{
                                            label: 'Keuntungan per Cup (Rp)',
                                            data: sortedData,
                                            backgroundColor: 'rgba(255, 99, 132, 0.6)',
                                            borderRadius: 6,
                                            barThickness: 18,
                                            maxBarThickness: 20,
                                            borderSkipped: false
                                        }]
                                    },
                                    options: {
                                        indexAxis: 'y',
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: { display: false },
                                            tooltip: {
                                                callbacks: {
                                                    label: ctx => 'Rp ' + ctx.parsed.x.toLocaleString('id-ID')
                                                }
                                            }
                                        },
                                        scales: {
                                            x: {
                                                beginAtZero: true,
                                                ticks: {
                                                    callback: value => 'Rp ' + value.toLocaleString('id-ID'),
                                                    font: { size: 11 }
                                                },
                                                grid: { color: '#f1f5f9' }
                                            },
                                            y: {
                                                ticks: { font: { size: 12 } },
                                                grid: { display: false }
                                            }
                                        }
                                    }
                                });

                                // =========================
                                // 6. Line Chart: Total Keuntungan Per Bulan
                                // =========================
                                const ctxPenjualanProfit = document.getElementById("penjualanProfitChart").getContext("2d");

                                const bulanLabels = @json($bulanLabels);
                                const penjualanData = @json($penjualanData);
                                const profitData = @json($totalKeuntunganBulanan);

                                new Chart(ctxPenjualanProfit, {
                                    type: 'bar',
                                    data: {
                                        labels: bulanLabels,
                                        datasets: [
                                            {
                                                label: 'Penjualan (Rp)',
                                                data: penjualanData,
                                                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                                                borderRadius: 6
                                            },
                                            {
                                                label: 'Keuntungan (Rp)',
                                                data: profitData,
                                                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                                                borderRadius: 6
                                            }
                                        ]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            tooltip: {
                                                callbacks: {
                                                    label: ctx => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                                                }
                                            }
                                        },
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                ticks: {
                                                    callback: value => 'Rp ' + value.toLocaleString('id-ID')
                                                }
                                            }
                                        }
                                    }
                                });

                                const ctxMinuman = document.getElementById('penjualanMinumanChart').getContext('2d');

                                new Chart(ctxMinuman, {
                                    type: 'bar',
                                    data: {
                                        labels: @json($penjualanMinuman->pluck('minuman.nama')),
                                        datasets: [{
                                            label: 'Jumlah Terjual (Cup)',
                                            data: @json($penjualanMinuman->pluck('total_qty')),
                                            backgroundColor: 'rgba(54, 162, 235, 0.7)',
                                            borderColor: 'rgba(54, 162, 235, 1)',
                                            borderWidth: 2,
                                            borderRadius: 6,
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        plugins: {
                                            legend: { display: false },
                                            tooltip: {
                                                callbacks: {
                                                    label: function(context) {
                                                        return context.raw + " Cup";
                                                    }
                                                }
                                            }
                                        },
                                        scales: {
                                            x: {
                                                ticks: { font: { weight: 'bold' } }
                                            },
                                            y: {
                                                beginAtZero: true,
                                                ticks: {
                                                    stepSize: 1,
                                                    callback: function(value) {
                                                        return value + " Cup";
                                                    }
                                                }
                                            }
                                        }
                                    }
                                });

            // =========================
            // 7. Line Chart: Pendapatan Driver Hari Demi Hari (Data Berkelanjutan)
            // =========================
            document.addEventListener("DOMContentLoaded", function() {
                const ctx = document.getElementById('chartPendapatanDriverHarian').getContext('2d');
                const pendapatanData = @json($pendapatanDriverHarian);

                const warna = [
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(153, 102, 255, 0.8)',
                    'rgba(255, 159, 64, 0.8)',
                ];

                const datasets = Object.entries(pendapatanData).map(([driver, items], i) => {
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

                new Chart(ctx, {
                    type: 'line',
                    data: { datasets },
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
                                ticks: {
                                    callback: v => 'Rp ' + v.toLocaleString('id-ID')
                                }
                            }
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: '📈 Grafik Pendapatan Harian per Driver'
                            },
                            legend: { position: 'bottom' },
                            tooltip: {
                                callbacks: {
                                    label: ctx => `${ctx.dataset.label}: Rp ${ctx.parsed.y.toLocaleString('id-ID')}`
                                }
                            }
                        }
                    }
                });
            });

document.addEventListener("DOMContentLoaded", function () {
    const dataDriver = @json($penjualanPerUserAllTime);

    const labels = dataDriver.map(d => d.user?.name ?? 'Tanpa Nama');
    const cupData = dataDriver.map(d => d.total_cup);
    const pendapatanData = dataDriver.map(d => d.pendapatan);

    const ctx = document.getElementById("chartGabungDriverLine").getContext("2d");

    new Chart(ctx, {
        type: "line",
        data: {
            labels: labels,
            datasets: [
                {
                    label: "Jumlah Cup Terjual",
                    data: cupData,
                    borderColor: "#f87171",
                    backgroundColor: "rgba(248,113,113,0.15)",
                    borderWidth: 2,
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4,
                    pointBackgroundColor: "#ef4444",
                    yAxisID: "yCup"
                },
                {
                    label: "Total Pendapatan (Rp)",
                    data: pendapatanData,
                    borderColor: "#3b82f6",
                    backgroundColor: "rgba(59,130,246,0.15)",
                    borderWidth: 2,
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4,
                    pointBackgroundColor: "#2563eb",
                    yAxisID: "yPendapatan"
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: "bottom",
                    labels: {
                        color: "#111",
                        boxWidth: 15,
                        font: { size: 12 }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            if (ctx.dataset.label.includes("Pendapatan")) {
                                return "Rp " + ctx.parsed.y.toLocaleString("id-ID");
                            }
                            return ctx.parsed.y + " Cup";
                        }
                    }
                },
                title: {
                    display: false
                }
            },
            interaction: { mode: "index", intersect: false },
            scales: {
                x: {
                    ticks: { color: "#333", font: { size: 11, weight: "500" } },
                    grid: { display: false }
                },
                yCup: {
                    type: "linear",
                    display: true,
                    position: "left",
                    beginAtZero: true,
                    ticks: {
                        color: "#ef4444",
                        callback: v => v + " Cup"
                    },
                    grid: { color: "#f3f4f6" }
                },
                yPendapatan: {
                    type: "linear",
                    display: true,
                    position: "right",
                    beginAtZero: true,
                    ticks: {
                        color: "#2563eb",
                        callback: v => "Rp " + v.toLocaleString("id-ID")
                    },
                    grid: { drawOnChartArea: false }
                }
            }
        }
    });
    const bulananEl = document.getElementById("chartBulananDriver");
    if (bulananEl) {
        const ctxBulanan = bulananEl.getContext("2d");
        const dataBulanan = @json($grafikBulananDriver);

        const warna = ['#36A2EB','#FF6384','#4BC0C0','#FFCE56','#9966FF','#FF9F40'];
        const bulanLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

        // Dataset Pendapatan (Line)
        const datasetsPendapatan = Object.values(dataBulanan).map((driver, i) => {
            const nama = driver[0]?.nama_driver ?? 'Driver ' + (i + 1);
            const dataPendapatan = Array(12).fill(0);
            driver.forEach(d => {
                const idx = parseInt(d.bulan) - 1;
                if (idx >= 0 && idx < 12) dataPendapatan[idx] = d.total_pendapatan ?? 0;
            });
            return {
                label: `${nama} - Pendapatan (Rp)`,
                data: dataPendapatan,
                borderColor: warna[i % warna.length],
                backgroundColor: warna[i % warna.length],
                borderWidth: 2,
                fill: false,
                tension: 0.3,
                yAxisID: 'yPendapatan'
            };
        });

        // Dataset Cup (Bar)
        const datasetsCup = Object.values(dataBulanan).map((driver, i) => {
            const nama = driver[0]?.nama_driver ?? 'Driver ' + (i + 1);
            const dataCup = Array(12).fill(0);
            driver.forEach(d => {
                const idx = parseInt(d.bulan) - 1;
                if (idx >= 0 && idx < 12) dataCup[idx] = d.total_cup ?? 0;
            });
            return {
                label: `${nama} - Cup Terjual`,
                data: dataCup,
                type: 'bar',
                backgroundColor: warna[i % warna.length] + '55',
                borderColor: warna[i % warna.length],
                borderRadius: 4,
                yAxisID: 'yCup'
            };
        });

        new Chart(ctxBulanan, {
            data: {
                labels: bulanLabels,
                datasets: [...datasetsCup, ...datasetsPendapatan]
            },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { position: "bottom" },
                    title: { display: true, text: "📊 Pendapatan & Cup Terjual per Driver (Bulanan)" },
                    tooltip: {
                        callbacks: {
                            label: ctx => {
                                if (ctx.dataset.label.includes("Pendapatan"))
                                    return ctx.dataset.label + ': Rp ' + ctx.parsed.y.toLocaleString('id-ID');
                                return ctx.dataset.label + ': ' + ctx.parsed.y + ' Cup';
                            }
                        }
                    }
                },
                scales: {
                    yPendapatan: {
                        type: 'linear',
                        position: 'left',
                        beginAtZero: true,
                        ticks: { callback: v => 'Rp ' + v.toLocaleString('id-ID') },
                        grid: { color: '#f3f4f6' }
                    },
                    yCup: {
                        type: 'linear',
                        position: 'right',
                        beginAtZero: true,
                        ticks: { callback: v => v + ' cup' },
                        grid: { drawOnChartArea: false }
                    }
                }
            }
        });
    }
});
</script>

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
