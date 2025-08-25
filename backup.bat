@echo off
echo ========================================
echo    Laravel Project Backup Script
echo    Attendance Management System - Lamphun Technical College
echo ========================================
echo.

:: Set backup directory name with timestamp
set TIMESTAMP=%date:~-4,4%%date:~-10,2%%date:~-7,2%_%time:~0,2%%time:~3,2%%time:~6,2%
set TIMESTAMP=%TIMESTAMP: =0%
set BACKUP_DIR=backup_%TIMESTAMP%

echo Creating backup directory: %BACKUP_DIR%
mkdir %BACKUP_DIR%

:: Copy essential project files and folders
echo.
echo [1/6] Copying project files...
xcopy /E /I /Y app %BACKUP_DIR%\app
xcopy /E /I /Y bootstrap %BACKUP_DIR%\bootstrap
xcopy /E /I /Y config %BACKUP_DIR%\config
xcopy /E /I /Y database %BACKUP_DIR%\database
xcopy /E /I /Y public %BACKUP_DIR%\public
xcopy /E /I /Y resources %BACKUP_DIR%\resources
xcopy /E /I /Y routes %BACKUP_DIR%\routes
xcopy /E /I /Y storage %BACKUP_DIR%\storage
xcopy /E /I /Y tests %BACKUP_DIR%\tests
xcopy /E /I /Y vendor %BACKUP_DIR%\vendor
copy artisan %BACKUP_DIR%\
copy composer.json %BACKUP_DIR%\
copy composer.lock %BACKUP_DIR%\
copy package.json %BACKUP_DIR%\
copy vite.config.js %BACKUP_DIR%\
echo ✓ Project files copied successfully

:: Backup SQLite database
echo.
echo [2/6] Backing up database...
if exist database\database.sqlite (
    copy database\database.sqlite %BACKUP_DIR%\database\
    echo ✓ Database backed up successfully
) else (
    echo ! No database file found
)

:: Create database dump
echo.
echo [3/6] Creating database dump...
if exist database\database.sqlite (
    echo .dump > %BACKUP_DIR%\database_dump.sql
    sqlite3 database\database.sqlite .dump > %BACKUP_DIR%\database_dump.sql
    echo ✓ Database dump created successfully
) else (
    echo ! No database file found for dump
)

:: Create system information file
echo.
echo [4/6] Creating system information...
echo System Information > %BACKUP_DIR%\system_info.txt
echo ================== >> %BACKUP_DIR%\system_info.txt
echo. >> %BACKUP_DIR%\system_info.txt
echo Backup Date: %date% %time% >> %BACKUP_DIR%\system_info.txt
echo. >> %BACKUP_DIR%\system_info.txt
echo PHP Version: >> %BACKUP_DIR%\system_info.txt
php --version >> %BACKUP_DIR%\system_info.txt 2>&1
echo. >> %BACKUP_DIR%\system_info.txt
echo Composer Version: >> %BACKUP_DIR%\system_info.txt
composer --version >> %BACKUP_DIR%\system_info.txt 2>&1
echo. >> %BACKUP_DIR%\system_info.txt
echo Laravel Version: >> %BACKUP_DIR%\system_info.txt
php artisan --version >> %BACKUP_DIR%\system_info.txt 2>&1
echo ✓ System information created successfully

:: Create restore instructions
echo.
echo [5/6] Creating restore instructions...
echo Restore Instructions > %BACKUP_DIR%\RESTORE_INSTRUCTIONS.txt
echo ==================== >> %BACKUP_DIR%\RESTORE_INSTRUCTIONS.txt
echo. >> %BACKUP_DIR%\RESTORE_INSTRUCTIONS.txt
echo To restore this backup: >> %BACKUP_DIR%\RESTORE_INSTRUCTIONS.txt
echo 1. Extract this backup to a new directory >> %BACKUP_DIR%\RESTORE_INSTRUCTIONS.txt
echo 2. Open Command Prompt in the restored directory >> %BACKUP_DIR%\RESTORE_INSTRUCTIONS.txt
echo 3. Run: install.bat or install-xampp.bat >> %BACKUP_DIR%\RESTORE_INSTRUCTIONS.txt
echo 4. If you want to restore the database: >> %BACKUP_DIR%\RESTORE_INSTRUCTIONS.txt
echo    - Copy database_dump.sql to the new project >> %BACKUP_DIR%\RESTORE_INSTRUCTIONS.txt
echo    - Run: sqlite3 database\database.sqlite ^< database_dump.sql >> %BACKUP_DIR%\RESTORE_INSTRUCTIONS.txt
echo 5. Start the server: start-server.bat >> %BACKUP_DIR%\RESTORE_INSTRUCTIONS.txt
echo ✓ Restore instructions created successfully

:: Compress backup
echo.
echo [6/6] Compressing backup...
powershell -command "Compress-Archive -Path '%BACKUP_DIR%' -DestinationPath '%BACKUP_DIR%.zip' -Force"
if %errorlevel% equ 0 (
    echo ✓ Backup compressed successfully
    echo.
    echo ========================================
    echo    Backup Completed Successfully!
    echo ========================================
    echo.
    echo Backup file: %BACKUP_DIR%.zip
    echo Backup size: 
    for %%A in (%BACKUP_DIR%.zip) do echo %%~zA bytes
    echo.
    echo Backup location: %CD%\%BACKUP_DIR%.zip
    echo.
    echo ========================================
) else (
    echo ERROR: Failed to compress backup
    echo Backup files are in: %BACKUP_DIR%
)

:: Clean up temporary directory
if exist %BACKUP_DIR%.zip (
    rmdir /S /Q %BACKUP_DIR%
    echo Temporary files cleaned up
)

echo.
pause
