@extends('layouts.app')

@section('title', 'ดูข้อมูลการเช็คชื่อ - ระบบเช็คชื่อการมาเรียน')

@section('page-title', 'ดูข้อมูลการเช็คชื่อ')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-eye me-2"></i>รายละเอียดการเช็คชื่อ
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">ข้อมูลนักเรียน</h6>
                        <div class="mb-3">
                            <strong>รหัสนักเรียน:</strong> {{ $attendance->student->student_id }}<br>
                            <strong>ชื่อ-นามสกุล:</strong> {{ $attendance->student->name }}<br>
                            <strong>ชั้นเรียน:</strong> {{ $attendance->student->class }}
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="text-muted">ข้อมูลการเช็คชื่อ</h6>
                        <div class="mb-3">
                            <strong>วันที่:</strong> {{ $attendance->date->format('d/m/Y') }}<br>
                            @if($attendance->time)
                            <strong>เวลา:</strong> {{ $attendance->time->format('H:i') }}<br>
                            @endif
                            <strong>สถานะ:</strong> 
                            <span class="badge bg-{{ $attendance->status_color }}">
                                {{ $attendance->status_text }}
                            </span>
                        </div>
                    </div>
                </div>
                
                @if($attendance->note)
                <div class="row">
                    <div class="col-12">
                        <h6 class="text-muted">หมายเหตุ</h6>
                        <p class="mb-3">{{ $attendance->note }}</p>
                    </div>
                </div>
                @endif
                
                <div class="row">
                    <div class="col-12">
                        <h6 class="text-muted">ข้อมูลการบันทึก</h6>
                        <div class="mb-3">
                            <strong>ผู้บันทึก:</strong> {{ $attendance->recorder->name }}<br>
                            <strong>เวลาที่บันทึก:</strong> {{ $attendance->created_at->format('d/m/Y H:i:s') }}
                            @if($attendance->updated_at != $attendance->created_at)
                            <br><strong>แก้ไขล่าสุด:</strong> {{ $attendance->updated_at->format('d/m/Y H:i:s') }}
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('attendances.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>กลับ
                    </a>
                    <div>
                        <a href="{{ route('attendances.edit', $attendance) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>แก้ไข
                        </a>
                        <form method="POST" action="{{ route('attendances.destroy', $attendance) }}" class="d-inline" 
                              onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบข้อมูลนี้?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash me-2"></i>ลบ
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
