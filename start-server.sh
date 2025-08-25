#!/bin/bash

echo "========================================"
echo "   Laravel Development Server"
echo "   Attendance Management System - Lamphun Technical College"
echo "========================================"
echo

# Check if .env exists
if [ ! -f .env ]; then
    echo "ERROR: .env file not found"
    echo "Please run ./install.sh first"
    exit 1
fi

# Check if database exists
if [ ! -f database/database.sqlite ]; then
    echo "ERROR: Database file not found"
    echo "Please run ./install.sh first"
    exit 1
fi

# Clear cache
echo "[1/4] Clearing Cache..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
echo "✓ Cache cleared successfully"

# Optimize application
echo
echo "[2/4] Optimizing Application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "✓ Optimization completed"

# Check database connection
echo
echo "[3/4] Checking Database..."
php artisan migrate:status
if [ $? -ne 0 ]; then
    echo "ERROR: Cannot connect to database"
    exit 1
fi
echo "✓ Database is ready"

# Start server
echo
echo "[4/4] Starting Server..."
echo
echo "========================================"
echo "   Server Started Successfully!"
echo "========================================"
echo
echo "URL: http://localhost:8000"
echo
echo "Press Ctrl+C to stop the server"
echo
echo "========================================"
echo

php artisan serve --host=0.0.0.0 --port=8000
