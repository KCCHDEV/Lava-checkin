@extends('layouts.base')

@section('title', $title ?? 'ระบบเช็คชื่อการมาเรียน')

@section('content')
@php
    // Get theme settings
    $currentTheme = \App\Models\SchoolSetting::getValue('current_theme', 'default');
    $primaryColor = \App\Models\SchoolSetting::getValue('primary_color', '#667eea');
    $secondaryColor = \App\Models\SchoolSetting::getValue('secondary_color', '#764ba2');
    $accentColor = \App\Models\SchoolSetting::getValue('accent_color', '#28a745');
    $textColor = \App\Models\SchoolSetting::getValue('text_color', '#333333');
    $backgroundColor = \App\Models\SchoolSetting::getValue('background_color', '#f8f9fa');
    $sidebarColor = \App\Models\SchoolSetting::getValue('sidebar_color', '#2c3e50');
    $fontFamily = \App\Models\SchoolSetting::getValue('font_family', 'Sarabun');
    $borderRadius = \App\Models\SchoolSetting::getValue('border_radius', 'medium');
    $shadowIntensity = \App\Models\SchoolSetting::getValue('shadow_intensity', 'medium');
    
    // Border radius mapping
    $radiusMap = ['small' => '4px', 'medium' => '8px', 'large' => '12px'];
    $currentRadius = $radiusMap[$borderRadius] ?? '8px';
    
    // Shadow mapping
    $shadowMap = [
        'low' => '0 1px 2px rgba(0,0,0,0.1)',
        'medium' => '0 2px 4px rgba(0,0,0,0.1)',
        'high' => '0 4px 8px rgba(0,0,0,0.15)'
    ];
    $currentShadow = $shadowMap[$shadowIntensity] ?? '0 2px 4px rgba(0,0,0,0.1)';
