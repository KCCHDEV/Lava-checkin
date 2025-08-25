<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\SubjectAttendanceController;
use App\Http\Controllers\LineAttendanceController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\SchoolSettingController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckInController;

// Public routes
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/announcements', [AnnouncementController::class, 'publicIndex'])->name('announcements.public.index');
Route::get('/announcements/{id}', [AnnouncementController::class, 'publicShow'])->name('announcements.public.show');
Route::get('/blogs', [BlogController::class, 'publicIndex'])->name('blogs.public.index');
Route::get('/blogs/{slug}', [BlogController::class, 'publicShow'])->name('blogs.public.show');

// Public check-in routes (accessible without login for QR scanning)
Route::get('/scan/{code?}', [CheckInController::class, 'scan'])->name('check-ins.scan');
Route::post('/scan/submit', [CheckInController::class, 'store'])->name('check-ins.scan.submit');

// API routes for real-time data
Route::get('/api/recent-checkins', [CheckInController::class, 'getRecentCheckins'])->name('api.recent-checkins');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Student routes
    Route::get('/student/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/student/profile', [StudentController::class, 'profile'])->name('student.profile');
    Route::post('/student/profile', [StudentController::class, 'updateProfile'])->name('student.profile.update');
    
    // Admin/Teacher routes
    Route::middleware(['auth'])->group(function () {
        // Students management
        Route::resource('students', StudentController::class);
    Route::get('students/export', [StudentController::class, 'export'])->name('students.export');
        
        // Subjects management
        Route::resource('subjects', SubjectController::class);
        
        // Attendances
        Route::resource('attendances', AttendanceController::class);
        Route::post('/attendances/bulk', [AttendanceController::class, 'bulkStore'])->name('attendances.bulk.store');
        
        // Subject attendances
        Route::resource('subject-attendances', SubjectAttendanceController::class);
        Route::post('/subject-attendances/bulk', [SubjectAttendanceController::class, 'bulkStore'])->name('subject-attendances.bulk.store');
        
        // Line attendances
        Route::resource('line-attendances', LineAttendanceController::class);
        Route::post('/line-attendances/bulk', [LineAttendanceController::class, 'bulkStore'])->name('line-attendances.bulk.store');
        
        // Check-ins
        Route::resource('check-ins', CheckInController::class);
        Route::get('/check-ins-export', [CheckInController::class, 'export'])->name('check-ins.export');
        Route::get('/check-ins-manual', [CheckInController::class, 'manual'])->name('check-ins.manual');
        Route::post('/check-ins-manual', [CheckInController::class, 'storeManual'])->name('check-ins.manual.store');
        Route::post('/check-ins-generate-qr', [CheckInController::class, 'generateQR'])->name('check-ins.generate-qr');
        
        // Welcome content management
        Route::get('/admin/welcome/manage', [WelcomeController::class, 'manage'])->name('admin.welcome.manage');
        Route::post('/admin/welcome/update', [WelcomeController::class, 'update'])->name('admin.welcome.update');
        
        // Announcements management
        Route::resource('announcements', AnnouncementController::class)->except(['publicIndex', 'publicShow']);
        Route::post('/announcements/{announcement}/toggle-status', [AnnouncementController::class, 'toggleStatus'])->name('announcements.toggle-status');
        
        // Blogs management
        Route::resource('blogs', BlogController::class)->except(['publicIndex', 'publicShow']);
        Route::post('/blogs/{blog}/toggle-status', [BlogController::class, 'toggleStatus'])->name('blogs.toggle-status');
        Route::post('/blogs/{blog}/toggle-featured', [BlogController::class, 'toggleFeatured'])->name('blogs.toggle-featured');
        
        // School settings
        Route::get('/admin/settings', [SchoolSettingController::class, 'index'])->name('admin.settings.index');
        Route::post('/admin/settings', [SchoolSettingController::class, 'update'])->name('admin.settings.update');
        Route::post('/admin/settings/store', [SchoolSettingController::class, 'store'])->name('admin.settings.store');
        Route::delete('/admin/settings/{setting}', [SchoolSettingController::class, 'destroy'])->name('admin.settings.destroy');
        Route::post('/admin/settings/initialize', [SchoolSettingController::class, 'initializeDefaults'])->name('admin.settings.initialize');
        
        // Database management (Admin only)
        Route::prefix('admin/database')->name('admin.database.')->group(function () {
            Route::get('/', [DatabaseController::class, 'index'])->name('index');
            Route::get('/backup', [DatabaseController::class, 'backup'])->name('backup');
            Route::post('/export', [DatabaseController::class, 'export'])->name('export');
            Route::post('/optimize', [DatabaseController::class, 'optimize'])->name('optimize');
            Route::post('/reset', [DatabaseController::class, 'reset'])->name('reset');
            Route::get('/table/{table}', [DatabaseController::class, 'showTable'])->name('table');
            Route::delete('/table/{table}/{id}', [DatabaseController::class, 'deleteRecord'])->name('delete-record');
        });
        
        // Theme management (Admin only)
        Route::prefix('admin/theme')->name('admin.theme.')->group(function () {
            Route::get('/', [ThemeController::class, 'index'])->name('index');
            Route::post('/update', [ThemeController::class, 'update'])->name('update');
            Route::post('/preview', [ThemeController::class, 'preview'])->name('preview');
            Route::post('/reset', [ThemeController::class, 'reset'])->name('reset');
        });

        // User Profile Management
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [ProfileController::class, 'index'])->name('index');
            Route::put('/update', [ProfileController::class, 'update'])->name('update');
            Route::delete('/avatar', [ProfileController::class, 'deleteAvatar'])->name('delete-avatar');
            Route::get('/preferences', [ProfileController::class, 'preferences'])->name('preferences');
            Route::put('/preferences/update', [ProfileController::class, 'updatePreferences'])->name('preferences.update');
        });
    });
});
