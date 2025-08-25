@extends('layouts.app')

@section('title', 'แดชบอร์ดจัดการโรงเรียน - ' . ($schoolInfo['name'] ?? 'ระบบจัดการโรงเรียน'))

@section('page-title', 'แดชบอร์ดจัดการโรงเรียน')

@push('styles')
<style>
:root {
    --primary-color: {{ \App\Models\SchoolSetting::getValue('primary_color', '#667eea') }};
    --secondary-color: {{ \App\Models\SchoolSetting::getValue('secondary_color', '#764ba2') }};
    --accent-color: {{ \App\Models\SchoolSetting::getValue('accent_color', '#f093fb') }};
    --success-color: #11998e;
    --warning-color: #f5576c;
    --info-color: #4facfe;
}

.dashboard-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: white;
    border-radius: 1rem;
    padding: 2rem;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}

.dashboard-header::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 200px;
    height: 200px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    transform: translate(50%, -50%);
}

.stat-card {
    background: white;
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: none;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: var(--primary-color);
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #6c757d;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.stat-change {
    font-size: 0.875rem;
    font-weight: 500;
}

.stat-change.positive {
    color: var(--success-color);
}

.stat-change.negative {
    color: var(--warning-color);
}

.chart-card {
    background: white;
    border-radius: 1rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.chart-header {
    background: linear-gradient(45deg, #f8f9fa, #e9ecef);
    padding: 1.5rem;
    border-bottom: 1px solid #dee2e6;
}

.activity-card {
    background: white;
    border-radius: 1rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    max-height: 600px;
    overflow: hidden;
}

.activity-item {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #f1f3f4;
    display: flex;
    align-items: center;
    transition: background-color 0.2s ease;
}

.activity-item:hover {
    background-color: #f8f9fa;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    color: white;
}

.activity-icon.primary { background: var(--primary-color); }
.activity-icon.success { background: var(--success-color); }
.activity-icon.warning { background: var(--warning-color); }
.activity-icon.info { background: var(--info-color); }

.school-info-card {
    background: linear-gradient(135deg, var(--accent-color) 0%, var(--warning-color) 100%);
    color: white;
    border-radius: 1rem;
    padding: 1.5rem;
}

.quick-action-btn {
    background: white;
    border: 2px solid var(--primary-color);
    color: var(--primary-color);
    border-radius: 0.75rem;
    padding: 1rem 1.5rem;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    font-weight: 600;
    margin-bottom: 1rem;
}

.quick-action-btn:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-2px);
}

.alert-system {
    border-radius: 0.75rem;
    border: none;
    padding: 1rem 1.5rem;
    margin-bottom: 1rem;
}

.live-indicator {
    display: inline-flex;
    align-items: center;
    color: var(--success-color);
    font-size: 0.875rem;
    font-weight: 500;
}

