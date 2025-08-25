@extends('layouts.app')

@section('title', 'เช็คชื่อแบบแมนนวล - ระบบจัดการโรงเรียน')

@section('page-title', 'เช็คชื่อแบบแมนนวล')

@push('styles')
<style>
.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border-radius: 0.5rem;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 0.5rem 0.5rem 0 0 !important;
    border: none;
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.form-control, .form-select {
    border-radius: 0.375rem;
    border: 1px solid #ced4da;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn {
    border-radius: 0.375rem;
    font-weight: 500;
    padding: 0.5rem 1rem;
    transition: all 0.15s ease-in-out;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a67d8 0%, #6b46a3 100%);
    transform: translateY(-1px);
}

.student-search {
    background: #f8f9fa;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 1.5rem;
}

.student-grid {
    max-height: 400px;
    overflow-y: auto;
    background: #f8f9fa;
    border-radius: 0.5rem;
    padding: 1rem;
}

.student-card {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 0.75rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.student-card:hover {
    border-color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.student-card.selected {
    border-color: #667eea;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.student-card.already-checked {
    border-color: #28a745;
    background: #d4edda;
    opacity: 0.7;
    cursor: not-allowed;
}

.student-card.already-checked:hover {
    transform: none;
    box-shadow: none;
}

.student-name {
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 0.25rem;
}

.student-details {
    font-size: 0.9rem;
    opacity: 0.8;
}

.quick-actions {
    background: #e3f2fd;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 1rem;
}

.quick-time-btn {
    border: 1px solid #667eea;
    background: white;
    color: #667eea;
    border-radius: 0.375rem;
    padding: 0.375rem 0.75rem;
    margin: 0.25rem;
    transition: all 0.15s ease;
    cursor: pointer;
}

.quick-time-btn:hover, .quick-time-btn.active {
    background: #667eea;
    color: white;
}

.status-selection {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
}

.status-option {
    flex: 1;
    padding: 1rem;
    border: 2px solid #e9ecef;
    border-radius: 0.5rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
}

.status-option:hover {
    border-color: #667eea;
    transform: translateY(-2px);
}

.status-option.selected {
    border-color: #667eea;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.status-option.present.selected {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    border-color: #11998e;
}

.status-option.late.selected {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    border-color: #f093fb;
}

.bulk-actions {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 1rem;
}

.selected-count {
    background: #667eea;
    color: white;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: 600;
}

@media (max-width: 768px) {
    .status-selection {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .student-grid {
        max-height: 300px;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <!-- Student Selection -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2"></i>เลือกนักเรียน
                        <span class="selected-count ms-2" id="selectedCount">0</span>
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Search -->
                    <div class="student-search">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="searchInput" class="form-label">ค้นหานักเรียน</label>
                                <input type="text" class="form-control" id="searchInput" 
                                       placeholder="ชื่อ, รหัสนักเรียน, ชั้นเรียน...">
                            </div>
                            <div class="col-md-3">
                                <label for="classFilter" class="form-label">ชั้นเรียน</label>
                                <select class="form-control" id="classFilter">
                                    <option value="">ทั้งหมด</option>
                                    @foreach($students->pluck('class')->unique()->filter() as $class)
                                        <option value="{{ $class }}">{{ $class }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="majorFilter" class="form-label">สาขา</label>
                                <select class="form-control" id="majorFilter">
                                    <option value="">ทั้งหมด</option>
                                    @foreach($students->pluck('major')->unique()->filter() as $major)
                                        <option value="{{ $major }}">{{ $major }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="selectAll()">
                                        เลือกทั้งหมด
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearSelection()">
                                        ยกเลิกการเลือก
                                    </button>
                                    <button type="button" class="btn btn-outline-info btn-sm" onclick="toggleAlreadyChecked()">
                                        <span id="hideShowText">ซ่อน</span>ผู้ที่เช็คชื่อแล้ว
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Student Grid -->
                    <div class="student-grid" id="studentGrid">
                        @foreach($students as $student)
                            @php
                                $alreadyChecked = \App\Models\CheckIn::where('student_id', $student->id)
                                    ->whereDate('check_in_date', $date)
                                    ->exists();
                            @endphp
                            <div class="student-card {{ $alreadyChecked ? 'already-checked' : '' }}" 
                                 data-student-id="{{ $student->id }}"
                                 data-student-name="{{ $student->name }}"
                                 data-student-code="{{ $student->student_code }}"
                                 data-class="{{ $student->class ?? '' }}"
                                 data-major="{{ $student->major ?? '' }}"
                                 data-search-text="{{ strtolower($student->name . ' ' . $student->student_code . ' ' . ($student->class ?? '') . ' ' . ($student->major ?? '')) }}"
                                 data-already-checked="{{ $alreadyChecked ? 'true' : 'false' }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="student-name">{{ $student->name }}</div>
                                        <div class="student-details">
                                            รหัส: {{ $student->student_code }}
                                            @if($student->class || $student->major)
                                                | {{ $student->class }} {{ $student->major }}
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        @if($alreadyChecked)
                                            <span class="badge bg-success">เช็คชื่อแล้ว</span>
                                        @else
                                            <i class="fas fa-circle-check d-none text-white" style="font-size: 1.5rem;"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="text-muted small mt-2" id="studentCount">
                        แสดง {{ $students->count() }} คน
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Check-in Form -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-check-circle me-2"></i>ข้อมูลการเช็คชื่อ
                    </h5>
                </div>
                <div class="card-body">
                    <form id="checkinForm" method="POST" action="{{ route('check-ins.manual.store') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="check_in_date" class="form-label">วันที่</label>
                            <input type="date" class="form-control" id="check_in_date" 
                                   name="check_in_date" value="{{ $date }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="check_in_time" class="form-label">เวลา</label>
                            <input type="time" class="form-control" id="check_in_time" 
                                   name="check_in_time" value="{{ now()->format('H:i') }}" required>
                            <div class="quick-actions mt-2">
                                <small class="text-muted d-block mb-1">เวลาด่วน:</small>
                                <span class="quick-time-btn" data-time="08:00">08:00</span>
                                <span class="quick-time-btn" data-time="08:30">08:30</span>
                                <span class="quick-time-btn active" data-time="{{ now()->format('H:i') }}">ปัจจุบัน</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">สถานะ</label>
                            <div class="status-selection">
                                <div class="status-option present selected" data-status="present">
                                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                                    <div class="fw-bold">เช็คชื่อแล้ว</div>
                                    <small>มาเรียนตรงเวลา</small>
                                </div>
                                <div class="status-option late" data-status="late">
                                    <i class="fas fa-clock fa-2x mb-2"></i>
                                    <div class="fw-bold">มาสาย</div>
                                    <small>มาหลังเวลา</small>
                                </div>
                            </div>
                            <input type="hidden" name="status" id="statusInput" value="present">
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">สถานที่</label>
                            <input type="text" class="form-control" id="location" 
                                   name="location" placeholder="ห้องเรียน, อาคาร...">
                        </div>

                        <div class="mb-3">
                            <label for="note" class="form-label">หมายเหตุ</label>
                            <textarea class="form-control" id="note" name="note" 
                                      rows="3" placeholder="หมายเหตุเพิ่มเติม..."></textarea>
                        </div>

                        <!-- Selected Students Display -->
                        <div class="mb-3">
                            <label class="form-label">นักเรียนที่เลือก <span id="selectedStudentCount">(0 คน)</span></label>
                            <div class="alert alert-info" id="selectedStudentsList">
                                ยังไม่ได้เลือกนักเรียน
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                <i class="fas fa-save me-2"></i>บันทึกการเช็คชื่อ
                            </button>
                            <a href="{{ route('check-ins.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>กลับ
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Bulk Actions -->
            <div class="bulk-actions" id="bulkActions" style="display: none;">
                <h6><i class="fas fa-tasks me-2"></i>การดำเนินการแบบกลุ่ม</h6>
                <p class="small mb-2">เลือกแล้ว <span id="bulkSelectedCount">0</span> คน</p>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-success btn-sm" onclick="bulkCheckin('present')">
                        <i class="fas fa-check me-1"></i>เช็คชื่อทั้งหมด (เข้าเรียน)
                    </button>
                    <button type="button" class="btn btn-warning btn-sm" onclick="bulkCheckin('late')">
                        <i class="fas fa-clock me-1"></i>เช็คชื่อทั้งหมด (มาสาย)
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const studentCards = document.querySelectorAll('.student-card');
    const selectedStudentsList = document.getElementById('selectedStudentsList');
    const selectedStudentCount = document.getElementById('selectedStudentCount');
    const selectedCount = document.getElementById('selectedCount');
    const submitBtn = document.getElementById('submitBtn');
    const checkinForm = document.getElementById('checkinForm');
    const searchInput = document.getElementById('searchInput');
    const classFilter = document.getElementById('classFilter');
    const majorFilter = document.getElementById('majorFilter');
    const bulkActions = document.getElementById('bulkActions');
    const bulkSelectedCount = document.getElementById('bulkSelectedCount');
    const hideShowText = document.getElementById('hideShowText');
    
    let selectedStudents = new Set();
    let hideAlreadyChecked = false;

    // Student selection
    studentCards.forEach(card => {
        if (card.dataset.alreadyChecked === 'false') {
            card.addEventListener('click', function() {
                const studentId = this.dataset.studentId;
                const studentName = this.dataset.studentName;
                const studentCode = this.dataset.studentCode;
                
                if (this.classList.contains('selected')) {
                    // Deselect
                    this.classList.remove('selected');
                    this.querySelector('.fas.fa-circle-check').classList.add('d-none');
                    selectedStudents.delete(studentId);
                } else {
                    // Select
                    this.classList.add('selected');
                    this.querySelector('.fas.fa-circle-check').classList.remove('d-none');
                    selectedStudents.add(studentId);
                }
                
                updateSelectedDisplay();
            });
        }
    });

    // Status selection
    document.querySelectorAll('.status-option').forEach(option => {
        option.addEventListener('click', function() {
            document.querySelectorAll('.status-option').forEach(o => o.classList.remove('selected'));
            this.classList.add('selected');
            document.getElementById('statusInput').value = this.dataset.status;
        });
    });

    // Quick time buttons
    document.querySelectorAll('.quick-time-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.quick-time-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            document.getElementById('check_in_time').value = this.dataset.time;
        });
    });

    // Search and filter
    function filterStudents() {
        const searchTerm = searchInput.value.toLowerCase();
        const classValue = classFilter.value;
        const majorValue = majorFilter.value;
        let visibleCount = 0;

        studentCards.forEach(card => {
            const searchText = card.dataset.searchText;
            const cardClass = card.dataset.class;
            const cardMajor = card.dataset.major;
            const alreadyChecked = card.dataset.alreadyChecked === 'true';
            
            const matchesSearch = searchText.includes(searchTerm);
            const matchesClass = !classValue || cardClass === classValue;
            const matchesMajor = !majorValue || cardMajor === majorValue;
            const shouldShow = (!hideAlreadyChecked || !alreadyChecked);
            
            if (matchesSearch && matchesClass && matchesMajor && shouldShow) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
                // Remove from selection if hidden
                if (card.classList.contains('selected')) {
                    card.classList.remove('selected');
                    card.querySelector('.fas.fa-circle-check').classList.add('d-none');
                    selectedStudents.delete(card.dataset.studentId);
                }
            }
        });

        document.getElementById('studentCount').textContent = `แสดง ${visibleCount} คน`;
        updateSelectedDisplay();
    }

    searchInput.addEventListener('input', filterStudents);
    classFilter.addEventListener('change', filterStudents);
    majorFilter.addEventListener('change', filterStudents);

    // Update selected display
    function updateSelectedDisplay() {
        const count = selectedStudents.size;
        selectedCount.textContent = count;
        selectedStudentCount.textContent = `(${count} คน)`;
        bulkSelectedCount.textContent = count;
        
        if (count > 0) {
            submitBtn.disabled = false;
            bulkActions.style.display = 'block';
            
            const selectedNames = Array.from(selectedStudents).map(id => {
                const card = document.querySelector(`[data-student-id="${id}"]`);
                return `${card.dataset.studentName} (${card.dataset.studentCode})`;
            });
            
            selectedStudentsList.innerHTML = selectedNames.slice(0, 5).join('<br>') + 
                (selectedNames.length > 5 ? `<br><small>และอีก ${selectedNames.length - 5} คน</small>` : '');
        } else {
            submitBtn.disabled = true;
            bulkActions.style.display = 'none';
            selectedStudentsList.textContent = 'ยังไม่ได้เลือกนักเรียน';
        }
    }

    // Select all visible students
    window.selectAll = function() {
        studentCards.forEach(card => {
            if (card.style.display !== 'none' && card.dataset.alreadyChecked === 'false') {
                if (!card.classList.contains('selected')) {
                    card.classList.add('selected');
                    card.querySelector('.fas.fa-circle-check').classList.remove('d-none');
                    selectedStudents.add(card.dataset.studentId);
                }
            }
        });
        updateSelectedDisplay();
    };

    // Clear selection
    window.clearSelection = function() {
        studentCards.forEach(card => {
            card.classList.remove('selected');
            card.querySelector('.fas.fa-circle-check').classList.add('d-none');
        });
        selectedStudents.clear();
        updateSelectedDisplay();
    };

    // Toggle already checked
    window.toggleAlreadyChecked = function() {
        hideAlreadyChecked = !hideAlreadyChecked;
        hideShowText.textContent = hideAlreadyChecked ? 'แสดง' : 'ซ่อน';
        filterStudents();
    };

    // Bulk check-in
    window.bulkCheckin = function(status) {
        if (selectedStudents.size === 0) return;
        
        if (confirm(`คุณต้องการเช็คชื่อ ${selectedStudents.size} คน ด้วยสถานะ "${status === 'present' ? 'เข้าเรียน' : 'มาสาย'}" หรือไม่?`)) {
            document.getElementById('statusInput').value = status;
            checkinForm.submit();
        }
    };

    // Form submission
    checkinForm.addEventListener('submit', function(e) {
        if (selectedStudents.size === 0) {
            e.preventDefault();
            alert('กรุณาเลือกนักเรียนอย่างน้อย 1 คน');
            return;
        }

        // Add selected student IDs to form
        selectedStudents.forEach(studentId => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'student_ids[]';
            input.value = studentId;
            this.appendChild(input);
        });
    });

    // Initialize
    updateSelectedDisplay();
});
</script>
@endpush