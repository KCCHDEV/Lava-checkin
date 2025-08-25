@extends('layouts.app')

@section('title', 'จัดการฐานข้อมูล - ระบบเช็คชื่อการมาเรียน')

@section('page-title', 'จัดการฐานข้อมูล')

@section('page-content')
<div class="row">
    <!-- Database Statistics -->
    <div class="col-12 mb-4">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-bar me-2"></i>สถิติฐานข้อมูล
                </h6>
                <div>
                    <a href="{{ route('admin.database.backup') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-download me-2"></i>Backup
                    </a>
                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#optimizeModal">
                        <i class="fas fa-tools me-2"></i>Optimize
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#resetModal">
                        <i class="fas fa-redo me-2"></i>Reset
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 col-sm-4 col-6 mb-3">
                        <div class="text-center">
                            <div class="h4 text-primary">{{ $stats['users'] ?? 0 }}</div>
                            <small class="text-muted">ผู้ใช้งาน</small>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-4 col-6 mb-3">
                        <div class="text-center">
                            <div class="h4 text-success">{{ $stats['students'] ?? 0 }}</div>
                            <small class="text-muted">นักเรียน</small>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-4 col-6 mb-3">
                        <div class="text-center">
                            <div class="h4 text-info">{{ $stats['subjects'] ?? 0 }}</div>
                            <small class="text-muted">วิชาเรียน</small>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-4 col-6 mb-3">
                        <div class="text-center">
                            <div class="h4 text-warning">{{ $stats['attendances'] ?? 0 }}</div>
                            <small class="text-muted">เช็คชื่อทั่วไป</small>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-4 col-6 mb-3">
                        <div class="text-center">
                            <div class="h4 text-secondary">{{ $stats['subject_attendances'] ?? 0 }}</div>
                            <small class="text-muted">เช็คชื่อรายวิชา</small>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-4 col-6 mb-3">
                        <div class="text-center">
                            <div class="h4 text-dark">{{ $stats['line_attendances'] ?? 0 }}</div>
                            <small class="text-muted">เช็คเข้าแถว</small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 col-sm-4 col-6 mb-3">
                        <div class="text-center">
                            <div class="h4 text-primary">{{ $stats['announcements'] ?? 0 }}</div>
                            <small class="text-muted">ประกาศ</small>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-4 col-6 mb-3">
                        <div class="text-center">
                            <div class="h4 text-success">{{ $stats['blogs'] ?? 0 }}</div>
                            <small class="text-muted">บทความ</small>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-4 col-6 mb-3">
                        <div class="text-center">
                            <div class="h4 text-info">{{ $stats['school_settings'] ?? 0 }}</div>
                            <small class="text-muted">ตั้งค่าโรงเรียน</small>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-4 col-6 mb-3">
                        <div class="text-center">
                            <div class="h4 text-warning">{{ $stats['welcome_contents'] ?? 0 }}</div>
                            <small class="text-muted">เนื้อหาเว็บไซต์</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Management -->
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-table me-2"></i>จัดการตารางข้อมูล
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ตาราง</th>
                                <th>จำนวนข้อมูล</th>
                                <th>การดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tableSizes as $table => $count)
                            <tr>
                                <td>
                                    <strong>{{ ucfirst(str_replace('_', ' ', $table)) }}</strong>
                                    <br><small class="text-muted">{{ $table }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ number_format($count) }}</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.database.table', $table) }}" 
                                           class="btn btn-sm btn-info" title="ดูข้อมูล">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-success" 
                                                onclick="exportTable('{{ $table }}', 'json')" title="Export JSON">
                                            <i class="fas fa-file-code"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning" 
                                                onclick="exportTable('{{ $table }}', 'csv')" title="Export CSV">
                                            <i class="fas fa-file-csv"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="col-lg-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-history me-2"></i>กิจกรรมล่าสุด
                </h6>
            </div>
            <div class="card-body">
                @if(count($recentActivities) > 0)
                    <div class="timeline">
                        @foreach($recentActivities as $activity)
                        <div class="timeline-item mb-3">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="{{ $activity['icon'] }} text-primary"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="small text-muted">{{ $activity['date']->diffForHumans() }}</div>
                                    <div class="small">{{ $activity['description'] }}</div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted">
                        <i class="fas fa-inbox fa-2x mb-2"></i>
                        <p>ไม่มีกิจกรรมล่าสุด</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Optimize Modal -->
<div class="modal fade" id="optimizeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-tools me-2"></i>Optimize Database
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>การ optimize จะช่วยปรับปรุงประสิทธิภาพของฐานข้อมูลโดย:</p>
                <ul>
                    <li>ล้าง cache ทั้งหมด</li>
                    <li>ปรับปรุงโครงสร้างฐานข้อมูล</li>
                    <li>เพิ่มความเร็วในการทำงาน</li>
                </ul>
                <p class="text-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    กระบวนการนี้อาจใช้เวลาสักครู่
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                <form action="{{ route('admin.database.optimize') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-tools me-2"></i>Optimize
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reset Modal -->
<div class="modal fade" id="resetModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>Reset Database
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>คำเตือน:</strong> การ reset จะลบข้อมูลทั้งหมดในฐานข้อมูล!
                </div>
                <p>การดำเนินการนี้จะ:</p>
                <ul>
                    <li>ลบข้อมูลทั้งหมดในฐานข้อมูล</li>
                    <li>รัน migrations ใหม่</li>
                    <li>เพิ่มข้อมูลเริ่มต้น</li>
                    <li>ล้าง cache ทั้งหมด</li>
                </ul>
                <p class="text-danger">
                    <strong>ข้อมูลทั้งหมดจะหายไปและไม่สามารถกู้คืนได้!</strong>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                <form action="{{ route('admin.database.reset') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger" onclick="return confirm('คุณแน่ใจหรือไม่ที่จะ reset ฐานข้อมูล?')">
                        <i class="fas fa-redo me-2"></i>Reset Database
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Export Form -->
<form id="exportForm" action="{{ route('admin.database.export') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="table" id="exportTable">
    <input type="hidden" name="format" id="exportFormat">
</form>

<style>
.timeline-item {
    border-left: 2px solid #e3e6f0;
    padding-left: 1rem;
    position: relative;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -0.5rem;
    top: 0.25rem;
    width: 0.75rem;
    height: 0.75rem;
    background-color: #007bff;
    border-radius: 50%;
}

.timeline-item:last-child {
    border-left: none;
}
</style>

<script>
function exportTable(table, format) {
    document.getElementById('exportTable').value = table;
    document.getElementById('exportFormat').value = format;
    document.getElementById('exportForm').submit();
}
</script>
@endsection
