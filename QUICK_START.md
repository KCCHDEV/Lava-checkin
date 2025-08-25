# 🚀 Quick Start Guide

## 📋 For Windows

### First Time Installation
1. Open Command Prompt in the project folder
2. Run one of the following commands:

**For XAMPP (Recommended):**
```cmd
install-xampp.bat
```

**For PHP installed in PATH:**
```cmd
install.bat
```

### Starting the Application
```cmd
start-server.bat
```

### Creating Backup
```cmd
backup.bat
```

## 📋 For Linux/macOS

### First Time Installation
1. Open Terminal in the project folder
2. Give execute permissions to files:
```bash
chmod +x install.sh start-server.sh backup.sh
```
3. Run the command:
```bash
./install.sh
```

### Starting the Application
```bash
./start-server.sh
```

### Creating Backup
```bash
./backup.sh
```

## 🌐 Accessing the System

### URLs
- **Homepage**: http://localhost:8000
- **Login**: http://localhost:8000/login

### Default Login Information
- **Email**: admin@example.com
- **Password**: password
- **Role**: Administrator

## 🔧 Common Commands

### Clearing Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Optimization
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Checking Migration Status
```bash
php artisan migrate:status
```

### Running New Migrations
```bash
php artisan migrate
```

### Adding Initial Data
```bash
php artisan db:seed
```

## 📁 Important File Structure

```
├── install.bat              # Install Windows (PHP in PATH)
├── install-xampp.bat        # Install Windows (XAMPP)
├── install.sh               # Install Linux/macOS
├── start-server.bat         # Start server Windows
├── start-server.sh          # Start server Linux/macOS
├── backup.bat               # Backup Windows
├── backup.sh                # Backup Linux/macOS
├── README_INSTALL.md        # Detailed installation guide
├── QUICK_START.md           # This guide
└── .env                     # System configuration
```

## 🆘 Troubleshooting

### Problem: .env file not found
**Solution**: Run `install-xampp.bat` (for XAMPP) or `install.bat` (for PHP in PATH) or `./install.sh`

### Problem: Database file not found
**Solution**: Run `install-xampp.bat` (for XAMPP) or `install.bat` (for PHP in PATH) or `./install.sh`

### Problem: Permission Denied (Linux/macOS)
**Solution**: 
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Problem: Composer Dependencies Error
**Solution**: 
```bash
composer install --ignore-platform-reqs
```

## 📞 Contact

If you have problems or questions:
- **Email**: support@example.com
- **Phone**: 053-xxxxxx

---

**Note**: This guide is for quick usage. For detailed information, please read `README_INSTALL.md`
