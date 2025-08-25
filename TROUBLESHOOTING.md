# 🔧 Troubleshooting Guide

## 🚨 Common Problems and Solutions

### 1. Problem: PHP Not Installed

#### Symptoms:
```
ERROR: PHP is not installed. Please install PHP first.
```

#### Solutions:

**For Windows:**
1. Install XAMPP (Recommended)
   - Download from: https://www.apachefriends.org/
   - Install following normal steps
   - Use `install-xampp.bat` instead of `install.bat`

2. Or install PHP separately
   - Download from: https://windows.php.net/download/
   - Add PHP to PATH
   - Use `install.bat`

**For Linux/macOS:**
```bash
# Ubuntu/Debian
sudo apt install php php-cli php-mbstring php-xml php-sqlite3

# CentOS/RHEL
sudo yum install php php-cli php-mbstring php-xml php-sqlite3

# macOS
brew install php
```

### 2. Problem: Composer Not Installed

#### Symptoms:
```
ERROR: Composer is not installed. Please install Composer first.
```

#### Solutions:

**For Windows:**
1. Download Composer Installer
   - Go to: https://getcomposer.org/download/
   - Download Composer-Setup.exe
   - Install following the steps

2. Or use command in PowerShell:
```powershell
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```

**For Linux/macOS:**
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### 3. Problem: .env File Not Found

#### Symptoms:
```
ERROR: .env file not found
```

#### Solutions:
```bash
# Windows (XAMPP)
.\install-xampp.bat

# Windows (PHP in PATH)
.\install.bat

# Linux/macOS
./install.sh
```

### 4. Problem: Database File Not Found

#### Symptoms:
```
ERROR: Database file not found
```

#### Solutions:
```bash
# Create SQLite database
touch database/database.sqlite

# Run migrations
php artisan migrate

# Add initial data
php artisan db:seed
```

### 5. Problem: Permission Denied (Linux/macOS)

#### Symptoms:
```
Permission denied: storage/logs/laravel.log
```

#### Solutions:
```bash
# Set permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Or change ownership
sudo chown -R $USER:$USER storage
sudo chown -R $USER:$USER bootstrap/cache
```

### 6. Problem: Database Connection Error

#### Symptoms:
```
SQLSTATE[HY000] [2002] Connection refused
```

#### Solutions:

**Check .env settings:**
```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite
```

**For SQLite:**
```bash
# Create database file
touch database/database.sqlite

# Set permissions
chmod 664 database/database.sqlite
```

### 7. Problem: Composer Dependencies Error

#### Symptoms:
```
Your requirements could not be resolved to an installable set of packages
```

#### Solutions:
```bash
# Clear cache
composer clear-cache

# Install with ignore platform requirements
composer install --ignore-platform-reqs

# Or update dependencies
composer update
```

### 8. Problem: Cache Issues

#### Symptoms:
```
View [xxx] not found
```

#### Solutions:
```bash
# Clear all cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Or optimize again
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 9. Problem: Migration Error

#### Symptoms:
```
SQLSTATE[23000]: Integrity constraint violation
```

#### Solutions:
```bash
# Reset migrations
php artisan migrate:reset

# Run migrations again
php artisan migrate

# Add initial data
php artisan db:seed
```

### 10. Problem: Server Not Starting

#### Symptoms:
```
Address already in use
```

#### Solutions:
```bash
# Change port
php artisan serve --port=8001

# Or stop process using port 8000
# Windows
netstat -ano | findstr :8000
taskkill /PID <PID> /F

# Linux/macOS
lsof -ti:8000 | xargs kill -9
```

## 🔍 System Verification

### Check PHP Version:
```bash
php --version
```

### Check Composer Version:
```bash
composer --version
```

### Check Laravel Version:
```bash
php artisan --version
```

### Check Database Connection:
```bash
php artisan migrate:status
```

### Check Cache Status:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 📞 Contact

If you still have problems, please contact:
- **Email**: support@example.com
- **Phone**: 053-xxxxxx

## 📋 Troubleshooting Checklist

- [ ] Check PHP version (must be 8.1+)
- [ ] Check Composer installation
- [ ] Check .env file
- [ ] Check database
- [ ] Check access permissions
- [ ] Clear all cache
- [ ] Run migrations again
- [ ] Add initial data
- [ ] Check log files

---

**Note**: If you still have problems, please provide the error message you received along with system information (OS, PHP version, Laravel version)
