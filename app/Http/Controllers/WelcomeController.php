<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WelcomeContent;
use App\Models\Announcement;
use App\Models\Blog;
use App\Models\SchoolSetting;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    /**
     * Display the welcome page
     */
    public function index()
    {
        try {
            // Get welcome content with caching
            $welcomeContent = cache()->remember('welcome_content', 300, function () {
                return WelcomeContent::getAllContents();
            });

            // Get school information
            $schoolInfo = cache()->remember('school_info', 300, function () {
                return SchoolSetting::getSchoolInfo();
            });

            // Get recent announcements
            $announcements = cache()->remember('recent_announcements', 300, function () {
                return Announcement::published()
                    ->with('creator')
                    ->orderBy('priority', 'desc')
                    ->orderBy('published_at', 'desc')
                    ->take(5)
                    ->get();
            });

            // Get featured blogs
            $featuredBlogs = cache()->remember('featured_blogs', 300, function () {
                return Blog::published()
                    ->featured()
                    ->with('author')
                    ->orderBy('published_at', 'desc')
                    ->take(3)
                    ->get();
            });

            // Get recent blogs
            $recentBlogs = cache()->remember('recent_blogs', 300, function () {
                return Blog::published()
                    ->with('author')
                    ->orderBy('published_at', 'desc')
                    ->take(6)
                    ->get();
            });

            // Calculate statistics
            $stats = cache()->remember('welcome_stats', 300, function () {
                return [
                    'total_students' => \App\Models\Student::count(),
                    'total_subjects' => \App\Models\Subject::count(),
                    'total_announcements' => Announcement::published()->count(),
                    'total_blogs' => Blog::published()->count(),
                ];
            });

            return view('welcome', compact(
                'welcomeContent',
                'schoolInfo',
                'announcements',
                'featuredBlogs',
                'recentBlogs',
                'stats'
            ));
        } catch (\Exception $e) {
            // Fallback values if there's an error
            $welcomeContent = [];
            $schoolInfo = SchoolSetting::getSchoolInfo();
            $announcements = collect();
            $featuredBlogs = collect();
            $recentBlogs = collect();
            $stats = [
                'total_students' => 0,
                'total_subjects' => 0,
                'total_announcements' => 0,
                'total_blogs' => 0,
            ];

            return view('welcome', compact(
                'welcomeContent',
                'schoolInfo',
                'announcements',
                'featuredBlogs',
                'recentBlogs',
                'stats'
            ));
        }
    }

    /**
     * Show the welcome content management page
     */
    public function manage()
    {
        // Check if user is admin
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        $welcomeContent = WelcomeContent::getAllContents();
        return view('admin.welcome.manage', compact('welcomeContent'));
    }

    /**
     * Update welcome content
     */
    public function update(Request $request)
    {
        // Check if user is admin
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        $request->validate([
            'hero_title' => 'required|string|max:255',
            'hero_subtitle' => 'required|string|max:500',
            'about_title' => 'required|string|max:255',
            'about_content' => 'required|string',
            'programs' => 'required|array',
            'programs.*.title' => 'required|string|max:255',
            'programs.*.description' => 'required|string|max:500',
            'news' => 'required|array',
            'news.*.title' => 'required|string|max:255',
            'news.*.content' => 'required|string|max:500',
        ]);

        // Update hero section
        WelcomeContent::updateOrCreate(
            ['key' => 'hero_title'],
            ['value' => $request->hero_title, 'type' => 'text']
        );

        WelcomeContent::updateOrCreate(
            ['key' => 'hero_subtitle'],
            ['value' => $request->hero_subtitle, 'type' => 'text']
        );

        // Update about section
        WelcomeContent::updateOrCreate(
            ['key' => 'about_title'],
            ['value' => $request->about_title, 'type' => 'text']
        );

        WelcomeContent::updateOrCreate(
            ['key' => 'about_content'],
            ['value' => $request->about_content, 'type' => 'textarea']
        );

        // Update programs
        foreach ($request->programs as $index => $program) {
            WelcomeContent::updateOrCreate(
                ['key' => "program_{$index}_title"],
                ['value' => $program['title'], 'type' => 'text']
            );

            WelcomeContent::updateOrCreate(
                ['key' => "program_{$index}_description"],
                ['value' => $program['description'], 'type' => 'textarea']
            );
        }

        // Update news
        foreach ($request->news as $index => $news) {
            WelcomeContent::updateOrCreate(
                ['key' => "news_{$index}_title"],
                ['value' => $news['title'], 'type' => 'text']
            );

            WelcomeContent::updateOrCreate(
                ['key' => "news_{$index}_content"],
                ['value' => $news['content'], 'type' => 'textarea']
            );
        }

        // Clear cache
        cache()->forget('welcome_content');
        cache()->forget('welcome_stats');

        return redirect()->back()
            ->with('success', 'อัปเดตเนื้อหาหน้าหลักเรียบร้อยแล้ว');
    }

    /**
     * Toggle content status
     */
    public function toggleStatus($id)
    {
        // Check if user is admin
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        $content = WelcomeContent::findOrFail($id);
        $content->update(['is_active' => !$content->is_active]);

        // Clear cache
        cache()->forget('welcome_content');
        cache()->forget('welcome_stats');

        return redirect()->back()
            ->with('success', 'อัปเดตสถานะเรียบร้อยแล้ว');
    }
}
