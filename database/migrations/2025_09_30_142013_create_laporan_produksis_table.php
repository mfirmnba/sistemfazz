<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// database/migrations/xxxx_xx_xx_create_laporan_produksis_table.php
return new class extends Migration {
    public function up(): void {
        Schema::create('laporan_produksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // produksi
            $table->foreignId('stock_id')->constrained('stocks')->onDelete('cascade');
            $table->integer('jumlah_digunakan');// jumlah yang diproduksi
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('laporan_produksis');
    }
};

