@extends('layouts.driver') @section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">📊 Dashboard Driver</h1>
    <p>
        Halo, <b>{{ auth()->user()->name }}</b> 👋
    </p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <!-- Total Laporan Hari Ini -->
        <div class="bg-blue-600 text-white rounded-lg p-6 shadow-md">
            <h2 class="text-lg font-semibold">Laporan Hari Ini</h2>
            <p class="text-3xl font-bold mt-2">{{ $countToday }}</p>
        </div>

        <!-- Total Pendapatan (hanya yg terjual) -->
        <div class="bg-green-600 text-white rounded-lg p-6 shadow-md">
            <h2 class="text-lg font-semibold">Total Pendapatan Hari Ini</h2>
            <p class="text-3xl font-bold mt-2">
                Rp {{ number_format($totalToday, 0, ",", ".") }}
            </p>
        </div>

        <!-- Total Cup Terjual -->
        <div class="bg-purple-600 text-white rounded-lg p-6 shadow-md">
            <h2 class="text-lg font-semibold">Cup Terjual</h2>
            <p class="text-3xl font-bold mt-2">{{ $totalCupTerjual }} cup</p>
        </div>

        <!-- Total Expired -->
        <div class="bg-yellow-600 text-white rounded-lg p-6 shadow-md">
            <h2 class="text-lg font-semibold">Expired</h2>
            <p class="text-3xl font-bold mt-2">{{ $totalExpired }} cup</p>
        </div>

        <!-- Total Tumpah -->
        <div class="bg-red-600 text-white rounded-lg p-6 shadow-md">
            <h2 class="text-lg font-semibold">Tumpah</h2>
            <p class="text-3xl font-bold mt-2">{{ $totalTumpah }} cup</p>
        </div>

        <!-- Laporan Terakhir -->
        <div class="bg-gray-700 text-white rounded-lg p-6 shadow-md">
            <h2 class="text-lg font-semibold">Laporan Terakhir</h2>
            @if($lastReport)
            <p class="mt-2">
                {{ $lastReport->minuman->nama }} -
                {{ $lastReport->jumlah }} ({{ ucfirst($lastReport->status) }})
            </p>
            <small class="text-gray-300">
                Tanggal: {{ $lastReport->tanggal }}
            </small>
            @else
            <p class="mt-2">Belum ada laporan</p>
            @endif
        </div>
    </div>

    <div class="mt-8 flex gap-4">
        <a
            href="{{ route('driver.laporanpenjualan.create') }}"
            class="bg-blue-500 text-white px-4 py-2 rounded shadow hover:bg-blue-700"
        >
            + Tambah Laporan
        </a>
        <a
            href="{{ route('driver.laporanpenjualan.index') }}"
            class="bg-gray-700 text-white px-4 py-2 rounded shadow hover:bg-gray-900"
        >
            📋 Lihat Semua Laporan
        </a>
    </div>
</div>
@endsection
