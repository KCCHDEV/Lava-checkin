@extends('layouts.app')

@section('title', 'เช็คชื่อรายวิชา - ระบบเช็คชื่อการมาเรียน')

@section('page-title', 'เช็คชื่อรายวิชา')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chalkboard-teacher me-2"></i>เช็คชื่อรายวิชา
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('subject-attendances.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="subject_id" class="form-label">วิชา <span class="text-danger">*</span></label>
                            <select class="form-select @error('subject_id') is-invalid @enderror" 
                                    id="subject_id" name="subject_id" required>
                                <option value="">เลือกวิชา</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
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
                                    <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
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
                                   id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">สถานะ <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="">เลือกสถานะ</option>
                                <option value="present" {{ old('status') == 'present' ? 'selected' : '' }}>มาเรียน</option>
                                <option value="absent" {{ old('status') == 'absent' ? 'selected' : '' }}>ขาดเรียน</option>
                                <option value="late" {{ old('status') == 'late' ? 'selected' : '' }}>มาสาย</option>
                                <option value="sick" {{ old('status') == 'sick' ? 'selected' : '' }}>ป่วย</option>
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
                                   id="time" name="time" value="{{ old('time', date('H:i')) }}">
                            @error('time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="period" class="form-label">คาบเรียน</label>
                            <select class="form-select @error('period') is-invalid @enderror" 
                                    id="period" name="period">
                                <option value="">เลือกคาบเรียน</option>
                                <option value="1" {{ old('period') == '1' ? 'selected' : '' }}>คาบที่ 1</option>
                                <option value="2" {{ old('period') == '2' ? 'selected' : '' }}>คาบที่ 2</option>
                                <option value="3" {{ old('period') == '3' ? 'selected' : '' }}>คาบที่ 3</option>
                                <option value="4" {{ old('period') == '4' ? 'selected' : '' }}>คาบที่ 4</option>
                                <option value="5" {{ old('period') == '5' ? 'selected' : '' }}>คาบที่ 5</option>
                                <option value="6" {{ old('period') == '6' ? 'selected' : '' }}>คาบที่ 6</option>
                                <option value="7" {{ old('period') == '7' ? 'selected' : '' }}>คาบที่ 7</option>
                                <option value="8" {{ old('period') == '8' ? 'selected' : '' }}>คาบที่ 8</option>
                            </select>
                            @error('period')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="note" class="form-label">หมายเหตุ</label>
                        <textarea class="form-control @error('note') is-invalid @enderror" 
                                  id="note" name="note" rows="3" placeholder="ระบุหมายเหตุเพิ่มเติม (ถ้ามี)">{{ old('note') }}</textarea>
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

<!-- Quick Add Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>เช็คชื่อหลายคนพร้อมกัน
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('subject-attendances.bulk-store') }}">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="bulk_subject_id" class="form-label">วิชา <span class="text-danger">*</span></label>
                            <select class="form-select" id="bulk_subject_id" name="subject_id" required>
                                <option value="">เลือกวิชา</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="bulk_date" class="form-label">วันที่ <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="bulk_date" name="date" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="bulk_status" class="form-label">สถานะ <span class="text-danger">*</span></label>
                            <select class="form-select" id="bulk_status" name="status" required>
                                <option value="present">มาเรียน</option>
                                <option value="absent">ขาดเรียน</option>
                                <option value="late">มาสาย</option>
                                <option value="sick">ป่วย</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="bulk_period" class="form-label">คาบเรียน</label>
                            <select class="form-select" id="bulk_period" name="period">
                                <option value="">เลือกคาบเรียน</option>
                                <option value="1">คาบที่ 1</option>
                                <option value="2">คาบที่ 2</option>
                                <option value="3">คาบที่ 3</option>
                                <option value="4">คาบที่ 4</option>
                                <option value="5">คาบที่ 5</option>
                                <option value="6">คาบที่ 6</option>
                                <option value="7">คาบที่ 7</option>
                                <option value="8">คาบที่ 8</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="bulk_time" class="form-label">เวลา</label>
                            <input type="time" class="form-control" id="bulk_time" name="time" value="{{ date('H:i') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="bulk_note" class="form-label">หมายเหตุ</label>
                            <input type="text" class="form-control" id="bulk_note" name="note" placeholder="หมายเหตุสำหรับทุกคน">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <label class="form-label mb-0">
                                <i class="fas fa-users me-2"></i>เลือกนักเรียน
                            </label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="select_all">
                                <label class="form-check-label" for="select_all">
                                    <strong>เลือกทั้งหมด</strong>
                                </label>
                            </div>
                        </div>
                        <div class="student-list">
                            <div class="row g-2">
                                @foreach($students as $student)
                                <div class="col-md-6 col-lg-4">
                                    <div class="student-card">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="student_ids[]" 
                                                   value="{{ $student->id }}" id="student_{{ $student->id }}">
                                            <label class="form-check-label w-100" for="student_{{ $student->id }}">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <div class="fw-bold text-primary mb-1">{{ $student->student_id }}</div>
                                                        <div class="text-dark mb-1">{{ $student->name }}</div>
                                                        <small class="text-muted">
                                                            <i class="fas fa-users me-1"></i>{{ $student->class }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            <small>
                                <i class="fas fa-info-circle me-1"></i>
                                เลือกนักเรียนที่ต้องการเช็คชื่อจากรายการด้านบน
                                <span id="selected-count" class="badge bg-primary ms-2">0 คน</span>
                            </small>
                        </div>
                        <button type="submit" class="btn btn-primary" id="submit-bulk" disabled>
                            <i class="fas fa-save me-2"></i>บันทึกทั้งหมด
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.student-list {
    max-height: 400px;
    overflow-y: auto;
    border: 1px solid #e9ecef;
    border-radius: 0.375rem;
    padding: 0.5rem;
    background-color: #f8f9fa;
}

.student-list::-webkit-scrollbar {
    width: 6px;
}

.student-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.student-list::-webkit-scrollbar-thumb {
    background: #667eea;
    border-radius: 3px;
}

.student-list::-webkit-scrollbar-thumb:hover {
    background: #5a6fd8;
}

.student-card {
    background-color: white;
    border: 1px solid #e9ecef;
    border-radius: 0.5rem;
    padding: 0.75rem;
    margin-bottom: 0.5rem;
    transition: all 0.2s ease;
    cursor: pointer;
}

.student-card:hover {
    border-color: #667eea;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.15);
    transform: translateY(-1px);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select All functionality
    const selectAllCheckbox = document.getElementById('select_all');
    const studentCheckboxes = document.querySelectorAll('input[name="student_ids[]"]');
    
    selectAllCheckbox.addEventListener('change', function() {
        studentCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectedCount();
    });
    
    // Update select all based on individual checkboxes
    studentCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectedCount();
        });
    });
    
    // Function to update selected count and button state
    function updateSelectedCount() {
        const checkedCount = document.querySelectorAll('input[name="student_ids[]"]:checked').length;
        const selectedCountElement = document.getElementById('selected-count');
        const submitButton = document.getElementById('submit-bulk');
        
        selectedCountElement.textContent = `${checkedCount} คน`;
        submitButton.disabled = checkedCount === 0;
        
        selectAllCheckbox.checked = checkedCount === studentCheckboxes.length;
        selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < studentCheckboxes.length;
        
        // Update visual feedback for selected cards
        studentCheckboxes.forEach(checkbox => {
            const card = checkbox.closest('.student-card');
            if (checkbox.checked) {
                card.style.borderColor = '#667eea';
                card.style.backgroundColor = '#f8f9ff';
            } else {
                card.style.borderColor = '#e9ecef';
                card.style.backgroundColor = 'white';
            }
        });
    }
    
    // Initialize selected count
    updateSelectedCount();
});
</script>
@endpush
