@echo off
chcp 65001 >nul
setlocal enabledelayedexpansion

echo.
echo ========================================
echo    รีเซ็ตฐานข้อมูล Laravel Attendance System
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

echo.
echo [1/4] ตรวจสอบ PHP...
php --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ PHP ไม่ได้ติดตั้ง
    pause
    exit /b 1
)
echo ✅ PHP พร้อมใช้งาน

echo.
echo [2/4] รีเซ็ตฐานข้อมูล...
php artisan migrate:fresh --force
if %errorlevel% neq 0 (
    echo ❌ การรีเซ็ตฐานข้อมูลล้มเหลว
    pause
    exit /b 1
)
echo ✅ รีเซ็ตฐานข้อมูลเสร็จสิ้น

echo.
echo [3/4] เพิ่มข้อมูลเริ่มต้น...
php artisan db:seed --force
if %errorlevel% neq 0 (
    echo ❌ การเพิ่มข้อมูลเริ่มต้นล้มเหลว
    pause
    exit /b 1
)
echo ✅ เพิ่มข้อมูลเริ่มต้นเสร็จสิ้น

echo.
echo [4/4] ล้าง Cache...
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
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
