@extends('layouts.app')

@section('title', 'จัดการวิชา - ระบบเช็คชื่อการมาเรียน')

@section('page-title', 'จัดการวิชา')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">รายการวิชา</h5>
    <a href="{{ route('subjects.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>เพิ่มวิชาใหม่
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>รหัสวิชา</th>
                        <th>ชื่อวิชา</th>
                        <th>ครูผู้สอน</th>
                        <th>ชั้นเรียน</th>
                        <th>สถานะ</th>
                        <th>การดำเนินการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subjects as $subject)
                    <tr>
                        <td><strong>{{ $subject->code }}</strong></td>
                        <td>{{ $subject->name }}</td>
                        <td>{{ $subject->teacher_name }}</td>
                        <td>{{ $subject->class }}</td>
                        <td>
                            @if($subject->is_active)
                                <span class="badge bg-success">เปิดใช้งาน</span>
                            @else
                                <span class="badge bg-secondary">ปิดใช้งาน</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('subjects.show', $subject) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('subjects.toggle-status', $subject) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-toggle-on"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('subjects.destroy', $subject) }}" class="d-inline" 
                                      onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบวิชานี้?')">
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
                        <td colspan="6" class="text-center py-4">
                            <i class="fas fa-book text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3">ยังไม่มีข้อมูลวิชา</p>
                            <a href="{{ route('subjects.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>เพิ่มวิชาแรก
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($subjects->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $subjects->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

