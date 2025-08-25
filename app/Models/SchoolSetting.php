<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type', // text, textarea, image, number, boolean, json
        'group', // general, contact, social, academic, appearance
        'description',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    // Scopes
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeByGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    // Methods
    public static function getValue($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function setValue($key, $value, $type = 'text', $group = 'general', $description = null, $isPublic = true)
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
                'description' => $description,
                'is_public' => $isPublic,
            ]
        );
    }

    public static function getSchoolInfo()
    {
        return [
            'name' => static::getValue('school_name', 'วิทยาลัยเทคนิคลำพูน'),
            'name_en' => static::getValue('school_name_en', 'Lamphun Technical College'),
            'motto' => static::getValue('school_motto', ''),
            'description' => static::getValue('school_description', ''),
            'logo' => static::getValue('school_logo', ''),
            'banner' => static::getValue('school_banner', ''),
            'address' => static::getValue('school_address', ''),
            'phone' => static::getValue('school_phone', ''),
            'email' => static::getValue('school_email', ''),
            'website' => static::getValue('school_website', ''),
            'facebook' => static::getValue('school_facebook', ''),
            'youtube' => static::getValue('school_youtube', ''),
            'line' => static::getValue('school_line', ''),
            'principal_name' => static::getValue('principal_name', ''),
            'principal_phone' => static::getValue('principal_phone', ''),
            'principal_email' => static::getValue('principal_email', ''),
            'academic_year' => static::getValue('academic_year', date('Y')),
            'semester' => static::getValue('semester', '1'),
            'school_hours' => static::getValue('school_hours', ''),
            'office_hours' => static::getValue('office_hours', ''),
        ];
    }

    public static function getThemeSettings()
    {
        return [
            'primary_color' => static::getValue('primary_color', '#667eea'),
            'secondary_color' => static::getValue('secondary_color', '#764ba2'),
            'accent_color' => static::getValue('accent_color', '#28a745'),
            'text_color' => static::getValue('text_color', '#333333'),
            'background_color' => static::getValue('background_color', '#f8f9fa'),
            'font_family' => static::getValue('font_family', 'Sarabun'),
            'font_size' => static::getValue('font_size', '16px'),
        ];
    }

    public static function getSystemSettings()
    {
        return [
            'maintenance_mode' => static::getValue('maintenance_mode', false),
            'registration_enabled' => static::getValue('registration_enabled', true),
            'attendance_auto_close' => static::getValue('attendance_auto_close', '17:00'),
            'max_attendance_late_minutes' => static::getValue('max_attendance_late_minutes', 15),
            'enable_notifications' => static::getValue('enable_notifications', true),
            'enable_analytics' => static::getValue('enable_analytics', true),
        ];
    }
}
