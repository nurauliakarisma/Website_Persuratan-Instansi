@echo off
title Website Persuratan - Laravel Server
echo ===================================================
echo Memulai Website Persuratan DPRD Jawa Timur...
echo Pastikan Apache dan MySQL di XAMPP sudah berjalan!
echo ===================================================
echo Server akan berjalan di: http://127.0.0.1:8000
echo.
C:\xampp\php\php.exe artisan serve --host=127.0.0.1 --port=8000
pause
