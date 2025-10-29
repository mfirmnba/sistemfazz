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

        <!-- Statistik Ringkas Warna urutan nomor 2 -->
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

    <!-- ========================================================= -->
    <!-- 📊 Grafik Bulanan: Pendapatan & Cup Terjual per Driver -->
    <!-- ========================================================= -->
    <div class="mt-8 bg-white shadow rounded-xl p-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-4 text-center">
            📊 Grafik Bulanan Pendapatan & Cup Terjual per Driver
        </h2>
        <div class="w-full h-[400px]">
            <canvas id="chartBulananDriver"></canvas>
        </div>
    </div>

    <!-- ========================================================= -->
    <!-- 📈 Grafik Tahunan: Pendapatan & Cup Terjual per Driver -->
    <!-- ========================================================= -->
    <div class="mt-8 bg-white shadow rounded-xl p-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-4 text-center">
            📈 Grafik Tahunan Pendapatan & Cup Terjual per Driver
        </h2>
        <div class="w-full h-[400px]">
            <canvas id="chartTahunanDriver"></canvas>
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
                📦 Stok Terpakai
            </h2>
            <div class="h-64">
                <canvas id="stokTerpakaiChart" class="w-full h-full"></canvas>
            </div>
        </div>

        <!-- 🍩 Doughnut Chart: Stock -->
        <div class="bg-white dark:bg-gray-800 p-5 shadow rounded-xl text-center transform transition duration-500 hover:scale-[1.03] animate-fade-in delay-100">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-3">
                🍩 Komposisi Stock
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
                🚴 Penjualan & Omset Rider
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
document.addEventListener("DOMContentLoaded", function() {

    // =========================
    // 1. Bar Chart: Bahan Terpakai Hari Ini
    // =========================
    const barChartEl = document.getElementById("barChart");
    if (barChartEl) {
        new Chart(barChartEl, {
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
    }

    // =========================
    // 2. Doughnut Chart: Stok tersedia vs terpakai
    // =========================
    const doughnutEl = document.getElementById("doughnutChart");
    if (doughnutEl) {
        new Chart(doughnutEl, {
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
    }

    // =========================
    // 3. Line Chart: Penjualan Minuman Hari Ini
    // =========================
    const lineChartEl = document.getElementById("lineChart");
    if (lineChartEl) {
        new Chart(lineChartEl, {
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
    }

    // =========================
    // 4. Bar Chart: Stok vs Terpakai Hari Ini
    // =========================
    const stokEl = document.getElementById('stokTerpakaiChart');
    if (stokEl) {
        const ctxStok = stokEl.getContext('2d');
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
                    { label: 'Jumlah Stok', data: jumlahBahan, backgroundColor: bgColors, borderRadius: 5 },
                    { label: 'Stok Terpakai Hari Ini', data: stokTerpakai, backgroundColor: 'rgba(16,185,129,0.7)', borderRadius: 5 }
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    // =========================
    // 5. Bar Chart: Keuntungan per Minuman
    // =========================
    const keuntunganEl = document.getElementById("keuntunganChart");
    if (keuntunganEl) {
        const ctxKeuntungan = keuntunganEl.getContext("2d");
        const labelsKeuntungan = @json($minumans->pluck('nama'));
        const hargas = @json($minumans->pluck('harga'));
        const hpps = @json($minumans->pluck('hpp'));
        const keuntunganData = hargas.map((harga, i) => harga - (hpps[i] ?? 0));
        const dataGabung = labelsKeuntungan.map((nama, i) => ({ nama, keuntungan: keuntunganData[i] }))
                                           .sort((a, b) => b.keuntungan - a.keuntungan);
        new Chart(ctxKeuntungan, {
            type: 'bar',
            data: {
                labels: dataGabung.map(d => d.nama),
                datasets: [{
                    label: 'Keuntungan per Cup (Rp)',
                    data: dataGabung.map(d => d.keuntungan),
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    borderRadius: 6,
                    barThickness: 18
                }]
            },
            options: { indexAxis: 'y', responsive: true }
        });
    }

    // =========================
    // 6. Line Chart: Total Keuntungan Per Bulan
    // =========================
    const penjualanProfitEl = document.getElementById("penjualanProfitChart");
    if (penjualanProfitEl) {
        const ctxPenjualanProfit = penjualanProfitEl.getContext("2d");
        new Chart(ctxPenjualanProfit, {
            type: 'bar',
            data: {
                labels: @json($bulanLabels),
                datasets: [
                    { label: 'Penjualan (Rp)', data: @json($penjualanData), backgroundColor: 'rgba(54,162,235,0.6)', borderRadius: 6 },
                    { label: 'Keuntungan (Rp)', data: @json($totalKeuntunganBulanan), backgroundColor: 'rgba(75,192,192,0.6)', borderRadius: 6 }
                ]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });
    }

    // =========================
    // 7. Line Chart: Pendapatan Driver Hari Demi Hari
    // =========================
    const pendapatanHarianEl = document.getElementById('chartPendapatanDriverHarian');
    if (pendapatanHarianEl) {
        const ctx = pendapatanHarianEl.getContext('2d');
        const pendapatanData = @json($pendapatanDriverHarian);
        const warna = ['#36A2EB','#FF6384','#4BC0C0','#FFCE56','#9966FF','#FF9F40'];
        const datasets = Object.entries(pendapatanData).map(([driver, items], i) => ({
            label: driver,
            data: items.sort((a,b)=>new Date(a.tanggal)-new Date(b.tanggal))
                        .map(x=>({x:x.tanggal,y:x.total_pendapatan})),
            borderColor: warna[i%warna.length],
            fill:false
        }));
        new Chart(ctx,{type:'line',data:{datasets},options:{responsive:true,scales:{x:{type:'time'}}}});
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
