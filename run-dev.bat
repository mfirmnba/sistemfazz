@echo off
title Laravel + Vite Runner
echo ========================================
echo   Starting Laravel + Vite Development
echo ========================================

:: Jalankan PHP Artisan Serve
start cmd /k "php artisan serve"

:: Jalankan NPM Run Dev
start cmd /k "npm run dev"

echo ========================================
echo   Semua service sudah berjalan!
echo ========================================
pause
