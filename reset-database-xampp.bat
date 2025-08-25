@echo off
chcp 65001 >nul
setlocal enabledelayedexpansion

echo.
echo ========================================
echo    รีเซ็ตฐานข้อมูล Laravel Attendance System
echo    สำหรับ XAMPP
echo ========================================
echo.

echo ⚠️  คำเตือน: การดำเนินการนี้จะลบข้อมูลทั้งหมดในฐานข้อมูล!
echo.
set /p confirm="คุณแน่ใจหรือไม่ที่จะรีเซ็ตฐานข้อมูล? (y/N): "
if /i not "%confirm%"=="y" (
    echo การดำเนินการถูกยกเลิก
    pause
    exit /b 0
)

:: Check if XAMPP PHP is available
echo.
echo [1/4] ตรวจสอบ XAMPP PHP...
if exist "C:\xampp\php\php.exe" (
    set PHP_PATH=C:\xampp\php\php.exe
    echo ✅ พบ XAMPP PHP ที่: %PHP_PATH%
) else (
    echo ❌ ไม่พบ XAMPP PHP
    echo กรุณาติดตั้ง XAMPP ก่อน
    pause
    exit /b 1
)

echo.
echo [2/4] รีเซ็ตฐานข้อมูล...
%PHP_PATH% artisan migrate:fresh --force
if %errorlevel% neq 0 (
    echo ❌ การรีเซ็ตฐานข้อมูลล้มเหลว
    pause
    exit /b 1
)
echo ✅ รีเซ็ตฐานข้อมูลเสร็จสิ้น

echo.
echo [3/4] เพิ่มข้อมูลเริ่มต้น...
%PHP_PATH% artisan db:seed --force
if %errorlevel% neq 0 (
    echo ❌ การเพิ่มข้อมูลเริ่มต้นล้มเหลว
    pause
    exit /b 1
)
echo ✅ เพิ่มข้อมูลเริ่มต้นเสร็จสิ้น

echo.
echo [4/4] ล้าง Cache...
%PHP_PATH% artisan config:clear
%PHP_PATH% artisan cache:clear
%PHP_PATH% artisan view:clear
%PHP_PATH% artisan route:clear
echo ✅ ล้าง Cache เสร็จสิ้น

echo.
echo ========================================
echo    ✅ รีเซ็ตฐานข้อมูลเสร็จสิ้น!
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
echo 🎉 ระบบพร้อมใช้งานแล้ว!
echo.
pause
