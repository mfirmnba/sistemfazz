<!-- resources/views/owner/minumans/create.blade.php -->

@extends('layouts.app') @section('content')
<div class="container">
    <h2>Tambah Minuman Baru</h2>
    <form action="{{ route('owner.minumans.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Minuman</label>
            <input
                type="text"
                name="nama"
                id="nama"
                class="form-control"
                required
            />
        </div>

        <div class="mb-3">
            <label for="hpp" class="form-label"
                >Harga Pokok Penjualan (HPP)</label
            >
            <input
                type="number"
                name="hpp"
                id="hpp"
                class="form-control"
                step="0.01"
                required
            />
        </div>

        <div class="mb-3">
            <label for="margin" class="form-label">Margin (%)</label>
            <input
                type="number"
                name="margin"
                id="margin"
                class="form-control"
                step="0.01"
                required
            />
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('owner.minumans.index') }}" class="btn btn-secondary"
            >Batal</a
        >
    </form>
</div>
@endsection
