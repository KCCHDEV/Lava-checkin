@extends('layouts.app')

@section('title', 'เช็คชื่อรายวิชา - ระบบเช็คชื่อการมาเรียน')

@section('page-title', 'เช็คชื่อรายวิชา')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-chalkboard-teacher me-2"></i>รายการเช็คชื่อรายวิชา
                </h5>
                <a href="{{ route('subject-attendances.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>เช็คชื่อใหม่
                </a>
            </div>
            <div class="card-body">
                <!-- Filter Section -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label for="subject_filter" class="form-label">วิชา</label>
                        <select class="form-select" id="subject_filter">
                            <option value="">ทุกวิชา</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
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
                                <th>วิชา</th>
                                <th>นักเรียน</th>
                                <th>สถานะ</th>
                                <th>เวลา</th>
                                <th>ผู้บันทึก</th>
                                <th>การจัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subjectAttendances as $attendance)
                            <tr>
                                <td>{{ $attendance->date->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $attendance->subject->name }}</span>
                                </td>
                                <td>
                                    <strong>{{ $attendance->student->student_id }}</strong><br>
                                    <small>{{ $attendance->student->name }}</small>
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
                                        <a href="{{ route('subject-attendances.show', $attendance) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('subject-attendances.edit', $attendance) }}" 
                                           class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('subject-attendances.destroy', $attendance) }}" 
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
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p>ไม่พบข้อมูลการเช็คชื่อรายวิชา</p>
                                        <a href="{{ route('subject-attendances.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>เช็คชื่อใหม่
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($subjectAttendances->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $subjectAttendances->links() }}
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
                        <i class="fas fa-clipboard-list fa-2x"></i>
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
    const subjectFilter = document.getElementById('subject_filter');
    const dateFilter = document.getElementById('date_filter');
    const statusFilter = document.getElementById('status_filter');
    const clearFiltersBtn = document.getElementById('clear_filters');
    
    function applyFilters() {
        const params = new URLSearchParams();
        
        if (subjectFilter.value) params.append('subject_id', subjectFilter.value);
        if (dateFilter.value) params.append('date', dateFilter.value);
        if (statusFilter.value) params.append('status', statusFilter.value);
        
        window.location.href = '{{ route("subject-attendances.index") }}?' + params.toString();
    }
    
    subjectFilter.addEventListener('change', applyFilters);
    dateFilter.addEventListener('change', applyFilters);
    statusFilter.addEventListener('change', applyFilters);
    
    clearFiltersBtn.addEventListener('click', function() {
        window.location.href = '{{ route("subject-attendances.index") }}';
    });
});
</script>
@endpush
