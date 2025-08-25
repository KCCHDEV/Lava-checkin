@extends('layouts.app')

@section('title', 'เช็คเข้าแถว - ระบบเช็คชื่อการมาเรียน')

@section('page-title', 'เช็คเข้าแถว')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-list-ol me-2"></i>รายการเช็คเข้าแถว
                </h5>
                <a href="{{ route('line-attendances.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>เช็คเข้าแถวใหม่
                </a>
            </div>
            <div class="card-body">
                <!-- Filter Section -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label for="date_filter" class="form-label">วันที่</label>
                        <input type="date" class="form-control" id="date_filter" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="status_filter" class="form-label">สถานะ</label>
                        <select class="form-select" id="status_filter">
                            <option value="">ทุกสถานะ</option>
                            <option value="present">มาเรียน</option>
                            <option value="absent">ขาดเรียน</option>
                            <option value="late">มาสาย</option>
                            <option value="sick">ป่วย</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="line_filter" class="form-label">แถว</label>
                        <select class="form-select" id="line_filter">
                            <option value="">ทุกแถว</option>
                            <option value="1">แถวที่ 1</option>
                            <option value="2">แถวที่ 2</option>
                            <option value="3">แถวที่ 3</option>
                            <option value="4">แถวที่ 4</option>
                            <option value="5">แถวที่ 5</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="button" class="btn btn-secondary" id="clear_filters">
                            <i class="fas fa-times me-2"></i>ล้างตัวกรอง
                        </button>
                    </div>
                </div>

                <!-- Attendance Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>วันที่</th>
                                <th>นักเรียน</th>
                                <th>แถว</th>
                                <th>ลำดับ</th>
                                <th>สถานะ</th>
                                <th>เวลา</th>
                                <th>ผู้บันทึก</th>
                                <th>การจัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lineAttendances as $attendance)
                            <tr>
                                <td>{{ $attendance->date->format('d/m/Y') }}</td>
                                <td>
                                    <strong>{{ $attendance->student->student_id }}</strong><br>
                                    <small>{{ $attendance->student->name }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">แถวที่ {{ $attendance->line_number }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $attendance->position }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $attendance->status_color }}">
                                        {{ $attendance->status_text }}
                                    </span>
                                </td>
                                <td>
                                    @if($attendance->time)
                                        {{ $attendance->time->format('H:i') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $attendance->recorder->name }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('line-attendances.show', $attendance) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('line-attendances.edit', $attendance) }}" 
                                           class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('line-attendances.destroy', $attendance) }}" 
                                              class="d-inline" onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบข้อมูลนี้?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p>ไม่พบข้อมูลการเช็คเข้าแถว</p>
                                        <a href="{{ route('line-attendances.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>เช็คเข้าแถวใหม่
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($lineAttendances->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $lineAttendances->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $stats['total'] ?? 0 }}</h4>
                        <small>ทั้งหมด</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-list-ol fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $stats['present'] ?? 0 }}</h4>
                        <small>มาเรียน</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $stats['late'] ?? 0 }}</h4>
                        <small>มาสาย</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $stats['absent'] ?? 0 }}</h4>
                        <small>ขาดเรียน</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-times-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const dateFilter = document.getElementById('date_filter');
    const statusFilter = document.getElementById('status_filter');
    const lineFilter = document.getElementById('line_filter');
    const clearFiltersBtn = document.getElementById('clear_filters');
    
    function applyFilters() {
        const params = new URLSearchParams();
        
        if (dateFilter.value) params.append('date', dateFilter.value);
        if (statusFilter.value) params.append('status', statusFilter.value);
        if (lineFilter.value) params.append('line_number', lineFilter.value);
        
        window.location.href = '{{ route("line-attendances.index") }}?' + params.toString();
    }
    
    dateFilter.addEventListener('change', applyFilters);
    statusFilter.addEventListener('change', applyFilters);
    lineFilter.addEventListener('change', applyFilters);
    
    clearFiltersBtn.addEventListener('click', function() {
        window.location.href = '{{ route("line-attendances.index") }}';
    });
});
</script>
@endpush
