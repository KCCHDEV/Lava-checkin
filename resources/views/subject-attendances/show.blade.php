@extends('layouts.app')

@section('title', 'ดูข้อมูลการเช็คชื่อรายวิชา - ระบบเช็คชื่อการมาเรียน')

@section('page-title', 'ดูข้อมูลการเช็คชื่อรายวิชา')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-eye me-2"></i>รายละเอียดการเช็คชื่อรายวิชา
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">ข้อมูลวิชา</h6>
                        <div class="mb-3">
                            <strong>ชื่อวิชา:</strong> {{ $subjectAttendance->subject->name }}<br>
                            <strong>รหัสวิชา:</strong> {{ $subjectAttendance->subject->code }}<br>
                            <strong>หน่วยกิต:</strong> {{ $subjectAttendance->subject->credits }}
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="text-muted">ข้อมูลนักเรียน</h6>
                        <div class="mb-3">
                            <strong>รหัสนักเรียน:</strong> {{ $subjectAttendance->student->student_id }}<br>
                            <strong>ชื่อ-นามสกุล:</strong> {{ $subjectAttendance->student->name }}<br>
                            <strong>ชั้นเรียน:</strong> {{ $subjectAttendance->student->class }}
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">ข้อมูลการเช็คชื่อ</h6>
                        <div class="mb-3">
                            <strong>วันที่:</strong> {{ $subjectAttendance->date->format('d/m/Y') }}<br>
                            @if($subjectAttendance->time)
                            <strong>เวลา:</strong> {{ $subjectAttendance->time->format('H:i') }}<br>
                            @endif
                            @if($subjectAttendance->period)
                            <strong>คาบเรียน:</strong> คาบที่ {{ $subjectAttendance->period }}<br>
                            @endif
                            <strong>สถานะ:</strong> 
                            <span class="badge bg-{{ $subjectAttendance->status_color }}">
                                {{ $subjectAttendance->status_text }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="text-muted">ข้อมูลการบันทึก</h6>
                        <div class="mb-3">
                            <strong>ผู้บันทึก:</strong> {{ $subjectAttendance->recorder->name }}<br>
                            <strong>เวลาที่บันทึก:</strong> {{ $subjectAttendance->created_at->format('d/m/Y H:i:s') }}
                            @if($subjectAttendance->updated_at != $subjectAttendance->created_at)
                            <br><strong>แก้ไขล่าสุด:</strong> {{ $subjectAttendance->updated_at->format('d/m/Y H:i:s') }}
                            @endif
                        </div>
                    </div>
                </div>
                
                @if($subjectAttendance->note)
                <div class="row">
                    <div class="col-12">
                        <h6 class="text-muted">หมายเหตุ</h6>
                        <p class="mb-3">{{ $subjectAttendance->note }}</p>
                    </div>
                </div>
                @endif
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('subject-attendances.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>กลับ
                    </a>
                    <div>
                        <a href="{{ route('subject-attendances.edit', $subjectAttendance) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>แก้ไข
                        </a>
                        <form method="POST" action="{{ route('subject-attendances.destroy', $subjectAttendance) }}" class="d-inline" 
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
