<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Subject;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Redirect based on user role
        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isTeacher()) {
            return $this->teacherDashboard();
        } elseif ($user->isStudent()) {
            return redirect()->route('student.dashboard');
        }
        
        // Default dashboard for other roles
        return $this->defaultDashboard();
    }
    
    private function adminDashboard()
    {
        try {
            // ข้อมูลสถิติทั่วไป - ใช้ cache เพื่อเพิ่มประสิทธิภาพ
            $totalStudents = cache()->remember('total_students', 300, function () {
                return Student::count();
            });
            
            $totalSubjects = cache()->remember('total_subjects', 300, function () {
                return Subject::count();
            });
            
            $totalAttendances = cache()->remember('total_attendances', 300, function () {
                return Attendance::count();
            });
            
            $totalUsers = cache()->remember('total_users', 300, function () {
                return User::count();
            });

            // สถิติการเช็คชื่อวันนี้ - ใช้ single query แทน multiple queries
            $todayAttendances = cache()->remember('today_attendances_stats', 60, function () {
                return Attendance::whereDate('date', Carbon::today())
                    ->selectRaw('status, COUNT(*) as count')
                    ->groupBy('status')
                    ->pluck('count', 'status')
                    ->toArray();
            });

            $todayStats = [
                'present' => $todayAttendances['present'] ?? 0,
                'absent' => $todayAttendances['absent'] ?? 0,
                'late' => $todayAttendances['late'] ?? 0,
                'sick' => $todayAttendances['sick'] ?? 0,
            ];

            // การเช็คชื่อล่าสุด - ลดจำนวน records และ optimize relationships
            $recentAttendances = cache()->remember('recent_attendances', 60, function () {
                return Attendance::with(['student:id,name,student_id,class', 'recorder:id,name'])
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();
            });

            return view('dashboard', compact(
                'totalStudents',
                'totalSubjects', 
                'totalAttendances',
                'totalUsers',
                'todayStats',
                'recentAttendances'
            ));
        } catch (\Exception $e) {
            // Fallback values if there's an error
            $totalStudents = 0;
            $totalSubjects = 0;
            $totalAttendances = 0;
            $totalUsers = 0;
            $todayStats = [
                'present' => 0,
                'absent' => 0,
                'late' => 0,
                'sick' => 0,
            ];
            $recentAttendances = collect();

            return view('dashboard', compact(
                'totalStudents',
                'totalSubjects', 
                'totalAttendances',
                'totalUsers',
                'todayStats',
                'recentAttendances'
            ));
        }
    }
    
    private function teacherDashboard()
    {
        // Similar to admin dashboard but with teacher-specific data
        return $this->adminDashboard();
    }
    
    private function defaultDashboard()
    {
        // Default dashboard for other roles
        return $this->adminDashboard();
    }
}
