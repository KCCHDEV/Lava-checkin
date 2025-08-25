@echo off
echo ========================================
echo    Laravel Development Server
echo    Attendance Management System - Lamphun Technical College
echo ========================================
echo.

:: Set PHP path
set PHP_PATH=C:\xampp\php\php.exe
if not exist "%PHP_PATH%" (
    set PHP_PATH=php
)

:: Check if .env exists
if not exist .env (
    echo ERROR: .env file not found
    echo Please run install.bat or install-xampp.bat first
    pause
    exit /b 1
)

:: Check if database exists
if not exist database\database.sqlite (
    echo ERROR: Database file not found
    echo Please run install.bat first
    pause
    exit /b 1
)

:: Clear cache
echo [1/4] Clearing Cache...
%PHP_PATH% artisan config:clear
%PHP_PATH% artisan cache:clear
%PHP_PATH% artisan view:clear
echo ✓ Cache cleared successfully

:: Optimize application
echo.
echo [2/4] Optimizing Application...
%PHP_PATH% artisan config:cache
%PHP_PATH% artisan route:cache
%PHP_PATH% artisan view:cache
echo ✓ Optimization completed

:: Check database connection
echo.
echo [3/4] Checking Database...
%PHP_PATH% artisan migrate:status
if %errorlevel% neq 0 (
    echo ERROR: Cannot connect to database
    pause
    exit /b 1
)
echo ✓ Database is ready

:: Start server
echo.
echo [4/4] Starting Server...
echo.
echo ========================================
echo    Server Started Successfully!
echo ========================================
echo.
echo URL: http://localhost:8000
echo.
echo Press Ctrl+C to stop the server
echo.
echo ========================================
echo.

%PHP_PATH% artisan serve --host=0.0.0.0 --port=8000
