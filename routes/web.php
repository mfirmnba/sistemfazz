<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboard;
use App\Http\Controllers\Driver\DashboardController as DriverDashboard;
use App\Http\Controllers\Produksi\DashboardController as ProduksiDashboard;
use App\Http\Controllers\Produksi\LaporanProduksiController;
use App\Http\Controllers\Owner\ProfitController;
use App\Http\Controllers\Owner\OmsetController;
use App\Http\Controllers\Owner\PenjualanController;
use App\Http\Controllers\Owner\StockController;

// ======================
// Halaman depan
// ======================
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ======================
// Dashboard universal
// -> akan diarahkan otomatis oleh middleware redirect.role
// ======================
Route::view('/dashboard', 'dashboard')
    ->middleware(['auth', 'verified', 'redirect.role'])
    ->name('dashboard');

// ======================
// Settings (Volt)
// ======================
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

// ======================
// Role-based dashboards
// ======================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
        Route::resource('minuman', \App\Http\Controllers\Admin\MinumanController::class);
        Route::resource('stock', \App\Http\Controllers\Admin\StockController::class);
        Route::get('dashboard/kirim-stock-wa', [AdminDashboard::class, 'kirimStockKeWA'])
            ->name('kirim-stock-wa');
    });

Route::middleware(['auth', 'role:owner'])
    ->prefix('owner')
    ->name('owner.')
    ->group(function () {
        Route::get('/dashboard', [OwnerDashboard::class, 'index'])->name('dashboard');
        Route::resource('minuman', \App\Http\Controllers\Owner\MinumanController::class);
        // Halaman detail statistik
        Route::get('/profit', [ProfitController::class, 'index'])->name('profit');
        Route::get('/omset', [OmsetController::class, 'index'])->name('omset');
        Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan');
        Route::get('/stock', [StockController::class, 'index'])->name('stock');
    });

Route::middleware(['auth', 'role:driver'])
    ->prefix('driver')
    ->name('driver.')
    ->group(function () {
        Route::get('/dashboard', [DriverDashboard::class, 'index'])->name('dashboard');
         // route khusus untuk kirim laporan via WA
        Route::get('laporanpenjualan/send-whatsapp', [\App\Http\Controllers\Driver\LaporanPenjualanController::class, 'sendWhatsapp'])
            ->name('laporanpenjualan.sendWhatsapp');

        // resource untuk CRUD laporan penjualan
        Route::resource('laporanpenjualan', \App\Http\Controllers\Driver\LaporanPenjualanController::class);
    });

Route::middleware(['auth', 'role:produksi'])
    ->prefix('produksi')
    ->name('produksi.')
    ->group(function () {
        Route::get('/dashboard', [ProduksiDashboard::class, 'index'])->name('dashboard');
        Route::post('/minuman/reset-all', [ProduksiDashboard::class, 'resetAllStok'])->name('minuman.resetAll');
        // Laporan Produksi
        Route::resource('minuman', App\Http\Controllers\Produksi\MinumanController::class);
        Route::get('laporanproduksi', [LaporanProduksiController::class, 'index'])->name('laporanproduksi.index');
        Route::get('laporanproduksi/create', [LaporanProduksiController::class, 'create'])->name('laporanproduksi.create');
        Route::post('laporanproduksi', [LaporanProduksiController::class, 'store'])->name('laporanproduksi.store');
        Route::get('laporanproduksi/{id}/edit', [LaporanProduksiController::class, 'edit'])->name('laporanproduksi.edit');
        Route::put('laporanproduksi/{id}', [LaporanProduksiController::class, 'update'])->name('laporanproduksi.update');
        Route::delete('laporanproduksi/{id}', [LaporanProduksiController::class, 'destroy'])->name('laporanproduksi.destroy');
        Route::get('laporanproduksi/send-wa', [LaporanProduksiController::class, 'sendWa'])->name('laporanproduksi.sendWa');
        Route::post('/produksi/minuman/{minuman}/reset', [ProduksiDashboard::class, 'resetStok'])->name('minuman.reset');
    });

// ======================
// Auth routes (Breeze/Fortify)
// ======================
require __DIR__.'/auth.php';
