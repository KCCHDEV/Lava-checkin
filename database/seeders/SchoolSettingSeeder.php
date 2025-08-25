<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SchoolSetting;

class SchoolSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultSettings = [
            // General Settings
            ['key' => 'school_name', 'value' => 'วิทยาลัยเทคนิคลำพูน', 'type' => 'text', 'group' => 'general', 'description' => 'ชื่อโรงเรียน'],
            ['key' => 'school_name_en', 'value' => 'Lamphun Technical College', 'type' => 'text', 'group' => 'general', 'description' => 'ชื่อโรงเรียน (ภาษาอังกฤษ)'],
            ['key' => 'school_motto', 'value' => 'สร้างสรรค์นวัตกรรม พัฒนาคุณภาพชีวิต', 'type' => 'text', 'group' => 'general', 'description' => 'คำขวัญโรงเรียน'],
            ['key' => 'school_description', 'value' => 'วิทยาลัยเทคนิคลำพูน เป็นสถาบันการศึกษาระดับอาชีวศึกษา ที่มุ่งเน้นการผลิตและพัฒนากำลังคนด้านวิชาชีพที่มีคุณภาพ', 'type' => 'textarea', 'group' => 'general', 'description' => 'คำอธิบายโรงเรียน'],
            
            // Contact Settings
            ['key' => 'school_address', 'value' => '123 ถนนหลัก ตำบลเมือง อำเภอเมือง จังหวัดลำพูน 51000', 'type' => 'textarea', 'group' => 'contact', 'description' => 'ที่อยู่โรงเรียน'],
            ['key' => 'school_phone', 'value' => '053-123456', 'type' => 'text', 'group' => 'contact', 'description' => 'เบอร์โทรศัพท์'],
            ['key' => 'school_email', 'value' => 'info@lamphuntech.ac.th', 'type' => 'text', 'group' => 'contact', 'description' => 'อีเมล'],
            ['key' => 'school_website', 'value' => 'https://www.lamphuntech.ac.th', 'type' => 'text', 'group' => 'contact', 'description' => 'เว็บไซต์'],
            ['key' => 'principal_name', 'value' => 'อาจารย์ ดร.สมชาย ใจดี', 'type' => 'text', 'group' => 'contact', 'description' => 'ชื่อผู้อำนวยการ'],
            ['key' => 'principal_phone', 'value' => '053-123457', 'type' => 'text', 'group' => 'contact', 'description' => 'เบอร์โทรผู้อำนวยการ'],
            ['key' => 'principal_email', 'value' => 'principal@lamphuntech.ac.th', 'type' => 'text', 'group' => 'contact', 'description' => 'อีเมลผู้อำนวยการ'],
            
            // Social Media
            ['key' => 'school_facebook', 'value' => 'https://facebook.com/lamphuntech', 'type' => 'text', 'group' => 'social', 'description' => 'Facebook'],
            ['key' => 'school_youtube', 'value' => 'https://youtube.com/lamphuntech', 'type' => 'text', 'group' => 'social', 'description' => 'YouTube'],
            ['key' => 'school_line', 'value' => '@lamphuntech', 'type' => 'text', 'group' => 'social', 'description' => 'Line Official'],
            ['key' => 'school_instagram', 'value' => 'https://instagram.com/lamphuntech', 'type' => 'text', 'group' => 'social', 'description' => 'Instagram'],
            
            // Academic Settings
            ['key' => 'academic_year', 'value' => date('Y'), 'type' => 'number', 'group' => 'academic', 'description' => 'ปีการศึกษา'],
            ['key' => 'semester', 'value' => '1', 'type' => 'number', 'group' => 'academic', 'description' => 'ภาคเรียน'],
            ['key' => 'school_hours', 'value' => '08:30 - 16:30 น.', 'type' => 'text', 'group' => 'academic', 'description' => 'เวลาทำการ'],
            ['key' => 'office_hours', 'value' => '08:00 - 17:00 น.', 'type' => 'text', 'group' => 'academic', 'description' => 'เวลาทำการสำนักงาน'],
            ['key' => 'break_time', 'value' => '12:00 - 13:00 น.', 'type' => 'text', 'group' => 'academic', 'description' => 'เวลาพักกลางวัน'],
            
            // Appearance Settings
            ['key' => 'primary_color', 'value' => '#667eea', 'type' => 'text', 'group' => 'appearance', 'description' => 'สีหลัก'],
            ['key' => 'secondary_color', 'value' => '#764ba2', 'type' => 'text', 'group' => 'appearance', 'description' => 'สีรอง'],
            ['key' => 'accent_color', 'value' => '#28a745', 'type' => 'text', 'group' => 'appearance', 'description' => 'สีเน้น'],
            ['key' => 'text_color', 'value' => '#333333', 'type' => 'text', 'group' => 'appearance', 'description' => 'สีข้อความ'],
            ['key' => 'background_color', 'value' => '#f8f9fa', 'type' => 'text', 'group' => 'appearance', 'description' => 'สีพื้นหลัง'],
            ['key' => 'font_family', 'value' => 'Sarabun', 'type' => 'text', 'group' => 'appearance', 'description' => 'ฟอนต์หลัก'],
            ['key' => 'font_size', 'value' => '16px', 'type' => 'text', 'group' => 'appearance', 'description' => 'ขนาดฟอนต์'],
            
            // System Settings
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean', 'group' => 'system', 'description' => 'โหมดบำรุงรักษา', 'is_public' => false],
            ['key' => 'registration_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'system', 'description' => 'เปิดใช้งานการลงทะเบียน', 'is_public' => false],
            ['key' => 'attendance_auto_close', 'value' => '17:00', 'type' => 'text', 'group' => 'system', 'description' => 'เวลาปิดการเช็คชื่ออัตโนมัติ', 'is_public' => false],
            ['key' => 'max_attendance_late_minutes', 'value' => '15', 'type' => 'number', 'group' => 'system', 'description' => 'จำนวนนาทีสูงสุดที่มาสาย', 'is_public' => false],
            ['key' => 'enable_notifications', 'value' => '1', 'type' => 'boolean', 'group' => 'system', 'description' => 'เปิดใช้งานการแจ้งเตือน', 'is_public' => false],
            ['key' => 'enable_analytics', 'value' => '1', 'type' => 'boolean', 'group' => 'system', 'description' => 'เปิดใช้งานการวิเคราะห์ข้อมูล', 'is_public' => false],
            ['key' => 'max_file_upload_size', 'value' => '2048', 'type' => 'number', 'group' => 'system', 'description' => 'ขนาดไฟล์อัปโหลดสูงสุด (KB)', 'is_public' => false],
            ['key' => 'allowed_file_types', 'value' => 'jpg,jpeg,png,gif,pdf,doc,docx', 'type' => 'text', 'group' => 'system', 'description' => 'ประเภทไฟล์ที่อนุญาต', 'is_public' => false],
        ];

        foreach ($defaultSettings as $setting) {
            SchoolSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('School settings seeded successfully!');
    }
}
