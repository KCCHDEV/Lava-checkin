@echo off
title Laravel Development Server
echo Starting Laravel Development Server...
echo.
echo Server will be available at: http://localhost:8000
echo Press Ctrl+C to stop the server
echo.

cd /d "C:\xampp\htdocs"
set PATH=%PATH%;C:\xampp\php
php artisan serve --host=0.0.0.0 --port=8000

pause
