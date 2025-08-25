# Laravel Auto-Start Setup

## ไฟล์ที่สร้างขึ้น:

### 1. `start-laravel.bat`
- รัน Laravel development server แบบ interactive
- แสดงหน้าต่าง command prompt
- กด Ctrl+C เพื่อหยุด server

### 2. `start-laravel-background.bat`
- รัน Laravel development server ใน background
- ไม่แสดงหน้าต่าง command prompt
- เหมาะสำหรับ auto-start

### 3. `setup-autostart.ps1`
- PowerShell script สำหรับตั้งค่า auto-start
- ต้องรันเป็น Administrator

## วิธีตั้งค่า Auto-Start:

### วิธีที่ 1: ใช้ PowerShell Script (แนะนำ)
1. คลิกขวาที่ `setup-autostart.ps1`
2. เลือก "Run with PowerShell (Administrator)"
3. รอให้ script ทำงานเสร็จ
4. Laravel จะเริ่มต้นอัตโนมัติเมื่อ login เข้า Windows

### วิธีที่ 2: ตั้งค่าด้วยตนเอง
1. กด `Win + R` พิมพ์ `shell:startup`
2. คลิกขวาในโฟลเดอร์ Startup
3. เลือก New > Shortcut
4. Browse ไปที่ `start-laravel-background.bat`
5. ตั้งชื่อ shortcut

### วิธีที่ 3: ใช้ Task Scheduler
1. เปิด Task Scheduler
2. Create Basic Task
3. ตั้งชื่อ "Laravel Auto-Start"
4. เลือก "When I log on"
5. เลือก "Start a program"
6. Browse ไปที่ `start-laravel-background.bat`

## การใช้งาน:

### รัน Laravel แบบ Manual:
```cmd
start-laravel.bat
```

### รัน Laravel ใน Background:
```cmd
start-laravel-background.bat
```

### เข้าถึง Laravel:
- URL: `http://localhost:8000`
- Laravel จะพร้อมใช้งานหลังจากรัน batch file

## การหยุด Auto-Start:

### วิธีที่ 1: ใช้ Registry Editor
1. กด `Win + R` พิมพ์ `regedit`
2. ไปที่ `HKEY_CURRENT_USER\Software\Microsoft\Windows\CurrentVersion\Run`
3. ลบ entry ชื่อ "LaravelDevelopmentServer"

### วิธีที่ 2: ใช้ PowerShell
```powershell
Remove-ItemProperty -Path "HKCU:\Software\Microsoft\Windows\CurrentVersion\Run" -Name "LaravelDevelopmentServer"
```

## หมายเหตุ:
- ต้องมี XAMPP และ PHP ติดตั้งแล้ว
- Laravel ต้องติดตั้งใน `C:\xampp\htdocs`
- Port 8000 จะถูกใช้งานโดย Laravel
- หากมีปัญหา ให้ตรวจสอบ firewall settings
