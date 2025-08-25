@echo off
chcp 65001 >nul
setlocal enabledelayedexpansion

echo.
echo ========================================
echo    ระบบติดตั้ง Laravel Attendance System
echo ========================================
echo.

:: Check if PHP is installed
echo [1/8] ตรวจสอบ PHP...
php --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ PHP ไม่ได้ติดตั้ง กรุณาติดตั้ง PHP ก่อน
    echo.
    echo วิธีติดตั้ง PHP:
    echo 1. ดาวน์โหลด XAMPP จาก https://www.apachefriends.org/
    echo 2. ติดตั้ง XAMPP
    echo 3. เปิดใช้งาน Apache และ MySQL
    echo.
    pause
    exit /b 1
)
echo ✅ PHP พร้อมใช้งาน

:: Check if Composer is installed
echo.
echo [2/8] ตรวจสอบ Composer...
composer --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ Composer ไม่ได้ติดตั้ง กรุณาติดตั้ง Composer ก่อน
    echo.
    echo วิธีติดตั้ง Composer:
    echo 1. ดาวน์โหลดจาก https://getcomposer.org/download/
    echo 2. ติดตั้ง Composer
    echo 3. รีสตาร์ท Command Prompt
    echo.
    pause
    exit /b 1
)
echo ✅ Composer พร้อมใช้งาน

:: Install PHP dependencies
echo.
echo [3/8] ติดตั้ง PHP Dependencies...
composer install --no-dev --optimize-autoloader
if %errorlevel% neq 0 (
    echo ❌ การติดตั้ง dependencies ล้มเหลว
    pause
    exit /b 1
)
echo ✅ ติดตั้ง PHP Dependencies เสร็จสิ้น

:: Copy environment file
echo.
echo [4/8] ตั้งค่า Environment...
if not exist .env (
    copy .env.example .env
    echo ✅ สร้างไฟล์ .env เรียบร้อย
) else (
    echo ✅ ไฟล์ .env มีอยู่แล้ว
)

:: Generate application key
echo.
echo [5/8] สร้าง Application Key...
php artisan key:generate
if %errorlevel% neq 0 (
    echo ❌ การสร้าง application key ล้มเหลว
    pause
    exit /b 1
)
echo ✅ สร้าง Application Key เสร็จสิ้น

:: Reset and setup database
echo.
echo [6/8] ตั้งค่าฐานข้อมูล...
echo ⚠️  กำลังรีเซ็ตฐานข้อมูลทั้งหมด...

:: Drop all tables and recreate
php artisan migrate:fresh --force
if %errorlevel% neq 0 (
    echo ❌ การรีเซ็ตฐานข้อมูลล้มเหลว
    pause
    exit /b 1
)
echo ✅ รีเซ็ตฐานข้อมูลเสร็จสิ้น

:: Seed database with initial data
echo.
echo [7/8] เพิ่มข้อมูลเริ่มต้น...
php artisan db:seed --force
if %errorlevel% neq 0 (
    echo ❌ การเพิ่มข้อมูลเริ่มต้นล้มเหลว
    pause
    exit /b 1
)
echo ✅ เพิ่มข้อมูลเริ่มต้นเสร็จสิ้น

:: Create storage link
echo.
echo [8/8] สร้าง Storage Link...
php artisan storage:link
if %errorlevel% neq 0 (
    echo ⚠️  การสร้าง storage link ล้มเหลว (อาจมีอยู่แล้ว)
) else (
    echo ✅ สร้าง Storage Link เสร็จสิ้น
)

:: Clear all caches
echo.
echo [9/8] ล้าง Cache...
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
echo ✅ ล้าง Cache เสร็จสิ้น

echo.
echo ========================================
echo    ✅ การติดตั้งเสร็จสิ้น!
echo ========================================
echo.
echo 📋 ข้อมูลการเข้าสู่ระบบ:
echo.
echo 👤 Admin Account:
echo    Email: admin@school.com
echo    Password: password
echo.
echo 👨‍🏫 Teacher Account:
echo    Email: teacher@school.com
echo    Password: password
echo.
echo 👨‍🎓 Student Account:
echo    Email: student@school.com
echo    Password: password
echo.
echo 🌐 เริ่มต้นเซิร์ฟเวอร์:
echo    php artisan serve
echo.
echo 📁 ข้อมูลเพิ่มเติม:
echo    - ฐานข้อมูล: database/database.sqlite
echo    - Logs: storage/logs/laravel.log
echo    - Storage: storage/app/public/
echo.
echo 🎉 ระบบพร้อมใช้งานแล้ว!
echo.
pause
