#!/bin/bash

# Set text encoding
export LANG=en_US.UTF-8

echo ""
echo "========================================"
echo "   Reset Database - Laravel Attendance System"
echo "========================================"
echo ""

echo "⚠️  Warning: This will delete all data in the database!"
echo ""
read -p "Are you sure you want to reset the database? (y/N): " confirm
if [[ ! $confirm =~ ^[Yy]$ ]]; then
    echo "Operation cancelled"
    exit 0
fi

echo ""
echo "[1/4] Checking PHP..."
if ! command -v php &> /dev/null; then
    echo "❌ PHP is not installed"
    exit 1
fi
echo "✅ PHP is ready"

echo ""
echo "[2/4] Resetting Database..."
php artisan migrate:fresh --force
if [ $? -ne 0 ]; then
    echo "❌ Failed to reset database"
    exit 1
fi
echo "✅ Database reset completed"

echo ""
echo "[3/4] Adding Initial Data..."
php artisan db:seed --force
if [ $? -ne 0 ]; then
    echo "❌ Failed to add initial data"
    exit 1
fi
echo "✅ Initial data added successfully"

echo ""
echo "[4/4] Clearing Cache..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
echo "✅ Cache cleared successfully"

echo ""
echo "========================================"
echo "   ✅ Database Reset Completed!"
echo "========================================"
echo ""
echo "📋 Login Information:"
echo ""
echo "👤 Admin Account:"
echo "   Email: admin@school.com"
echo "   Password: password"
echo ""
echo "👨‍🏫 Teacher Account:"
echo "   Email: teacher@school.com"
echo "   Password: password"
echo ""
echo "👨‍🎓 Student Account:"
echo "   Email: student@school.com"
echo "   Password: password"
echo ""
echo "🎉 System is ready to use!"
echo ""
