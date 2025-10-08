<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\ResetStokMinuman::class,
        \App\Console\Commands\ResetLaporanProduksiHarian::class,
        \App\Console\Commands\ResetLaporanPenjualanHarian::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // Jadwalkan command reset laporan penjualan harian
        $schedule->command('laporanpenjualan:reset-harian')->dailyAt('00:00');
        // Jadwalkan command reset laporan produksi harian
        $schedule->command('laporanproduksi:reset-harian')->dailyAt('00:00');
        // Jadwalkan command reset stok minuman
        $schedule->command('minuman:reset-stok')->dailyAt('00:00');
    }


    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
