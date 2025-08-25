<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\SchoolSetting;

class ThemeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isAdmin()) {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        try {
            $currentTheme = SchoolSetting::getValue('current_theme', 'default');
            $themes = $this->getAvailableThemes();
            
            return view('admin.theme.index', compact('currentTheme', 'themes'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading theme settings: ' . $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                'theme' => 'required|string',
                'primary_color' => 'required|string|regex:/^#[0-9A-F]{6}$/i',
                'secondary_color' => 'required|string|regex:/^#[0-9A-F]{6}$/i',
                'accent_color' => 'required|string|regex:/^#[0-9A-F]{6}$/i',
                'text_color' => 'required|string|regex:/^#[0-9A-F]{6}$/i',
                'background_color' => 'required|string|regex:/^#[0-9A-F]{6}$/i',
                'sidebar_color' => 'required|string|regex:/^#[0-9A-F]{6}$/i',
                'font_family' => 'required|string',
                'border_radius' => 'required|string',
                'shadow_intensity' => 'required|string',
            ]);

            // Update theme settings
            SchoolSetting::setValue('current_theme', $request->theme);
            SchoolSetting::setValue('primary_color', $request->primary_color);
            SchoolSetting::setValue('secondary_color', $request->secondary_color);
            SchoolSetting::setValue('accent_color', $request->accent_color);
            SchoolSetting::setValue('text_color', $request->text_color);
            SchoolSetting::setValue('background_color', $request->background_color);
            SchoolSetting::setValue('sidebar_color', $request->sidebar_color);
            SchoolSetting::setValue('font_family', $request->font_family);
            SchoolSetting::setValue('border_radius', $request->border_radius);
            SchoolSetting::setValue('shadow_intensity', $request->shadow_intensity);

            // Clear cache
            Cache::forget('theme_settings');
            Cache::forget('school_settings');

            return back()->with('success', 'Theme updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating theme: ' . $e->getMessage());
        }
    }

    public function preview(Request $request)
    {
        try {
            $theme = $request->input('theme', 'default');
            $themes = $this->getAvailableThemes();
            
            if (!isset($themes[$theme])) {
                return response()->json(['error' => 'Theme not found'], 404);
            }

            $selectedTheme = $themes[$theme];
            
            return response()->json([
                'success' => true,
                'theme' => $selectedTheme
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function reset()
    {
        try {
            // Reset to default theme
            SchoolSetting::setValue('current_theme', 'default');
            SchoolSetting::setValue('primary_color', '#667eea');
            SchoolSetting::setValue('secondary_color', '#764ba2');
            SchoolSetting::setValue('accent_color', '#28a745');
            SchoolSetting::setValue('text_color', '#333333');
            SchoolSetting::setValue('background_color', '#f8f9fa');
            SchoolSetting::setValue('sidebar_color', '#2c3e50');
            SchoolSetting::setValue('font_family', 'Sarabun');
            SchoolSetting::setValue('border_radius', 'medium');
            SchoolSetting::setValue('shadow_intensity', 'medium');

            // Clear cache
            Cache::forget('theme_settings');
            Cache::forget('school_settings');

            return back()->with('success', 'Theme reset to default successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error resetting theme: ' . $e->getMessage());
        }
    }

    private function getAvailableThemes()
    {
        return [
            'default' => [
                'name' => 'Default Theme',
                'description' => 'Classic blue and purple gradient theme',
                'primary_color' => '#667eea',
                'secondary_color' => '#764ba2',
                'accent_color' => '#28a745',
                'text_color' => '#333333',
                'background_color' => '#f8f9fa',
                'sidebar_color' => '#2c3e50',
                'font_family' => 'Sarabun',
                'border_radius' => 'medium',
                'shadow_intensity' => 'medium',
                'preview_image' => 'default-theme.jpg'
            ],
            'modern' => [
                'name' => 'Modern Theme',
                'description' => 'Clean and modern design with blue accents',
                'primary_color' => '#3b82f6',
                'secondary_color' => '#1e40af',
                'accent_color' => '#10b981',
                'text_color' => '#1f2937',
                'background_color' => '#ffffff',
                'sidebar_color' => '#1e293b',
                'font_family' => 'Inter',
                'border_radius' => 'large',
                'shadow_intensity' => 'high',
                'preview_image' => 'modern-theme.jpg'
            ],
            'dark' => [
                'name' => 'Dark Theme',
                'description' => 'Dark mode for better eye comfort',
                'primary_color' => '#8b5cf6',
                'secondary_color' => '#6366f1',
                'accent_color' => '#06b6d4',
                'text_color' => '#f1f5f9',
                'background_color' => '#0f172a',
                'sidebar_color' => '#1e293b',
                'font_family' => 'Inter',
                'border_radius' => 'medium',
                'shadow_intensity' => 'low',
                'preview_image' => 'dark-theme.jpg'
            ],
            'warm' => [
                'name' => 'Warm Theme',
                'description' => 'Warm and friendly orange theme',
                'primary_color' => '#f59e0b',
                'secondary_color' => '#d97706',
                'accent_color' => '#ef4444',
                'text_color' => '#374151',
                'background_color' => '#fef3c7',
                'sidebar_color' => '#92400e',
                'font_family' => 'Sarabun',
                'border_radius' => 'large',
                'shadow_intensity' => 'medium',
                'preview_image' => 'warm-theme.jpg'
            ],
            'nature' => [
                'name' => 'Nature Theme',
                'description' => 'Green and earthy tones',
                'primary_color' => '#059669',
                'secondary_color' => '#047857',
                'accent_color' => '#f59e0b',
                'text_color' => '#1f2937',
                'background_color' => '#f0fdf4',
                'sidebar_color' => '#064e3b',
                'font_family' => 'Sarabun',
                'border_radius' => 'medium',
                'shadow_intensity' => 'medium',
                'preview_image' => 'nature-theme.jpg'
            ],
            'elegant' => [
                'name' => 'Elegant Theme',
                'description' => 'Sophisticated purple and gold theme',
                'primary_color' => '#7c3aed',
                'secondary_color' => '#5b21b6',
                'accent_color' => '#fbbf24',
                'text_color' => '#1f2937',
                'background_color' => '#faf5ff',
                'sidebar_color' => '#4c1d95',
                'font_family' => 'Playfair Display',
                'border_radius' => 'small',
                'shadow_intensity' => 'high',
                'preview_image' => 'elegant-theme.jpg'
            ],
            'minimal' => [
                'name' => 'Minimal Theme',
                'description' => 'Clean and minimal design',
                'primary_color' => '#6b7280',
                'secondary_color' => '#374151',
                'accent_color' => '#3b82f6',
                'text_color' => '#111827',
                'background_color' => '#ffffff',
                'sidebar_color' => '#f9fafb',
                'font_family' => 'Inter',
                'border_radius' => 'small',
                'shadow_intensity' => 'low',
                'preview_image' => 'minimal-theme.jpg'
            ],
            'school' => [
                'name' => 'School Theme',
                'description' => 'Professional theme for educational institutions',
                'primary_color' => '#1e40af',
                'secondary_color' => '#1e3a8a',
                'accent_color' => '#dc2626',
                'text_color' => '#1f2937',
                'background_color' => '#f8fafc',
                'sidebar_color' => '#1e293b',
                'font_family' => 'Sarabun',
                'border_radius' => 'medium',
                'shadow_intensity' => 'medium',
                'preview_image' => 'school-theme.jpg'
            ]
        ];
    }
}
