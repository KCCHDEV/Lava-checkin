# Laravel Attendance System - Installation Guide

## 📋 Overview

ระบบเช็คชื่อการมาเรียนสำหรับโรงเรียน วิทยาลัยเทคนิคลำพูน พัฒนาด้วย Laravel Framework

## 🚀 Quick Installation

### Windows (General)

1. **ดาวน์โหลดและติดตั้ง:**
   - PHP 8.1+ จาก https://windows.php.net/download/
   - Composer จาก https://getcomposer.org/download/

2. **รัน Install Script:**
   ```cmd
   install.bat
   ```

### Windows (XAMPP)

1. **ติดตั้ง XAMPP:**
   - ดาวน์โหลดจาก https://www.apachefriends.org/
   - ติดตั้ง XAMPP

2. **รัน Install Script:**
   ```cmd
   install-xampp.bat
   ```

### Linux/macOS

1. **ติดตั้ง Dependencies:**
   ```bash
   # Ubuntu/Debian
   sudo apt install php php-cli php-mbstring php-xml php-sqlite3 composer

   # CentOS/RHEL
   sudo yum install php php-cli php-mbstring php-xml php-sqlite3 composer

   # macOS
   brew install php composer
   ```

2. **รัน Install Script:**
   ```bash
   chmod +x install.sh
   ./install.sh
   ```

## 🔄 Reset Database

หากต้องการรีเซ็ตฐานข้อมูลและเริ่มต้นใหม่:

### Windows (General)
```cmd
reset-database.bat
```

### Windows (XAMPP)
```cmd
reset-database-xampp.bat
```

### Linux/macOS
```bash
chmod +x reset-database.sh
./reset-database.sh
```

## 📊 Default Accounts

หลังจากติดตั้งเสร็จ ระบบจะมีบัญชีเริ่มต้นดังนี้:

### 👤 Administrator
- **Email:** admin@school.com
- **Password:** password
- **สิทธิ์:** จัดการระบบทั้งหมด

### 👨‍🏫 Teacher
- **Email:** teacher@school.com
- **Password:** password
- **สิทธิ์:** จัดการนักเรียน, เช็คชื่อ, จัดการประกาศและบทความ

### 👨‍🎓 Student
- **Email:** student@school.com
- **Password:** password
- **สิทธิ์:** ดูประวัติการเข้าเรียนของตัวเอง

## 🌐 Starting the Server

### Development Server
```bash
php artisan serve
```
เปิดเบราว์เซอร์ไปที่: http://localhost:8000

### Production (XAMPP)
1. เปิด XAMPP Control Panel
2. Start Apache
3. เปิดเบราว์เซอร์ไปที่: http://localhost/your-project-folder

## 📁 Project Structure

```
├── app/
│   ├── Http/Controllers/     # Controllers
│   ├── Models/              # Eloquent Models
│   └── Providers/           # Service Providers
├── database/
│   ├── migrations/          # Database Migrations
│   ├── seeders/            # Database Seeders
│   └── database.sqlite     # SQLite Database
├── resources/
│   └── views/              # Blade Templates
├── routes/
│   └── web.php             # Web Routes
├── storage/
│   ├── app/public/         # Public Storage
│   └── logs/               # Application Logs
├── install.bat             # Windows Install Script
├── install-xampp.bat       # XAMPP Install Script
├── install.sh              # Linux/macOS Install Script
├── reset-database.bat      # Windows Reset Script
├── reset-database-xampp.bat # XAMPP Reset Script
└── reset-database.sh       # Linux/macOS Reset Script
```

## 🔧 Features

### 📚 Core Features
- ✅ จัดการนักเรียน (CRUD)
- ✅ จัดการวิชาเรียน
- ✅ เช็คชื่อทั่วไป
- ✅ เช็คชื่อรายวิชา
- ✅ เช็คเข้าแถว
- ✅ ระบบ Login/Logout
- ✅ Role-based Access Control

### 🆕 New Features
- ✅ ระบบประกาศ (Announcements)
- ✅ ระบบบล็อก (Blogs)
- ✅ ระบบตั้งค่าโรงเรียน (School Settings)
- ✅ หน้าหลักใหม่ (Modern Landing Page)
- ✅ ระบบ Cache
- ✅ Error Handling
- ✅ Responsive Design

### 🎨 UI/UX Improvements
- ✅ Modern Bootstrap 5 Design
- ✅ Responsive Layout
- ✅ Dynamic Color Scheme
- ✅ Interactive Dashboard
- ✅ User-friendly Forms
- ✅ Loading States
- ✅ Success/Error Messages

## 🛠️ Manual Installation

หากไม่ต้องการใช้ install script:

### 1. Install Dependencies
```bash
composer install
```

### 2. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Database Setup
```bash
php artisan migrate:fresh --force
php artisan db:seed --force
```

### 4. Storage Setup
```bash
php artisan storage:link
```

### 5. Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

## 🔍 Troubleshooting

### Common Issues

#### 1. PHP Not Found
- ตรวจสอบว่า PHP ติดตั้งแล้ว
- ตรวจสอบ PATH environment variable
- สำหรับ XAMPP: ใช้ `C:\xampp\php\php.exe`

#### 2. Composer Not Found
- ติดตั้ง Composer จาก https://getcomposer.org/download/
- รีสตาร์ท Command Prompt/Terminal

#### 3. Database Errors
- ตรวจสอบสิทธิ์การเขียนไฟล์ในโฟลเดอร์ `database/`
- ลบไฟล์ `database/database.sqlite` และรัน install script ใหม่

#### 4. Storage Link Error
- ลบ symbolic link เดิม: `rm public/storage`
- รัน: `php artisan storage:link`

#### 5. Permission Errors (Linux/macOS)
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

## 📞 Support

หากมีปัญหาในการติดตั้ง:
1. ตรวจสอบ error messages ใน terminal
2. ตรวจสอบ log files ใน `storage/logs/`
3. ตรวจสอบ requirements ใน `composer.json`

## 🎯 Next Steps

หลังจากติดตั้งเสร็จ:
1. เข้าสู่ระบบด้วยบัญชี Admin
2. ไปที่ "ตั้งค่าระบบ" เพื่อปรับแต่งข้อมูลโรงเรียน
3. เพิ่มประกาศและบทความ
4. เริ่มใช้งานระบบ

---

**พัฒนาโดย:** ทีมพัฒนาระบบโรงเรียน  
**เวอร์ชัน:** 2.0.0  
**อัปเดตล่าสุด:** 25 สิงหาคม 2025
