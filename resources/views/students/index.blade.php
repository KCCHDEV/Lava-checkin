@extends('layouts.app')

@section('title', 'จัดการนักเรียน - ระบบเช็คชื่อการมาเรียน')

@section('page-title', 'จัดการนักเรียน')

@section('page-content')
<!-- Search and Filter Section -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-search me-2"></i>ค้นหาและกรองข้อมูล
        </h6>
    </div>
    <div class="card-body">
        <form action="{{ route('students.index') }}" method="GET" id="searchForm">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">ค้นหา</label>
                        <input type="text" class="form-control" name="search" 
                               value="{{ request('search') }}" 
                               placeholder="ชื่อ, รหัสนักเรียน, อีเมล, เบอร์โทร...">
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label">ชั้นเรียน</label>
                        <select class="form-select" name="class">
                            <option value="">ทั้งหมด</option>
                            @foreach($classes as $class)
                                <option value="{{ $class }}" {{ request('class') === $class ? 'selected' : '' }}>
                                    {{ $class }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label">สาขา</label>
                        <select class="form-select" name="major">
                            <option value="">ทั้งหมด</option>
                            @foreach($majors as $major)
                                <option value="{{ $major }}" {{ request('major') === $major ? 'selected' : '' }}>
                                    {{ $major }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label">สถานะ</label>
                        <select class="form-select" name="status">
                            <option value="">ทั้งหมด</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>กำลังศึกษา</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>ไม่ศึกษา</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label">เรียงลำดับ</label>
                        <select class="form-select" name="sort_by">
                            <option value="name" {{ request('sort_by', 'name') === 'name' ? 'selected' : '' }}>ชื่อ</option>
                            <option value="student_id" {{ request('sort_by') === 'student_id' ? 'selected' : '' }}>รหัสนักเรียน</option>
                            <option value="class" {{ request('sort_by') === 'class' ? 'selected' : '' }}>ชั้นเรียน</option>
                            <option value="major" {{ request('sort_by') === 'major' ? 'selected' : '' }}>สาขา</option>
                            <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>วันที่เพิ่ม</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">ลำดับ</label>
                        <select class="form-select" name="sort_order">
                            <option value="asc" {{ request('sort_order', 'asc') === 'asc' ? 'selected' : '' }}>น้อยไปมาก</option>
                            <option value="desc" {{ request('sort_order') === 'desc' ? 'selected' : '' }}>มากไปน้อย</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3 d-flex gap-2" style="margin-top: 32px;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>ค้นหา
                        </button>
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">
                            <i class="fas fa-undo me-2"></i>รีเซ็ต
                        </a>
                        <button type="button" class="btn btn-success" onclick="exportStudents()">
                            <i class="fas fa-download me-2"></i>ส่งออก
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            นักเรียนทั้งหมด
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            กำลังศึกษา
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            ไม่ศึกษา
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['inactive'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-times fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            ชั้นเรียน
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['classes'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card border-left-secondary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                            สาขา
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['majors'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-book fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            ผลการค้นหา
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $students->total() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-search fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Students Table -->
<div class="card shadow">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-table me-2"></i>รายชื่อนักเรียน
        </h6>
        <a href="{{ route('students.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-2"></i>เพิ่มนักเรียน
        </a>
    </div>
    <div class="card-body">
        @if($students->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>รหัสนักเรียน</th>
                            <th>ชื่อ-นามสกุล</th>
                            <th>ชั้นเรียน</th>
                            <th>สาขา</th>
                            <th>สถานะ</th>
                            <th>เบอร์โทร</th>
                            <th>การดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr>
                            <td>
                                <strong>{{ $student->student_id }}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $student->name }}</div>
                                        @if($student->email)
                                            <small class="text-muted">{{ $student->email }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $student->class }}</span>
                            </td>
                            <td>{{ $student->major }}</td>
                            <td>
                                @if($student->status === 'active')
                                    <span class="badge bg-success">กำลังศึกษา</span>
                                @else
                                    <span class="badge bg-warning">ไม่ศึกษา</span>
                                @endif
                            </td>
                            <td>
                                @if($student->phone)
                                    <a href="tel:{{ $student->phone }}" class="text-decoration-none">
                                        {{ $student->phone }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('students.show', $student) }}" 
                                       class="btn btn-sm btn-info" title="ดูข้อมูล">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('students.edit', $student) }}" 
                                       class="btn btn-sm btn-warning" title="แก้ไข">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" 
                                            onclick="deleteStudent({{ $student->id }})" title="ลบ">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $students->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">ไม่พบข้อมูลนักเรียน</h5>
                <p class="text-muted">ลองปรับเปลี่ยนเงื่อนไขการค้นหาหรือเพิ่มนักเรียนใหม่</p>
                <a href="{{ route('students.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>เพิ่มนักเรียน
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Export Form -->
<form id="exportForm" action="{{ route('students.export') }}" method="GET" style="display: none;">
    <input type="hidden" name="search" value="{{ request('search') }}">
    <input type="hidden" name="class" value="{{ request('class') }}">
    <input type="hidden" name="major" value="{{ request('major') }}">
    <input type="hidden" name="status" value="{{ request('status') }}">
</form>

<!-- Delete Form -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<style>
.avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-secondary {
    border-left: 0.25rem solid #858796 !important;
}

.border-left-danger {
    border-left: 0.25rem solid #e74a3b !important;
}

.text-gray-300 {
    color: #dddfeb !important;
}

.text-gray-800 {
    color: #5a5c69 !important;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}
</style>

<script>
function deleteStudent(studentId) {
    if (confirm('คุณแน่ใจหรือไม่ที่จะลบนักเรียนคนนี้?')) {
        const form = document.getElementById('deleteForm');
        form.action = `/students/${studentId}`;
        form.submit();
    }
}

function exportStudents() {
    document.getElementById('exportForm').submit();
}

// Auto-submit form when filters change
document.addEventListener('DOMContentLoaded', function() {
    const filterSelects = document.querySelectorAll('select[name="class"], select[name="major"], select[name="status"]');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('searchForm').submit();
        });
    });
});
</script>
@endsection

