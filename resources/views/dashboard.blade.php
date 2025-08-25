@extends('layouts.app')

@section('title', 'แดชบอร์ด - ระบบเช็คชื่อการมาเรียน')

@section('page-title', 'แดชบอร์ด')

@section('content')
<!-- Welcome Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-gradient-primary text-white">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="mb-2">
                            <i class="fas fa-sun me-2"></i>สวัสดีคุณ {{ auth()->user()->name ?? 'ผู้ใช้งาน' }}!
                        </h3>
                        <p class="mb-0 opacity-75">
                            ยินดีต้อนรับสู่ระบบเช็คชื่อการมาเรียน วิทยาลัยเทคนิคลำพูน
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <i class="fas fa-graduation-cap" style="font-size: 4rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            นักเรียนทั้งหมด
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalStudents ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            มาเรียนวันนี้
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $todayStats['present'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            มาสายวันนี้
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $todayStats['late'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            ขาดเรียนวันนี้
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $todayStats['absent'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-bolt me-2"></i>การดำเนินการด่วน
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @if(Auth::user() && Auth::user()->isAdmin())
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="{{ route('students.create') }}" class="btn btn-primary btn-block h-100 d-flex flex-column justify-content-center">
                            <i class="fas fa-user-plus fa-2x mb-2"></i>
                            <span>เพิ่มนักเรียนใหม่</span>
                        </a>
                    </div>
                    @endif
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="{{ route('attendances.create') }}" class="btn btn-success btn-block h-100 d-flex flex-column justify-content-center">
                            <i class="fas fa-clipboard-check fa-2x mb-2"></i>
                            <span>เช็คชื่อนักเรียน</span>
                        </a>
                    </div>
                    @if(Auth::user() && Auth::user()->isAdmin())
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="{{ route('subjects.create') }}" class="btn btn-info btn-block h-100 d-flex flex-column justify-content-center">
                            <i class="fas fa-book fa-2x mb-2"></i>
                            <span>เพิ่มวิชาใหม่</span>
                        </a>
                    </div>
                    @endif
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="{{ route('line-attendances.create') }}" class="btn btn-warning btn-block h-100 d-flex flex-column justify-content-center">
                            <i class="fas fa-list-ol fa-2x mb-2"></i>
                            <span>เช็คเข้าแถว</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities & Charts -->
<div class="row">
    <!-- Recent Attendances -->
    <div class="col-lg-8 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-history me-2"></i>การเช็คชื่อล่าสุด
                </h6>
            </div>
            <div class="card-body">
                @if($recentAttendances && $recentAttendances->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>นักเรียน</th>
                                    <th>วันที่</th>
                                    <th>สถานะ</th>
                                    <th>ผู้บันทึก</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentAttendances as $attendance)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $attendance->student->name ?? 'ไม่ระบุ' }}</strong><br>
                                                <small class="text-muted">{{ $attendance->student->class ?? 'ไม่ระบุ' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $attendance->date ? $attendance->date->format('d/m/Y') : 'ไม่ระบุ' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $attendance->status_color ?? 'secondary' }}">
                                            {{ $attendance->status_text ?? 'ไม่ระบุ' }}
                                        </span>
                                    </td>
                                    <td>{{ $attendance->recorder->name ?? 'ไม่ระบุ' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-clipboard-list text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3">ยังไม่มีการเช็คชื่อ</p>
                        <a href="{{ route('attendances.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>เริ่มเช็คชื่อ
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>

<!-- System Info -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-info-circle me-2"></i>ข้อมูลระบบ
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <div class="border-end">
                            <h4 class="text-primary">{{ $totalStudents ?? 0 }}</h4>
                            <p class="text-muted mb-0">นักเรียนทั้งหมด</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="border-end">
                            <h4 class="text-success">{{ $totalSubjects ?? 0 }}</h4>
                            <p class="text-muted mb-0">วิชาทั้งหมด</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="border-end">
                            <h4 class="text-info">{{ $totalAttendances ?? 0 }}</h4>
                            <p class="text-muted mb-0">การเช็คชื่อทั้งหมด</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div>
                            <h4 class="text-warning">{{ $totalUsers ?? 0 }}</h4>
                            <p class="text-muted mb-0">ผู้ใช้งานทั้งหมด</p>
                        </div>
                    </div>
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
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    const attendanceChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['มาเรียน', 'มาสาย', 'ขาดเรียน', 'ป่วย'],
            datasets: [{
                data: [
                    {{ $todayStats['present'] ?? 0 }},
                    {{ $todayStats['late'] ?? 0 }},
                    {{ $todayStats['absent'] ?? 0 }},
                    {{ $todayStats['sick'] ?? 0 }}
                ],
                backgroundColor: [
                    '#28a745',
                    '#ffc107',
                    '#dc3545',
                    '#17a2b8'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});
</script>
@endpush

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.border-left-primary {
    border-left: 4px solid #4e73df !important;
}

.border-left-success {
    border-left: 4px solid #1cc88a !important;
}

.border-left-warning {
    border-left: 4px solid #f6c23e !important;
}

.border-left-danger {
    border-left: 4px solid #e74a3b !important;
}

.avatar-sm {
    width: 32px;
    height: 32px;
}

.text-xs {
    font-size: 0.7rem;
}

.text-gray-800 {
    color: #5a5c69 !important;
}

.text-gray-300 {
    color: #dddfeb !important;
}
</style>
