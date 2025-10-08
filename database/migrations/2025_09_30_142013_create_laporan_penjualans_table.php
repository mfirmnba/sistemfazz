<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('laporan_penjualans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // driver
        $table->foreignId('minuman_id')->constrained('minumans')->onDelete('cascade');
        $table->integer('jumlah');
        $table->enum('status', ['terjual', 'expired', 'tumpah'])->default('terjual');
        $table->string('bukti_foto')->nullable(); // simpan path foto
        $table->date('tanggal'); // akan diisi otomatis di model/controller
        $table->timestamps();
    });

    }

    public function down(): void {
        Schema::dropIfExists('laporan_penjualans');
    }
};
