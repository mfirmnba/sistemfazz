@extends('layouts.app') @section('content')
<div class="container mt-5">
    <div class="row">
        <!-- Card Header -->
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3>Daftar Minuman</h3>
                </div>
                <div class="card-body">
                    <!-- Pesan Sukses -->
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session("success") }}
                    </div>
                    @endif

                    <!-- Tombol untuk Tambah Minuman -->
                    <a
                        href="{{ route('owner.minumans.create') }}"
                        class="btn btn-outline-primary mb-4"
                    >
                        <i class="bi bi-plus-circle"></i> Tambah Minuman
                    </a>

                    <!-- Tabel Data Minuman -->
                    <table
                        class="table table-hover table-bordered table-striped"
                    >
                        <thead class="table-light">
                            <tr>
                                <th>Nama Minuman</th>
                                <th>Harga Pokok Penjualan (HPP)</th>
                                <th>Harga Jual</th>
                                <th>Margin (%)</th>
                                <th>Keuntungan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($minumans as $minuman)
                            <tr>
                                <td>{{ $minuman->nama }}</td>
                                <td>{{ number_format($minuman->hpp, 2) }}</td>
                                <td>{{ number_format($minuman->harga, 2) }}</td>
                                <td>
                                    {{ number_format($minuman->margin, 2) }}%
                                </td>
                                <td>
                                    {{ number_format($minuman->keuntungan, 2) }}
                                </td>
                                <td>
                                    <a
                                        href="{{ route('owner.minumans.edit', $minuman->id) }}"
                                        class="btn btn-warning btn-sm"
                                    >
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
