#!/bin/bash

# Set text encoding
export LANG=en_US.UTF-8

echo ""
echo "========================================"
echo "   Laravel Attendance System Installer"
echo "========================================"
echo ""

# Check if PHP is installed
echo "[1/8] Checking PHP..."
if ! command -v php &> /dev/null; then
    echo "❌ PHP is not installed. Please install PHP first."
    echo ""
    echo "Installation methods:"
    echo "Ubuntu/Debian: sudo apt install php php-cli php-mbstring php-xml php-sqlite3"
    echo "CentOS/RHEL: sudo yum install php php-cli php-mbstring php-xml php-sqlite3"
    echo "macOS: brew install php"
    echo ""
    exit 1
fi
echo "✅ PHP is ready"

# Check if Composer is installed
echo ""
echo "[2/8] Checking Composer..."
if ! command -v composer &> /dev/null; then
    echo "❌ Composer is not installed. Please install Composer first."
    echo ""
    echo "Installation:"
    echo "curl -sS https://getcomposer.org/installer | php"
    echo "sudo mv composer.phar /usr/local/bin/composer"
    echo ""
    exit 1
fi
echo "✅ Composer is ready"

# Install PHP dependencies
echo ""
echo "[3/8] Installing PHP Dependencies..."
composer install --no-dev --optimize-autoloader
if [ $? -ne 0 ]; then
    echo "❌ Failed to install dependencies"
    exit 1
fi
echo "✅ PHP Dependencies installed successfully"

# Copy environment file
echo ""
echo "[4/8] Setting up Environment..."
if [ ! -f .env ]; then
    cp .env.example .env
    echo "✅ Created .env from .env.example"
else
    echo "✅ .env file already exists"
fi

# Generate application key
echo ""
echo "[5/8] Generating Application Key..."
php artisan key:generate
if [ $? -ne 0 ]; then
    echo "❌ Failed to generate application key"
    exit 1
fi
echo "✅ Application Key generated successfully"

# Reset and setup database
echo ""
echo "[6/8] Setting up Database..."
echo "⚠️  Resetting all database data..."

# Drop all tables and recreate
php artisan migrate:fresh --force
if [ $? -ne 0 ]; then
    echo "❌ Failed to reset database"
    exit 1
fi
echo "✅ Database reset completed"

# Seed database with initial data
echo ""
echo "[7/8] Adding Initial Data..."
php artisan db:seed --force
if [ $? -ne 0 ]; then
    echo "❌ Failed to add initial data"
    exit 1
fi
echo "✅ Initial data added successfully"

# Create storage link
echo ""
echo "[8/8] Creating Storage Link..."
php artisan storage:link
if [ $? -ne 0 ]; then
    echo "⚠️  Failed to create storage link (may already exist)"
else
    echo "✅ Storage Link created successfully"
fi

# Clear all caches
echo ""
echo "[9/8] Clearing Cache..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
echo "✅ Cache cleared successfully"

echo ""
echo "========================================"
echo "   ✅ Installation Completed!"
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
echo "🌐 Start Server:"
echo "   php artisan serve"
echo ""
echo "📁 Additional Information:"
echo "   - Database: database/database.sqlite"
echo "   - Logs: storage/logs/laravel.log"
echo "   - Storage: storage/app/public/"
echo ""
echo "🎉 System is ready to use!"
echo ""
