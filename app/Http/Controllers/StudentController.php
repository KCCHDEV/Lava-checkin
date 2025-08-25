<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\SubjectAttendance;
use App\Models\LineAttendance;
use App\Models\User;
use App\Models\SubjectAttendance as SubjectAttendanceModel;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        try {
            $query = Student::query();

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('student_id', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%")
                      ->orWhere('phone', 'LIKE', "%{$search}%")
                      ->orWhere('class', 'LIKE', "%{$search}%")
                      ->orWhere('major', 'LIKE', "%{$search}%");
                });
            }

            // Filter by class
            if ($request->filled('class')) {
                $query->where('class', $request->class);
            }

            // Filter by major
            if ($request->filled('major')) {
                $query->where('major', $request->major);
            }

            // Filter by status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Sort functionality
            $sortBy = $request->get('sort_by', 'name');
            $sortOrder = $request->get('sort_order', 'asc');
            
            if (in_array($sortBy, ['name', 'student_id', 'class', 'major', 'created_at'])) {
                $query->orderBy($sortBy, $sortOrder);
            }

            $students = $query->paginate(15)->withQueryString();

            // Get unique classes and majors for filters
            $classes = Student::distinct()->pluck('class')->filter()->sort();
            $majors = Student::distinct()->pluck('major')->filter()->sort();

            // Get statistics
            $stats = cache()->remember('student_stats', 300, function () {
                return [
                    'total' => Student::count(),
                    'active' => Student::where('status', 'active')->count(),
                    'inactive' => Student::where('status', 'inactive')->count(),
                    'classes' => Student::distinct('class')->count(),
                    'majors' => Student::distinct('major')->count(),
                ];
            });

            return view('students.index', compact('students', 'classes', 'majors', 'stats'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading students: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $classes = Student::distinct()->pluck('class')->filter()->sort();
            $majors = Student::distinct()->pluck('major')->filter()->sort();
            
            return view('students.create', compact('classes', 'majors'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading create form: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'student_id' => 'required|string|unique:students,student_id|max:20',
                'email' => 'nullable|email|unique:students,email',
                'phone' => 'nullable|string|max:20',
                'class' => 'required|string|max:50',
                'major' => 'required|string|max:100',
                'status' => 'required|in:active,inactive',
                'address' => 'nullable|string|max:500',
                'date_of_birth' => 'nullable|date',
                'gender' => 'nullable|in:male,female,other',
                'parent_name' => 'nullable|string|max:255',
                'parent_phone' => 'nullable|string|max:20',
            ]);

            $student = Student::create($request->all());

            // Clear cache
            Cache::forget('student_stats');

            return redirect()->route('students.index')->with('success', 'Student created successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating student: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Student $student)
    {
        try {
            // Get attendance statistics
            $attendanceStats = cache()->remember("student_attendance_stats_{$student->id}", 300, function () use ($student) {
                return [
                    'general' => Attendance::where('student_id', $student->id)->count(),
                    'subject' => SubjectAttendanceModel::where('student_id', $student->id)->count(),
                    'line' => LineAttendance::where('student_id', $student->id)->count(),
                    'present' => Attendance::where('student_id', $student->id)->where('status', 'present')->count(),
                    'absent' => Attendance::where('student_id', $student->id)->where('status', 'absent')->count(),
                    'late' => Attendance::where('student_id', $student->id)->where('status', 'late')->count(),
                ];
            });

            // Get recent attendances
            $recentAttendances = cache()->remember("student_recent_attendances_{$student->id}", 60, function () use ($student) {
                return Attendance::where('student_id', $student->id)
                    ->with('recordedByUser')
                    ->latest()
                    ->take(10)
                    ->get();
            });

            return view('students.show', compact('student', 'attendanceStats', 'recentAttendances'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading student details: ' . $e->getMessage());
        }
    }

    public function edit(Student $student)
    {
        try {
            $classes = Student::distinct()->pluck('class')->filter()->sort();
            $majors = Student::distinct()->pluck('major')->filter()->sort();
            
            return view('students.edit', compact('student', 'classes', 'majors'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading edit form: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Student $student)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'student_id' => 'required|string|unique:students,student_id,' . $student->id . '|max:20',
                'email' => 'nullable|email|unique:students,email,' . $student->id,
                'phone' => 'nullable|string|max:20',
                'class' => 'required|string|max:50',
                'major' => 'required|string|max:100',
                'status' => 'required|in:active,inactive',
                'address' => 'nullable|string|max:500',
                'date_of_birth' => 'nullable|date',
                'gender' => 'nullable|in:male,female,other',
                'parent_name' => 'nullable|string|max:255',
                'parent_phone' => 'nullable|string|max:20',
            ]);

            $student->update($request->all());

            // Clear cache
            Cache::forget('student_stats');
            Cache::forget("student_attendance_stats_{$student->id}");
            Cache::forget("student_recent_attendances_{$student->id}");

            return redirect()->route('students.index')->with('success', 'Student updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating student: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Student $student)
    {
        try {
            $student->delete();

            // Clear cache
            Cache::forget('student_stats');

            return redirect()->route('students.index')->with('success', 'Student deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting student: ' . $e->getMessage());
        }
    }

    // Student Dashboard (for student users)
    public function dashboard()
    {
        try {
            $user = auth()->user();
            $student = Student::where('user_id', $user->id)->first();

            if (!$student) {
                return back()->with('error', 'Student profile not found. Please contact administrator.');
            }

            // Get attendance statistics
            $stats = cache()->remember("student_dashboard_stats_{$student->id}", 300, function () use ($student) {
                return [
                    'total_attendances' => Attendance::where('student_id', $student->id)->count(),
                    'present_count' => Attendance::where('student_id', $student->id)->where('status', 'present')->count(),
                    'absent_count' => Attendance::where('student_id', $student->id)->where('status', 'absent')->count(),
                    'late_count' => Attendance::where('student_id', $student->id)->where('status', 'late')->count(),
                    'attendance_rate' => $this->calculateAttendanceRate($student->id),
                ];
            });

            // Get recent attendances
            $recentAttendances = cache()->remember("student_dashboard_recent_{$student->id}", 60, function () use ($student) {
                return Attendance::where('student_id', $student->id)
                    ->with('subject')
                    ->latest()
                    ->take(5)
                    ->get();
            });

            // Get monthly attendance chart data
            $monthlyData = cache()->remember("student_monthly_attendance_{$student->id}", 300, function () use ($student) {
                return $this->getMonthlyAttendanceData($student->id);
            });

            return view('student.dashboard', compact('student', 'stats', 'recentAttendances', 'monthlyData'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading dashboard: ' . $e->getMessage());
        }
    }

    public function profile()
    {
        try {
            $user = auth()->user();
            $student = Student::where('user_id', $user->id)->first();

            if (!$student) {
                return back()->with('error', 'Student profile not found. Please contact administrator.');
            }

            return view('student.profile', compact('student', 'user'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading profile: ' . $e->getMessage());
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $user = auth()->user();
            $student = Student::where('user_id', $user->id)->first();

            if (!$student) {
                return back()->with('error', 'Student profile not found.');
            }

            $request->validate([
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
                'parent_name' => 'nullable|string|max:255',
                'parent_phone' => 'nullable|string|max:20',
            ]);

            $student->update($request->only(['phone', 'address', 'parent_name', 'parent_phone']));

            return back()->with('success', 'Profile updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating profile: ' . $e->getMessage());
        }
    }

    // Search API for AJAX requests
    public function search(Request $request)
    {
        try {
            $query = $request->get('q', '');
            $limit = $request->get('limit', 10);

            if (empty($query)) {
                return response()->json([]);
            }

            $students = Student::where('name', 'LIKE', "%{$query}%")
                ->orWhere('student_id', 'LIKE', "%{$query}%")
                ->orWhere('email', 'LIKE', "%{$query}%")
                ->limit($limit)
                ->get(['id', 'name', 'student_id', 'class', 'major']);

            return response()->json($students);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Export students
    public function export(Request $request)
    {
        try {
            $query = Student::query();

            // Apply filters
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('student_id', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%");
                });
            }

            if ($request->filled('class')) {
                $query->where('class', $request->class);
            }

            if ($request->filled('major')) {
                $query->where('major', $request->major);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $students = $query->get();

            $filename = 'students_' . now()->format('Y-m-d_H-i-s') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename={$filename}",
            ];

            $callback = function() use ($students) {
                $file = fopen('php://output', 'w');
                
                // Add headers
                fputcsv($file, [
                    'ID', 'Student ID', 'Name', 'Email', 'Phone', 'Class', 'Major', 
                    'Status', 'Address', 'Date of Birth', 'Gender', 'Parent Name', 'Parent Phone', 'Created At'
                ]);

                // Add data
                foreach ($students as $student) {
                    fputcsv($file, [
                        $student->id,
                        $student->student_id,
                        $student->name,
                        $student->email,
                        $student->phone,
                        $student->class,
                        $student->major,
                        $student->status,
                        $student->address,
                        $student->date_of_birth,
                        $student->gender,
                        $student->parent_name,
                        $student->parent_phone,
                        $student->created_at,
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return back()->with('error', 'Error exporting students: ' . $e->getMessage());
        }
    }



    private function calculateAttendanceRate($studentId)
    {
        $total = Attendance::where('student_id', $studentId)->count();
        $present = Attendance::where('student_id', $studentId)->where('status', 'present')->count();
        
        return $total > 0 ? round(($present / $total) * 100, 2) : 0;
    }

    private function getMonthlyAttendanceData($studentId)
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->format('M Y');
            
            $total = Attendance::where('student_id', $studentId)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
                
            $present = Attendance::where('student_id', $studentId)
                ->where('status', 'present')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            $data[] = [
                'month' => $month,
                'total' => $total,
                'present' => $present,
                'rate' => $total > 0 ? round(($present / $total) * 100, 2) : 0
            ];
        }
        
        return $data;
    }
}
