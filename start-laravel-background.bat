@echo off
cd /d "C:\xampp\htdocs"
set PATH=%PATH%;C:\xampp\php
start "Laravel Server" /min php artisan serve --host=0.0.0.0 --port=8000
