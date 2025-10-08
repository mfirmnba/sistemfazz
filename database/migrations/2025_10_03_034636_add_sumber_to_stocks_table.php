<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('stocks', function (Blueprint $table) {
            $table->enum('sumber', ['produksi', 'driver'])->default('produksi')->after('satuan');
        });
    }

    public function down(): void {
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropColumn('sumber');
        });
    }
};
