<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('laporan_penjualan_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('minuman_id')->nullable()->constrained('minumans')->nullOnDelete();
            $table->integer('jumlah');
            $table->enum('status', ['terjual', 'expired', 'tumpah'])->default('terjual');
            $table->string('bukti_foto')->nullable();
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('laporan_penjualan_histories');
    }
};
