@echo off
:: -------------------------------
:: Batch file otomatis untuk menjalankan Scheduler Laravel
:: Menjalankan tiga command harian
:: -------------------------------

:: Folder project Laravel
SET LARAVEL_PATH=C:\laragon\www\Sistem_Fazz

:: Pindah ke folder project
cd /d %LARAVEL_PATH%

:: Tanggal dan waktu untuk log
SET TIMESTAMP=%DATE% %TIME%

:: Jalankan command laporan penjualan harian
echo [%TIMESTAMP%] Menjalankan laporanpenjualan:reset-harian >> scheduler.log
php artisan laporanpenjualan:reset-harian >> scheduler.log 2>&1

:: Jalankan command laporan produksi harian
echo [%TIMESTAMP%] Menjalankan laporanproduksi:reset-harian >> scheduler.log
php artisan laporanproduksi:reset-harian >> scheduler.log 2>&1

:: Jalankan command reset stok minuman
echo [%TIMESTAMP%] Menjalankan minuman:reset-stok >> scheduler.log
php artisan minuman:reset-stok >> scheduler.log 2>&1

echo [%TIMESTAMP%] ✅ Semua command selesai >> scheduler.log
