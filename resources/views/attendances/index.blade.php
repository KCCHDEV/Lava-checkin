@extends('layouts.app')

@section('title', 'เช็คชื่อทั่วไป - ระบบเช็คชื่อการมาเรียน')

@section('page-title', 'เช็คชื่อทั่วไป')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">รายการเช็คชื่อทั่วไป</h5>
    <a href="{{ route('attendances.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>เช็คชื่อนักเรียน
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>นักเรียน</th>
                        <th>วันที่</th>
                        <th>สถานะ</th>
                        <th>หมายเหตุ</th>
                        <th>ผู้บันทึก</th>
                        <th>การดำเนินการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $attendance)
                    <tr>
                        <td>
                            <strong>{{ $attendance->student->name }}</strong><br>
                            <small class="text-muted">{{ $attendance->student->class }}</small>
                        </td>
                        <td>{{ $attendance->date->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge bg-{{ $attendance->status_color }}">
                                {{ $attendance->status_text }}
                            </span>
                        </td>
                        <td>{{ $attendance->note ?: '-' }}</td>
                        <td>{{ $attendance->recorder->name }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('attendances.show', $attendance) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('attendances.edit', $attendance) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('attendances.destroy', $attendance) }}" class="d-inline" 
                                      onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบข้อมูลนี้?')">
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
                            <i class="fas fa-clipboard-list text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3">ยังไม่มีข้อมูลการเช็คชื่อ</p>
                            <a href="{{ route('attendances.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>เริ่มเช็คชื่อ
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($attendances->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $attendances->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

