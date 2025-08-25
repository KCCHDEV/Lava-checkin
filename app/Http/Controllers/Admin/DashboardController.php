<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\CheckIn;
use App\Models\Attendance;
use App\Models\Subject;
use App\Models\Announcement;
use App\Models\Blog;
use App\Models\SchoolSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isAdmin()) {
                abort(403, 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
            }
            return $next($request);
        });
    }

    public function index()
    {
        // ข้อมูลสถิติทั่วไป
        $stats = $this->getGeneralStats();
        
        // ข้อมูลการเช็คชื่อวันนี้
        $todayStats = $this->getTodayStats();
        
        // กราฟการเช็คชื่อ 7 วันล่าสุด
        $weeklyStats = $this->getWeeklyStats();
        
        // รายการล่าสุด
        $recentActivities = $this->getRecentActivities();
        
        // ข้อมูลโรงเรียน
        $schoolInfo = $this->getSchoolInfo();
        
        // Quick actions
        $quickStats = $this->getQuickStats();

        return view('admin.dashboard.index', compact(
            'stats',
            'todayStats', 
            'weeklyStats',
            'recentActivities',
            'schoolInfo',
            'quickStats'
        ));
    }

    private function getGeneralStats()
    {
        return [
            'total_students' => Student::count(),
            'active_students' => Student::where('status', 'active')->count(),
            'total_teachers' => User::where('role', 'teacher')->count(),
            'total_subjects' => Subject::count(),
            'total_announcements' => Announcement::count(),
            'published_blogs' => Blog::where('status', 'published')->count(),
        ];
    }

    private function getTodayStats()
    {
        $today = Carbon::today();
        
        return [
            'total_checkins' => CheckIn::whereDate('check_in_date', $today)->count(),
            'present_count' => CheckIn::whereDate('check_in_date', $today)
                ->where('status', 'present')->count(),
            'late_count' => CheckIn::whereDate('check_in_date', $today)
                ->where('status', 'late')->count(),
            'attendance_rate' => $this->calculateAttendanceRate($today),
            'recent_checkins' => CheckIn::with('student')
                ->whereDate('check_in_date', $today)
                ->orderBy('check_in_time', 'desc')
                ->limit(5)
                ->get(),
        ];
    }

    private function getWeeklyStats()
    {
        $days = [];
        $checkins = [];
        $attendances = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $days[] = $date->format('M d');
            
            $checkins[] = CheckIn::whereDate('check_in_date', $date)->count();
            $attendances[] = Attendance::whereDate('date', $date)->count();
        }

        return [
            'days' => $days,
            'checkins' => $checkins,
            'attendances' => $attendances,
        ];
    }

    private function getRecentActivities()
    {
        $activities = collect();

        // Recent check-ins
        $recentCheckins = CheckIn::with('student', 'recorder')
            ->whereDate('check_in_date', Carbon::today())
            ->orderBy('check_in_time', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($checkin) {
                return [
                    'type' => 'checkin',
                    'title' => "เช็คชื่อ: {$checkin->student->name}",
                    'description' => "สถานะ: {$checkin->status_text}",
                    'time' => $checkin->check_in_time,
                    'icon' => 'fa-qrcode',
                    'color' => $checkin->status_color,
                ];
            });

        // Recent announcements
        $recentAnnouncements = Announcement::orderBy('created_at', 'desc')
            ->limit(2)
            ->get()
            ->map(function ($announcement) {
                return [
                    'type' => 'announcement',
                    'title' => "ประกาศ: {$announcement->title}",
                    'description' => \Str::limit($announcement->content, 50),
                    'time' => $announcement->created_at,
                    'icon' => 'fa-bullhorn',
                    'color' => 'primary',
                ];
            });

        // Recent blogs
        $recentBlogs = Blog::where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->limit(2)
            ->get()
            ->map(function ($blog) {
                return [
                    'type' => 'blog',
                    'title' => "บทความ: {$blog->title}",
                    'description' => \Str::limit($blog->excerpt, 50),
                    'time' => $blog->created_at,
                    'icon' => 'fa-newspaper',
                    'color' => 'info',
                ];
            });

        return $activities
            ->merge($recentCheckins)
            ->merge($recentAnnouncements)
            ->merge($recentBlogs)
            ->sortByDesc('time')
            ->take(10);
    }

    private function getSchoolInfo()
    {
        return [
            'name' => SchoolSetting::getValue('school_name', 'โรงเรียน'),
            'address' => SchoolSetting::getValue('school_address', ''),
            'phone' => SchoolSetting::getValue('school_phone', ''),
            'email' => SchoolSetting::getValue('school_email', ''),
            'logo' => SchoolSetting::getValue('school_logo', ''),
            'principal' => SchoolSetting::getValue('principal_name', ''),
            'academic_year' => SchoolSetting::getValue('academic_year', date('Y')),
            'semester' => SchoolSetting::getValue('current_semester', '1'),
        ];
    }

    private function getQuickStats()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        
        return [
            'pending_tasks' => $this->getPendingTasksCount(),
            'low_attendance_classes' => $this->getLowAttendanceClasses(),
            'monthly_checkins' => CheckIn::whereDate('check_in_date', '>=', $thisMonth)->count(),
            'system_alerts' => $this->getSystemAlerts(),
        ];
    }

    private function calculateAttendanceRate($date)
    {
        $totalStudents = Student::where('status', 'active')->count();
        if ($totalStudents == 0) return 0;
        
        $presentStudents = CheckIn::whereDate('check_in_date', $date)
            ->whereIn('status', ['present', 'late'])
            ->count();
            
        return round(($presentStudents / $totalStudents) * 100, 1);
    }

    private function getPendingTasksCount()
    {
        return [
            'unpublished_announcements' => Announcement::where('status', 'draft')->count(),
            'draft_blogs' => Blog::where('status', 'draft')->count(),
            'inactive_students' => Student::where('status', 'inactive')->count(),
        ];
    }

    private function getLowAttendanceClasses()
    {
        // สามารถปรับปรุงเพิ่มเติมได้ตามโครงสร้างชั้นเรียน
        return collect();
    }

    private function getSystemAlerts()
    {
        $alerts = [];
        
        // ตรวจสอบข้อมูลโรงเรียนที่ยังไม่ได้กรอก
        if (!SchoolSetting::getValue('school_name')) {
            $alerts[] = [
                'type' => 'warning',
                'message' => 'กรุณาตั้งค่าข้อมูลโรงเรียน',
                'action' => route('admin.settings.index'),
            ];
        }
        
        // ตรวจสอบการสำรองข้อมูล
        $lastBackup = SchoolSetting::getValue('last_backup_date');
        if (!$lastBackup || Carbon::parse($lastBackup)->addDays(7)->isPast()) {
            $alerts[] = [
                'type' => 'danger',
                'message' => 'ควรสำรองข้อมูลแล้ว',
                'action' => route('admin.database.index'),
            ];
        }

        return $alerts;
    }

    /**
     * API endpoint for real-time dashboard data
     */
    public function apiStats(Request $request)
    {
        $stats = [
            'today_checkins' => CheckIn::whereDate('check_in_date', Carbon::today())->count(),
            'today_present' => CheckIn::whereDate('check_in_date', Carbon::today())
                ->where('status', 'present')->count(),
            'today_late' => CheckIn::whereDate('check_in_date', Carbon::today())
                ->where('status', 'late')->count(),
            'attendance_rate' => $this->calculateAttendanceRate(Carbon::today()),
            'timestamp' => Carbon::now()->format('H:i:s'),
        ];

        return response()->json($stats);
    }
}