.live-dot {
    width: 8px;
    height: 8px;
    background: var(--success-color);
    border-radius: 50%;
    margin-right: 0.5rem;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

.progress-ring {
    transform: rotate(-90deg);
}

.progress-ring-circle {
    transition: stroke-dashoffset 0.35s;
    transform-origin: 50% 50%;
}

@media (max-width: 768px) {
    .dashboard-header {
        padding: 1.5rem;
        margin-bottom: 1rem;
    }
    
    .stat-number {
        font-size: 2rem;
    }
    
    .chart-card, .activity-card {
        margin-bottom: 1rem;
    }
}

.weather-widget {
    background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
    color: white;
    border-radius: 1rem;
    padding: 1.5rem;
    text-align: center;
}

.time-widget {
    background: linear-gradient(135deg, #fd79a8 0%, #e84393 100%);
    color: white;
    border-radius: 1rem;
    padding: 1.5rem;
    text-align: center;
}

.digital-clock {
    font-family: 'Courier New', monospace;
    font-size: 1.5rem;
    font-weight: bold;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- School Header -->
    <div class="dashboard-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="mb-2">
                    <i class="fas fa-school me-3"></i>{{ $schoolInfo['name'] ?? 'ระบบจัดการโรงเรียน' }}
                </h1>
                <p class="mb-0 opacity-75">
                    <i class="fas fa-calendar me-2"></i>ปีการศึกษา {{ $schoolInfo['academic_year'] ?? date('Y') }} 
                    เทอม {{ $schoolInfo['semester'] ?? '1' }}
                    <span class="ms-3">
                        <span class="live-dot"></span>
                        <span class="live-indicator">สถานะ: ออนไลน์</span>
                    </span>
                </p>
            </div>
            <div class="col-md-4 text-end">
                <div class="time-widget">
                    <div class="digital-clock" id="digitalClock">{{ now()->format('H:i:s') }}</div>
                    <small>{{ now()->format('d/m/Y') }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- System Alerts -->
    @if(!empty($quickStats['system_alerts']))
        <div class="row mb-4">
            <div class="col-12">
                @foreach($quickStats['system_alerts'] as $alert)
                    <div class="alert alert-{{ $alert['type'] }} alert-system">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                {{ $alert['message'] }}
                            </div>
                            <a href="{{ $alert['action'] }}" class="btn btn-sm btn-outline-{{ $alert['type'] }}">
                                แก้ไข
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-number" id="totalStudentsCount">{{ number_format($stats['total_students']) }}</div>
                        <div class="stat-label">นักเรียนทั้งหมด</div>
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up me-1"></i>
                            {{ $stats['active_students'] }} คน กำลังศึกษา
                        </div>
                    </div>
                    <div class="stat-icon" style="background: var(--primary-color); color: white; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user-graduate fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-number" id="todayCheckinsCount">{{ number_format($todayStats['total_checkins']) }}</div>
                        <div class="stat-label">เช็คชื่อวันนี้</div>
                        <div class="stat-change {{ $todayStats['attendance_rate'] >= 80 ? 'positive' : 'negative' }}">
                            <i class="fas fa-percentage me-1"></i>
                            {{ $todayStats['attendance_rate'] }}% อัตราการเข้าเรียน
                        </div>
                    </div>
                    <div class="stat-icon" style="background: var(--success-color); color: white; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-qrcode fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-number">{{ number_format($stats['total_teachers']) }}</div>
                        <div class="stat-label">อาจารย์ทั้งหมด</div>
                        <div class="stat-change positive">
                            <i class="fas fa-chalkboard-teacher me-1"></i>
                            {{ $stats['total_subjects'] }} วิชา
                        </div>
                    </div>
                    <div class="stat-icon" style="background: var(--info-color); color: white; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-users fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-number">{{ number_format($stats['total_announcements']) }}</div>
                        <div class="stat-label">ประกาศทั้งหมด</div>
                        <div class="stat-change positive">
                            <i class="fas fa-newspaper me-1"></i>
                            {{ $stats['published_blogs'] }} บทความ
                        </div>
                    </div>
                    <div class="stat-icon" style="background: var(--warning-color); color: white; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-bullhorn fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row">
        <!-- Charts Section -->
        <div class="col-lg-8">
            <!-- Attendance Chart -->
            <div class="chart-card mb-4">
                <div class="chart-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>สถิติการเช็คชื่อ 7 วันล่าสุด
                    </h5>
                </div>
                <div class="p-3">
                    <canvas id="attendanceChart" height="100"></canvas>
                </div>
            </div>

            <!-- Today's Check-ins -->
            <div class="chart-card">
                <div class="chart-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-clock me-2"></i>การเช็คชื่อวันนี้
                        </h5>
                        <span class="badge bg-primary">{{ $todayStats['total_checkins'] }} คน</span>
                    </div>
                </div>
                <div class="p-3">
                    @if(!empty($todayStats['recent_checkins']) && $todayStats['recent_checkins']->count() > 0)
                        <div class="row">
                            @foreach($todayStats['recent_checkins'] as $checkin)
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center p-3 border rounded">
                                        <div class="activity-icon {{ $checkin->status_color }} me-3">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $checkin->student->name }}</div>
                                            <small class="text-muted">
                                                {{ $checkin->check_in_time->format('H:i') }} - 
                                                <span class="badge bg-{{ $checkin->status_color }}">{{ $checkin->status_text }}</span>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="text-center mt-3">
                            <a href="{{ route('check-ins.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-list me-2"></i>ดูทั้งหมด
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">ยังไม่มีการเช็คชื่อวันนี้</h6>
                            <a href="{{ route('check-ins.create') }}" class="btn btn-primary">
                                <i class="fas fa-qrcode me-2"></i>สร้าง QR Code
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="chart-card mb-4">
                <div class="chart-header">
                    <h5 class="mb-0">
                        <i class="fas fa-rocket me-2"></i>การดำเนินการด่วน
                    </h5>
                </div>
                <div class="p-3">
                    <a href="{{ route('check-ins.create') }}" class="quick-action-btn">
                        <i class="fas fa-qrcode me-2"></i>สร้าง QR Code เช็คชื่อ
                    </a>
                    <a href="{{ route('students.create') }}" class="quick-action-btn">
                        <i class="fas fa-user-plus me-2"></i>เพิ่มนักเรียนใหม่
                    </a>
                    <a href="{{ route('announcements.create') }}" class="quick-action-btn">
                        <i class="fas fa-bullhorn me-2"></i>สร้างประกาศใหม่
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="quick-action-btn">
                        <i class="fas fa-cog me-2"></i>ตั้งค่าระบบ
                    </a>
                </div>
            </div>

            <!-- School Information -->
            <div class="school-info-card mb-4">
                <h5 class="mb-3">
                    <i class="fas fa-info-circle me-2"></i>ข้อมูลโรงเรียน
                </h5>
                <div class="mb-2">
                    <strong>ที่อยู่:</strong><br>
                    <small>{{ $schoolInfo['address'] ?: 'ยังไม่ได้ระบุ' }}</small>
                </div>
                <div class="mb-2">
                    <strong>โทรศัพท์:</strong> {{ $schoolInfo['phone'] ?: 'ยังไม่ได้ระบุ' }}
                </div>
                <div class="mb-3">
                    <strong>อีเมล:</strong> {{ $schoolInfo['email'] ?: 'ยังไม่ได้ระบุ' }}
                </div>
                @if($schoolInfo['principal'])
                    <div class="mb-3">
                        <strong>ผู้อำนวยการ:</strong><br>
                        {{ $schoolInfo['principal'] }}
                    </div>
                @endif
                <a href="{{ route('admin.settings.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-edit me-1"></i>แก้ไขข้อมูล
                </a>
            </div>

            <!-- Recent Activities -->
            <div class="activity-card">
                <div class="chart-header">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>กิจกรรมล่าสุด
                    </h5>
                </div>
                <div style="max-height: 400px; overflow-y: auto;">
                    @forelse($recentActivities as $activity)
                        <div class="activity-item">
                            <div class="activity-icon {{ $activity['color'] }}">
                                <i class="fas {{ $activity['icon'] }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $activity['title'] }}</div>
                                <small class="text-muted">{{ $activity['description'] }}</small>
                                <div class="small text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $activity['time']->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-clock fa-2x text-muted mb-2"></i>
                            <p class="text-muted">ยังไม่มีกิจกรรม</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Digital Clock
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('th-TH');
        document.getElementById('digitalClock').textContent = timeString;
    }
    setInterval(updateClock, 1000);

    // Attendance Chart
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    const attendanceChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($weeklyStats['days']),
            datasets: [{
                label: 'การเช็คชื่อ QR',
                data: @json($weeklyStats['checkins']),
                borderColor: getComputedStyle(document.documentElement).getPropertyValue('--primary-color'),
                backgroundColor: getComputedStyle(document.documentElement).getPropertyValue('--primary-color') + '20',
                fill: true,
                tension: 0.4
            }, {
                label: 'การเช็คชื่อแบบเดิม',
                data: @json($weeklyStats['attendances']),
                borderColor: getComputedStyle(document.documentElement).getPropertyValue('--accent-color'),
                backgroundColor: getComputedStyle(document.documentElement).getPropertyValue('--accent-color') + '20',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f1f3f4'
                    }
                },
                x: {
                    grid: {
                        color: '#f1f3f4'
                    }
                }
            }
        }
    });

    // Real-time updates
    function updateDashboardStats() {
        fetch('/api/admin/dashboard/stats')
            .then(response => response.json())
            .then(data => {
                document.getElementById('todayCheckinsCount').textContent = data.today_checkins.toLocaleString();
                
                // Update other stats if needed
                const attendanceRate = document.querySelector('.stat-change');
                if (attendanceRate) {
                    attendanceRate.innerHTML = `<i class="fas fa-percentage me-1"></i>${data.attendance_rate}% อัตราการเข้าเรียน`;
                }
                
                console.log('Dashboard updated at', data.timestamp);
            })
            .catch(error => console.log('Update failed:', error));
    }

    // Update every 30 seconds
    setInterval(updateDashboardStats, 30000);

    // Add click animations to stat cards
    document.querySelectorAll('.stat-card').forEach(card => {
        card.addEventListener('click', function() {
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });
});
</script>
@endpush