@endphp

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <div class="text-white" id="sidebar-wrapper" style="background: {{ $sidebarColor }};">
        <div class="sidebar-heading d-flex align-items-center justify-content-center">
            <i class="fas fa-school me-2"></i>
            <span>ระบบเช็คชื่อ</span>
        </div>
        
        <div class="list-group list-group-flush">
            @if(Auth::user() && Auth::user()->isStudent())
                <!-- Student Navigation -->
                <a class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}" 
                   href="{{ route('student.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    หน้าแรก
                </a>
                
                <a class="nav-link {{ request()->routeIs('student.profile') ? 'active' : '' }}" 
                   href="{{ route('student.profile') }}">
                    <i class="fas fa-user"></i>
                    โปรไฟล์
                </a>
            @else
                <!-- Admin/Teacher Navigation -->
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                   href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    หน้าแรก
                </a>
                
                <a class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}" 
                   href="{{ route('students.index') }}">
                    <i class="fas fa-user-graduate"></i>
                    จัดการนักเรียน
                </a>
                
                <a class="nav-link {{ request()->routeIs('subjects.*') ? 'active' : '' }}" 
                   href="{{ route('subjects.index') }}">
                    <i class="fas fa-book"></i>
                    จัดการวิชาเรียน
                </a>
                
                <a class="nav-link {{ request()->routeIs('attendances.*') ? 'active' : '' }}" 
                   href="{{ route('attendances.index') }}">
                    <i class="fas fa-clipboard-check"></i>
                    เช็คชื่อทั่วไป
                </a>
                
                <a class="nav-link {{ request()->routeIs('subject-attendances.*') ? 'active' : '' }}" 
                   href="{{ route('subject-attendances.index') }}">
                    <i class="fas fa-chalkboard-teacher"></i>
                    เช็คชื่อรายวิชา
                </a>
                
                <a class="nav-link {{ request()->routeIs('line-attendances.*') ? 'active' : '' }}" 
                   href="{{ route('line-attendances.index') }}">
                    <i class="fas fa-users"></i>
                    เช็คเข้าแถว
                </a>
                
                <a class="nav-link {{ request()->routeIs('announcements.*') ? 'active' : '' }}" 
                   href="{{ route('announcements.index') }}">
                    <i class="fas fa-bullhorn"></i>
                    จัดการประกาศ
                </a>
                
                <a class="nav-link {{ request()->routeIs('blogs.*') ? 'active' : '' }}" 
                   href="{{ route('blogs.index') }}">
                    <i class="fas fa-newspaper"></i>
                    จัดการบทความ
                </a>
                
                @if(Auth::user() && Auth::user()->isAdmin())
                    <a class="nav-link {{ request()->routeIs('admin.welcome.*') ? 'active' : '' }}" 
                       href="{{ route('admin.welcome.manage') }}">
                        <i class="fas fa-home"></i>
                        จัดการเว็บไซต์
                    </a>
                    
                    <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" 
                       href="{{ route('admin.settings.index') }}">
                        <i class="fas fa-cog"></i>
                        ตั้งค่าระบบ
                    </a>
                    
                    <a class="nav-link {{ request()->routeIs('admin.database.*') ? 'active' : '' }}" 
                       href="{{ route('admin.database.index') }}">
                        <i class="fas fa-database"></i>
                        จัดการฐานข้อมูล
                    </a>
                    
                    <a class="nav-link {{ request()->routeIs('admin.theme.*') ? 'active' : '' }}" 
                       href="{{ route('admin.theme.index') }}">
                        <i class="fas fa-palette"></i>
                        จัดการธีม
                    </a>
                @endif
            @endif
        </div>
    </div>
    
    <!-- Page Content -->
    <div id="page-content-wrapper" style="background: {{ $backgroundColor }};">
        <!-- Top navigation-->
        <nav class="navbar navbar-expand-lg border-bottom" style="background: white; box-shadow: {{ $currentShadow }};">
            <div class="container-fluid">
                <button class="btn" id="sidebarToggle" style="background: linear-gradient(135deg, {{ $primaryColor }} 0%, {{ $secondaryColor }} 100%); border: none; color: white;">
                    <i class="fas fa-bars"></i>
                </button>
                
                <div class="navbar-nav ms-auto">
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-2"></i>
                            {{ Auth::user()->name ?? 'User' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>หน้าแรก
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i>ออกจากระบบ
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Page content-->
        <div class="container-fluid p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @yield('page-content')
        </div>
    </div>
</div>

<style>
body {
    font-family: '{{ $fontFamily }}', sans-serif;
    color: {{ $textColor }};
}

.nav-link {
    color: rgba(255,255,255,0.8) !important;
    padding: 0.75rem 1rem;
    border: none;
    transition: all 0.3s ease;
    border-radius: {{ $currentRadius }};
}

.nav-link:hover {
    color: white !important;
    background-color: rgba(255, 255, 255, 0.1);
}

.nav-link.active {
    color: white !important;
    background-color: rgba(255, 255, 255, 0.2);
}

.nav-link i {
    width: 20px;
    margin-right: 8px;
}

#sidebar-wrapper {
    min-height: 100vh;
    margin-left: -15rem;
    transition: margin 0.25s ease-out;
}

#sidebar-wrapper .sidebar-heading {
    padding: 0.875rem 1.25rem;
    font-size: 1.2rem;
    background-color: rgba(255, 255, 255, 0.1);
}

#wrapper.toggled #sidebar-wrapper {
    margin-left: 0;
}

@media (min-width: 768px) {
    #sidebar-wrapper {
        margin-left: 0;
    }
    
    #page-content-wrapper {
        min-width: 0;
        width: 100%;
    }
    
    #wrapper.toggled #sidebar-wrapper {
        margin-left: -15rem;
    }
}

.card {
    border: none;
    border-radius: {{ $currentRadius }};
    box-shadow: {{ $currentShadow }};
}

.btn-primary {
    background: linear-gradient(135deg, {{ $primaryColor }} 0%, {{ $secondaryColor }} 100%);
    border: none;
    border-radius: {{ $currentRadius }};
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.table {
    border-radius: {{ $currentRadius }};
    overflow: hidden;
}

.table thead th {
    background: linear-gradient(135deg, {{ $primaryColor }} 0%, {{ $secondaryColor }} 100%);
    color: white;
    border: none;
    font-weight: 600;
}

.avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, {{ $primaryColor }} 0%, {{ $secondaryColor }} 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

/* Theme-specific styles */
.theme-default {
    --primary-color: {{ $primaryColor }};
    --secondary-color: {{ $secondaryColor }};
    --accent-color: {{ $accentColor }};
    --text-color: {{ $textColor }};
    --background-color: {{ $backgroundColor }};
    --sidebar-color: {{ $sidebarColor }};
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle
    document.getElementById('sidebarToggle').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('wrapper').classList.toggle('toggled');
    });
    
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
@endsection
