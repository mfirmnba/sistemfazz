<?php

namespace App\Console\Commands;

use Carbon\CarbonPeriod;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\LaporanProduksi;
use App\Models\LaporanProduksiHistory;
use Illuminate\Support\Facades\Log;
use Exception;

class ResetLaporanProduksiHarian extends Command
{
    protected $signature = 'laporanproduksi:reset-harian';
    protected $description = 'Simpan laporan produksi ke history sebelum reset harian';

    public function handle()
    {
        Log::info('ResetLaporanProduksiHarian started');

        $today = now()->toDateString();

        try {
            // 1️⃣ Ambil semua laporan hari ini
            $laporanHariIni = LaporanProduksi::whereDate('tanggal', $today)->get();

            if ($laporanHariIni->isEmpty()) {
                $this->info("⚠️ Tidak ada laporan produksi hari ini untuk disimpan.");
                return;
            }

            // 2️⃣ Simpan ke history sebelum dihapus
            foreach ($laporanHariIni as $laporan) {
                LaporanProduksiHistory::create([
                    'driver_id' => $laporan->driver_id,
                    'stock_id' => $laporan->stock_id,
                    'tanggal' => $laporan->tanggal,
                    'jumlah_produksi' => $laporan->jumlah_produksi,
                    'total_pendapatan' => $laporan->total_pendapatan ?? 0,
                ]);
            }

            // 3️⃣ Hapus laporan harian setelah disalin
            $deleted = LaporanProduksi::whereDate('tanggal', $today)->delete();

            Log::info("✅ Laporan hari ini disimpan ke history dan direset. Total: {$deleted}");
            $this->info("✅ Laporan hari ini disimpan ke history dan direset. Total: {$deleted}");
        } catch (Exception $e) {
            Log::error("❌ ResetLaporanProduksiHarian error: {$e->getMessage()}");
            $this->error("❌ Terjadi error saat reset laporan produksi: {$e->getMessage()}");
        }
    }
}
