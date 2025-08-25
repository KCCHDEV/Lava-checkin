<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Attendance;
use App\Models\SubjectAttendance;
use App\Models\LineAttendance;
use App\Models\Announcement;
use App\Models\Blog;
use App\Models\SchoolSetting;
use App\Models\WelcomeContent;

class DatabaseController extends Controller
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
            // Get database statistics
            $stats = cache()->remember('database_stats', 300, function () {
                return [
                    'users' => User::count(),
                    'students' => Student::count(),
                    'subjects' => Subject::count(),
                    'attendances' => Attendance::count(),
                    'subject_attendances' => SubjectAttendance::count(),
                    'line_attendances' => LineAttendance::count(),
                    'announcements' => Announcement::count(),
                    'blogs' => Blog::count(),
                    'school_settings' => SchoolSetting::count(),
                    'welcome_contents' => WelcomeContent::count(),
                ];
            });

            // Get table sizes
            $tableSizes = cache()->remember('table_sizes', 300, function () {
                $tables = [
                    'users', 'students', 'subjects', 'attendances', 
                    'subject_attendances', 'line_attendances', 'announcements', 
                    'blogs', 'school_settings', 'welcome_contents'
                ];
                
                $sizes = [];
                foreach ($tables as $table) {
                    if (Schema::hasTable($table)) {
                        $sizes[$table] = DB::table($table)->count();
                    }
                }
                return $sizes;
            });

            // Get recent activities
            $recentActivities = cache()->remember('recent_activities', 60, function () {
                $activities = [];
                
                // Recent users
                $recentUsers = User::latest()->take(5)->get();
                foreach ($recentUsers as $user) {
                    $activities[] = [
                        'type' => 'user',
                        'action' => 'created',
                        'description' => "User {$user->name} created",
                        'date' => $user->created_at,
                        'icon' => 'fas fa-user'
                    ];
                }
                
                // Recent students
                $recentStudents = Student::latest()->take(5)->get();
                foreach ($recentStudents as $student) {
                    $activities[] = [
                        'type' => 'student',
                        'action' => 'created',
                        'description' => "Student {$student->name} added",
                        'date' => $student->created_at,
                        'icon' => 'fas fa-user-graduate'
                    ];
                }
                
                // Recent attendances
                $recentAttendances = Attendance::latest()->take(5)->get();
                foreach ($recentAttendances as $attendance) {
                    $activities[] = [
                        'type' => 'attendance',
                        'action' => 'recorded',
                        'description' => "Attendance recorded for {$attendance->student->name}",
                        'date' => $attendance->created_at,
                        'icon' => 'fas fa-clipboard-check'
                    ];
                }
                
                // Sort by date
                usort($activities, function ($a, $b) {
                    return $b['date']->compare($a['date']);
                });
                
                return array_slice($activities, 0, 10);
            });

            return view('admin.database.index', compact('stats', 'tableSizes', 'recentActivities'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading database information: ' . $e->getMessage());
        }
    }

    public function backup()
    {
        try {
            $timestamp = now()->format('Y-m-d_H-i-s');
            $filename = "backup_{$timestamp}.sql";
            
            // Create backup directory if not exists
            if (!Storage::disk('local')->exists('backups')) {
                Storage::disk('local')->makeDirectory('backups');
            }
            
            $backupPath = storage_path("app/backups/{$filename}");
            
            // For SQLite, just copy the database file
            $databasePath = database_path('database.sqlite');
            if (file_exists($databasePath)) {
                copy($databasePath, $backupPath);
                
                return response()->download($backupPath, $filename)->deleteFileAfterSend();
            }
            
            return back()->with('error', 'Database file not found');
        } catch (\Exception $e) {
            return back()->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        try {
            $table = $request->input('table');
            $format = $request->input('format', 'json');
            $timestamp = now()->format('Y-m-d_H-i-s');
            
            if (!Schema::hasTable($table)) {
                return back()->with('error', 'Table not found');
            }
            
            $data = DB::table($table)->get();
            
            switch ($format) {
                case 'json':
                    $filename = "{$table}_{$timestamp}.json";
                    $content = json_encode($data, JSON_PRETTY_PRINT);
                    break;
                    
                case 'csv':
                    $filename = "{$table}_{$timestamp}.csv";
                    $content = $this->arrayToCsv($data->toArray());
                    break;
                    
                default:
                    return back()->with('error', 'Unsupported format');
            }
            
            return response($content)
                ->header('Content-Type', 'text/plain')
                ->header('Content-Disposition', "attachment; filename={$filename}");
                
        } catch (\Exception $e) {
            return back()->with('error', 'Export failed: ' . $e->getMessage());
        }
    }

    public function optimize()
    {
        try {
            // Clear all caches
            cache()->flush();
            
            // Clear Laravel caches
            \Artisan::call('config:clear');
            \Artisan::call('cache:clear');
            \Artisan::call('view:clear');
            \Artisan::call('route:clear');
            
            // For SQLite, run VACUUM
            DB::statement('VACUUM');
            
            return back()->with('success', 'Database optimized successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Optimization failed: ' . $e->getMessage());
        }
    }

    public function reset()
    {
        try {
            // Run fresh migrations
            \Artisan::call('migrate:fresh', ['--force' => true]);
            
            // Seed database
            \Artisan::call('db:seed', ['--force' => true]);
            
            // Clear caches
            cache()->flush();
            \Artisan::call('config:clear');
            \Artisan::call('cache:clear');
            \Artisan::call('view:clear');
            \Artisan::call('route:clear');
            
            return back()->with('success', 'Database reset successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Reset failed: ' . $e->getMessage());
        }
    }

    public function showTable($table)
    {
        try {
            if (!Schema::hasTable($table)) {
                abort(404, 'Table not found');
            }
            
            $data = DB::table($table)->paginate(50);
            $columns = Schema::getColumnListing($table);
            
            return view('admin.database.table', compact('table', 'data', 'columns'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading table: ' . $e->getMessage());
        }
    }

    public function deleteRecord(Request $request, $table, $id)
    {
        try {
            if (!Schema::hasTable($table)) {
                return back()->with('error', 'Table not found');
            }
            
            DB::table($table)->where('id', $id)->delete();
            
            return back()->with('success', 'Record deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Delete failed: ' . $e->getMessage());
        }
    }

    private function arrayToCsv($array)
    {
        if (empty($array)) {
            return '';
        }
        
        $output = fopen('php://temp', 'r+');
        
        // Add headers
        fputcsv($output, array_keys((array) $array[0]));
        
        // Add data
        foreach ($array as $row) {
            fputcsv($output, (array) $row);
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return $csv;
    }
}
