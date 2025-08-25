@extends('layouts.app')

@section('title', 'ระบบเช็คชื่อ QR Code - ระบบเช็คชื่อการมาเรียน')

@section('page-title', 'ระบบเช็คชื่อ QR Code')

@push('styles')
<style>
.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border-radius: 0.5rem;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 0.5rem 0.5rem 0 0 !important;
    border: none;
}

.stats-card {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    border-radius: 0.5rem;
    padding: 1.5rem;
    margin-bottom: 1rem;
}

.stats-card h3 {
    font-size: 2rem;
    font-weight: bold;
    margin: 0;
}

.stats-card p {
    margin: 0;
    opacity: 0.9;
}

.btn-generate-qr {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 0.5rem;
    padding: 0.75rem 1.5rem;
    color: white;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
    transition: transform 0.2s ease;
}

.btn-generate-qr:hover {
    transform: translateY(-2px);
    color: white;
}

.status-badge {
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
    font-weight: 600;
    font-size: 0.875rem;
}

.table-responsive {
    border-radius: 0.5rem;
    overflow: hidden;
}

.table th {
    background-color: #f8f9fa;
    border: none;
    font-weight: 600;
    color: #495057;
}

.table td {
    border: none;
    border-bottom: 1px solid #dee2e6;
    vertical-align: middle;
}

.filter-card {
    background: #f8f9fa;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 1.5rem;
}

.live-update {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.7; }
    100% { opacity: 1; }
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h3>{{ $stats['total'] }}</h3>
                <p>เช็คชื่อทั้งหมดวันนี้</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                <h3>{{ $stats['present'] }}</h3>
                <p>เช็คชื่อแล้ว</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <h3>{{ $stats['late'] }}</h3>
                <p>มาสาย</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <h3>{{ $stats['students_total'] - $stats['total'] }}</h3>
                <p>ยังไม่เช็คชื่อ</p>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex gap-3 flex-wrap">
                <a href="{{ route('check-ins.create') }}" class="btn-generate-qr">
                    <i class="fas fa-qrcode me-2"></i>สร้าง QR Code
                </a>
                <a href="{{ route('check-ins.manual') }}" class="btn btn-outline-primary">
                    <i class="fas fa-plus me-2"></i>เช็คชื่อด้วยตนเอง
                </a>
                <a href="{{ route('check-ins.export', ['start_date' => $date, 'end_date' => $date]) }}" class="btn btn-outline-success">
                    <i class="fas fa-download me-2"></i>ส่งออกข้อมูล
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-card">
        <form method="GET" action="{{ route('check-ins.index') }}" class="row align-items-end">
            <div class="col-md-3">
                <label for="date" class="form-label">วันที่</label>
                <input type="date" class="form-control" id="date" name="date" value="{{ $date }}">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">สถานะ</label>
                <select class="form-control" id="status" name="status">
                    <option value="">ทั้งหมด</option>
                    <option value="present" {{ $status == 'present' ? 'selected' : '' }}>เช็คชื่อแล้ว</option>
                    <option value="late" {{ $status == 'late' ? 'selected' : '' }}>มาสาย</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search me-1"></i>ค้นหา
                </button>
                <a href="{{ route('check-ins.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-refresh me-1"></i>รีเซ็ต
                </a>
            </div>
            <div class="col-md-3 text-end">
                <div class="live-update">
                    <small class="text-muted">
                        <i class="fas fa-sync-alt me-1"></i>อัปเดตอัตโนมัติทุก 30 วินาที
                    </small>
                </div>
            </div>
        </form>
    </div>

    <!-- Check-ins Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>รายการเช็คชื่อ - {{ Carbon\Carbon::parse($date)->format('d/m/Y') }}
            </h5>
        </div>
        <div class="card-body p-0">
            @if($checkIns->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>รหัสนักเรียน</th>
                                <th>ชื่อนักเรียน</th>
                                <th>เวลาเช็คชื่อ</th>
                                <th>สถานะ</th>
                                <th>สถานที่</th>
                                <th>ผู้บันทึก</th>
                                <th>การจัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($checkIns as $checkIn)
                                <tr>
                                    <td>
                                        <strong>{{ $checkIn->student->student_code ?? 'N/A' }}</strong>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <div class="fw-bold">{{ $checkIn->student->name ?? 'N/A' }}</div>
                                                <small class="text-muted">{{ $checkIn->student->class ?? '' }} - {{ $checkIn->student->major ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-bold">{{ $checkIn->check_in_time->format('H:i:s') }}</div>
                                            <small class="text-muted">{{ $checkIn->check_in_time->format('d/m/Y') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge bg-{{ $checkIn->status_color }}">
                                            {{ $checkIn->status_text }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $checkIn->location ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $checkIn->recorder->name ?? 'System' }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('check-ins.show', $checkIn) }}" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form method="POST" action="{{ route('check-ins.destroy', $checkIn) }}" class="d-inline" onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบข้อมูลนี้?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="p-3">
                    {{ $checkIns->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-inbox fa-3x text-muted"></i>
                    </div>
                    <h5 class="text-muted">ไม่มีข้อมูลการเช็คชื่อ</h5>
                    <p class="text-muted">ในวันที่ {{ Carbon\Carbon::parse($date)->format('d/m/Y') }}</p>
                    <a href="{{ route('check-ins.create') }}" class="btn btn-primary">
                        <i class="fas fa-qrcode me-2"></i>สร้าง QR Code เพื่อเริ่มเช็คชื่อ
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto refresh every 30 seconds
setInterval(function() {
    if (window.location.search.indexOf('date') === -1 || window.location.search.indexOf('date={{ date("Y-m-d") }}') !== -1) {
        window.location.reload();
    }
}, 30000);

// Update timestamp display
function updateTimestamp() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('th-TH');
    document.querySelectorAll('.live-update small').forEach(el => {
        el.innerHTML = '<i class="fas fa-sync-alt me-1"></i>อัปเดตล่าสุด ' + timeString;
    });
}

setInterval(updateTimestamp, 1000);
</script>
@endpush