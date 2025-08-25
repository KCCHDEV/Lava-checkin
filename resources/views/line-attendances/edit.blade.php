@extends('layouts.app')

@section('title', 'แก้ไขการเช็คเข้าแถว - ระบบเช็คชื่อการมาเรียน')

@section('page-title', 'แก้ไขการเช็คเข้าแถว')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i>แก้ไขการเช็คเข้าแถว
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('line-attendances.update', $lineAttendance) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="student_id" class="form-label">นักเรียน <span class="text-danger">*</span></label>
                            <select class="form-select @error('student_id') is-invalid @enderror" 
                                    id="student_id" name="student_id" required>
                                <option value="">เลือกนักเรียน</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" 
                                            {{ (old('student_id', $lineAttendance->student_id) == $student->id) ? 'selected' : '' }}>
                                        {{ $student->student_id }} - {{ $student->name }} ({{ $student->class }})
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="date" class="form-label">วันที่ <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                   id="date" name="date" value="{{ old('date', $lineAttendance->date->format('Y-m-d')) }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="line_number" class="form-label">แถว <span class="text-danger">*</span></label>
                            <select class="form-select @error('line_number') is-invalid @enderror" 
                                    id="line_number" name="line_number" required>
                                <option value="">เลือกแถว</option>
                                <option value="1" {{ old('line_number', $lineAttendance->line_number) == '1' ? 'selected' : '' }}>แถวที่ 1</option>
                                <option value="2" {{ old('line_number', $lineAttendance->line_number) == '2' ? 'selected' : '' }}>แถวที่ 2</option>
                                <option value="3" {{ old('line_number', $lineAttendance->line_number) == '3' ? 'selected' : '' }}>แถวที่ 3</option>
                                <option value="4" {{ old('line_number', $lineAttendance->line_number) == '4' ? 'selected' : '' }}>แถวที่ 4</option>
                                <option value="5" {{ old('line_number', $lineAttendance->line_number) == '5' ? 'selected' : '' }}>แถวที่ 5</option>
                            </select>
                            @error('line_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="position" class="form-label">ลำดับในแถว <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('position') is-invalid @enderror" 
                                   id="position" name="position" value="{{ old('position', $lineAttendance->position) }}" min="1" max="50" required>
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="status" class="form-label">สถานะ <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="">เลือกสถานะ</option>
                                <option value="present" {{ old('status', $lineAttendance->status) == 'present' ? 'selected' : '' }}>มาเรียน</option>
                                <option value="absent" {{ old('status', $lineAttendance->status) == 'absent' ? 'selected' : '' }}>ขาดเรียน</option>
                                <option value="late" {{ old('status', $lineAttendance->status) == 'late' ? 'selected' : '' }}>มาสาย</option>
                                <option value="sick" {{ old('status', $lineAttendance->status) == 'sick' ? 'selected' : '' }}>ป่วย</option>
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
                                   id="time" name="time" value="{{ old('time', $lineAttendance->time ? $lineAttendance->time->format('H:i') : '') }}">
                            @error('time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="note" class="form-label">หมายเหตุ</label>
                            <input type="text" class="form-control @error('note') is-invalid @enderror" 
                                   id="note" name="note" value="{{ old('note', $lineAttendance->note) }}" placeholder="หมายเหตุเพิ่มเติม (ถ้ามี)">
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('line-attendances.index') }}" class="btn btn-secondary">
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
