@extends('layouts.app')

@section('title', 'แก้ไขการเช็คชื่อรายวิชา - ระบบเช็คชื่อการมาเรียน')

@section('page-title', 'แก้ไขการเช็คชื่อรายวิชา')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i>แก้ไขการเช็คชื่อรายวิชา
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('subject-attendances.update', $subjectAttendance) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="subject_id" class="form-label">วิชา <span class="text-danger">*</span></label>
                            <select class="form-select @error('subject_id') is-invalid @enderror" 
                                    id="subject_id" name="subject_id" required>
                                <option value="">เลือกวิชา</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" 
                                            {{ (old('subject_id', $subjectAttendance->subject_id) == $subject->id) ? 'selected' : '' }}>
                                        {{ $subject->name }} ({{ $subject->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="student_id" class="form-label">นักเรียน <span class="text-danger">*</span></label>
                            <select class="form-select @error('student_id') is-invalid @enderror" 
                                    id="student_id" name="student_id" required>
                                <option value="">เลือกนักเรียน</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" 
                                            {{ (old('student_id', $subjectAttendance->student_id) == $student->id) ? 'selected' : '' }}>
                                        {{ $student->student_id }} - {{ $student->name }} ({{ $student->class }})
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="date" class="form-label">วันที่ <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                   id="date" name="date" value="{{ old('date', $subjectAttendance->date->format('Y-m-d')) }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">สถานะ <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="">เลือกสถานะ</option>
                                <option value="present" {{ old('status', $subjectAttendance->status) == 'present' ? 'selected' : '' }}>มาเรียน</option>
                                <option value="absent" {{ old('status', $subjectAttendance->status) == 'absent' ? 'selected' : '' }}>ขาดเรียน</option>
                                <option value="late" {{ old('status', $subjectAttendance->status) == 'late' ? 'selected' : '' }}>มาสาย</option>
                                <option value="sick" {{ old('status', $subjectAttendance->status) == 'sick' ? 'selected' : '' }}>ป่วย</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="time" class="form-label">เวลา</label>
                            <input type="time" class="form-control @error('time') is-invalid @enderror" 
                                   id="time" name="time" value="{{ old('time', $subjectAttendance->time ? $subjectAttendance->time->format('H:i') : '') }}">
                            @error('time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="period" class="form-label">คาบเรียน</label>
                            <select class="form-select @error('period') is-invalid @enderror" 
                                    id="period" name="period">
                                <option value="">เลือกคาบเรียน</option>
                                <option value="1" {{ old('period', $subjectAttendance->period) == '1' ? 'selected' : '' }}>คาบที่ 1</option>
                                <option value="2" {{ old('period', $subjectAttendance->period) == '2' ? 'selected' : '' }}>คาบที่ 2</option>
                                <option value="3" {{ old('period', $subjectAttendance->period) == '3' ? 'selected' : '' }}>คาบที่ 3</option>
                                <option value="4" {{ old('period', $subjectAttendance->period) == '4' ? 'selected' : '' }}>คาบที่ 4</option>
                                <option value="5" {{ old('period', $subjectAttendance->period) == '5' ? 'selected' : '' }}>คาบที่ 5</option>
                                <option value="6" {{ old('period', $subjectAttendance->period) == '6' ? 'selected' : '' }}>คาบที่ 6</option>
                                <option value="7" {{ old('period', $subjectAttendance->period) == '7' ? 'selected' : '' }}>คาบที่ 7</option>
                                <option value="8" {{ old('period', $subjectAttendance->period) == '8' ? 'selected' : '' }}>คาบที่ 8</option>
                            </select>
                            @error('period')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="note" class="form-label">หมายเหตุ</label>
                        <textarea class="form-control @error('note') is-invalid @enderror" 
                                  id="note" name="note" rows="3">{{ old('note', $subjectAttendance->note) }}</textarea>
                        @error('note')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('subject-attendances.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>กลับ
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>บันทึก
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
