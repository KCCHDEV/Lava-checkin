<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolSetting;
use Illuminate\Support\Facades\Cache;

class SchoolSettingController extends Controller
{
    /**
     * Display settings management page
     */
    public function index()
    {
        // Check if user is admin
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        $groups = [
            'general' => 'ข้อมูลทั่วไป',
            'contact' => 'ข้อมูลติดต่อ',
            'social' => 'โซเชียลมีเดีย',
            'academic' => 'ข้อมูลวิชาการ',
            'appearance' => 'การแสดงผล',
            'system' => 'การตั้งค่าระบบ'
        ];

        $settings = [];
        foreach ($groups as $group => $label) {
            $settings[$group] = SchoolSetting::byGroup($group)->get();
        }

        return view('admin.settings.index', compact('settings', 'groups'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        // Check if user is admin
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        $request->validate([
            'settings' => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'nullable',
        ]);

        foreach ($request->settings as $setting) {
            if (isset($setting['key']) && isset($setting['value'])) {
                SchoolSetting::setValue(
                    $setting['key'],
                    $setting['value'],
                    $setting['type'] ?? 'text',
                    $setting['group'] ?? 'general',
                    $setting['description'] ?? null,
                    $setting['is_public'] ?? true
                );
            }
        }

        // Clear cache
        Cache::forget('school_settings');

        return redirect()->back()
            ->with('success', 'อัปเดตการตั้งค่าเรียบร้อยแล้ว');
    }

    /**
     * Store new setting
     */
    public function store(Request $request)
    {
        // Check if user is admin
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        $request->validate([
            'key' => 'required|string|unique:school_settings,key',
            'value' => 'nullable',
            'type' => 'required|in:text,textarea,image,number,boolean,json',
            'group' => 'required|in:general,contact,social,academic,appearance,system',
            'description' => 'nullable|string',
            'is_public' => 'boolean',
        ]);

        SchoolSetting::create($request->all());

        // Clear cache
        Cache::forget('school_settings');

        return redirect()->back()
            ->with('success', 'เพิ่มการตั้งค่าเรียบร้อยแล้ว');
    }

    /**
     * Delete setting
     */
    public function destroy(SchoolSetting $setting)
    {
        // Check if user is admin
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        $setting->delete();

        // Clear cache
        Cache::forget('school_settings');

        return redirect()->back()
            ->with('success', 'ลบการตั้งค่าเรียบร้อยแล้ว');
    }

    /**
     * Get school information for public use
     */
    public function getSchoolInfo()
    {
        return response()->json(SchoolSetting::getSchoolInfo());
    }

    /**
     * Get theme settings for public use
     */
    public function getThemeSettings()
    {
        return response()->json(SchoolSetting::getThemeSettings());
    }

    /**
     * Initialize default settings
     */
    public function initializeDefaults()
    {
        // Check if user is admin
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

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
            
            // Social Media
            ['key' => 'school_facebook', 'value' => 'https://facebook.com/lamphuntech', 'type' => 'text', 'group' => 'social', 'description' => 'Facebook'],
            ['key' => 'school_youtube', 'value' => 'https://youtube.com/lamphuntech', 'type' => 'text', 'group' => 'social', 'description' => 'YouTube'],
            ['key' => 'school_line', 'value' => '@lamphuntech', 'type' => 'text', 'group' => 'social', 'description' => 'Line Official'],
            
            // Academic Settings
            ['key' => 'academic_year', 'value' => date('Y'), 'type' => 'number', 'group' => 'academic', 'description' => 'ปีการศึกษา'],
            ['key' => 'semester', 'value' => '1', 'type' => 'number', 'group' => 'academic', 'description' => 'ภาคเรียน'],
            ['key' => 'school_hours', 'value' => '08:30 - 16:30 น.', 'type' => 'text', 'group' => 'academic', 'description' => 'เวลาทำการ'],
            ['key' => 'office_hours', 'value' => '08:00 - 17:00 น.', 'type' => 'text', 'group' => 'academic', 'description' => 'เวลาทำการสำนักงาน'],
            
            // Appearance Settings
            ['key' => 'primary_color', 'value' => '#667eea', 'type' => 'text', 'group' => 'appearance', 'description' => 'สีหลัก'],
            ['key' => 'secondary_color', 'value' => '#764ba2', 'type' => 'text', 'group' => 'appearance', 'description' => 'สีรอง'],
            ['key' => 'accent_color', 'value' => '#28a745', 'type' => 'text', 'group' => 'appearance', 'description' => 'สีเน้น'],
            ['key' => 'font_family', 'value' => 'Sarabun', 'type' => 'text', 'group' => 'appearance', 'description' => 'ฟอนต์หลัก'],
            
            // System Settings
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean', 'group' => 'system', 'description' => 'โหมดบำรุงรักษา', 'is_public' => false],
            ['key' => 'registration_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'system', 'description' => 'เปิดใช้งานการลงทะเบียน', 'is_public' => false],
            ['key' => 'attendance_auto_close', 'value' => '17:00', 'type' => 'text', 'group' => 'system', 'description' => 'เวลาปิดการเช็คชื่ออัตโนมัติ', 'is_public' => false],
            ['key' => 'max_attendance_late_minutes', 'value' => '15', 'type' => 'number', 'group' => 'system', 'description' => 'จำนวนนาทีสูงสุดที่มาสาย', 'is_public' => false],
        ];

        foreach ($defaultSettings as $setting) {
            SchoolSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        // Clear cache
        Cache::forget('school_settings');

        return redirect()->back()
            ->with('success', 'ตั้งค่าเริ่มต้นเรียบร้อยแล้ว');
    }
}
