<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('minumans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->decimal('harga', 10, 2);
            $table->integer('stok')->default(0); // jumlah minuman tersedia
            $table->integer('stok_hari_ini')->default(0);
            $table->integer('stok_besok')->default(0);

            // Tambahkan kolom sesuai database kamu
            $table->decimal('hpp', 10, 2)->nullable();
            $table->decimal('margin', 10, 2)->nullable();
            $table->decimal('keuntungan', 10, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('minumans');
    }
};
