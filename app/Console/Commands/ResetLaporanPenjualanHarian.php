<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LaporanPenjualan;
use App\Models\LaporanPenjualanHistory;
use Illuminate\Support\Facades\Log;
use Exception;

class ResetLaporanPenjualanHarian extends Command
{
    protected $signature = 'laporanpenjualan:reset-harian';
    protected $description = 'Simpan history laporan penjualan harian driver sebelum reset';

    public function handle()
    {
        Log::info('ResetLaporanPenjualanHarian started');

        $today = now()->toDateString();

        try {
            // 1️⃣ Ambil semua laporan hari ini
            $laporanHariIni = LaporanPenjualan::whereDate('tanggal', $today)->get();

            if ($laporanHariIni->isEmpty()) {
                $this->info("⚠️ Tidak ada laporan penjualan hari ini untuk disimpan.");
                return;
            }

            // 2️⃣ Simpan ke history
            foreach ($laporanHariIni as $laporan) {
                LaporanPenjualanHistory::create([
                    'user_id' => $laporan->user_id,
                    'minuman_id' => $laporan->minuman_id,
                    'jumlah' => $laporan->jumlah,
                    'status' => $laporan->status,
                    'bukti_foto' => $laporan->bukti_foto,
                    'tanggal' => $laporan->tanggal,
                ]);
            }

            // 3️⃣ Hapus laporan harian setelah disalin
            $deleted = LaporanPenjualan::whereDate('tanggal', $today)->delete();

            Log::info("✅ Laporan hari ini disimpan ke history dan direset. Total: {$deleted}");
            $this->info("✅ Laporan hari ini disimpan ke history dan direset. Total: {$deleted}");
        } catch (Exception $e) {
            Log::error("❌ ResetLaporanPenjualanHarian error: {$e->getMessage()}");
            $this->error("❌ Terjadi error saat reset laporan penjualan driver: {$e->getMessage()}");
        }
    }
}
