@extends('layouts.app')

@section('title', 'สร้าง QR Code สำหรับเช็คชื่อ - ระบบเช็คชื่อการมาเรียน')

@section('page-title', 'สร้าง QR Code สำหรับเช็คชื่อ')

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

.qr-preview {
    background: #f8f9fa;
    border-radius: 0.5rem;
    padding: 2rem;
    text-align: center;
    min-height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.time-limit-info {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 1rem;
}

.quick-settings {
    background: #f8f9fa;
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
}

.quick-time-btn:hover, .quick-time-btn.active {
    background: #667eea;
    color: white;
}

.live-preview {
    position: sticky;
    top: 20px;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-qrcode me-2"></i>ตั้งค่า QR Code
                    </h5>
                </div>
                <div class="card-body">
                    <form id="qrForm" method="POST" action="{{ route('check-ins.generate-qr') }}">
                        @csrf
                        
                        <!-- Time Limit Info -->
                        <div class="time-limit-info">
                            <h6 class="mb-2">
                                <i class="fas fa-clock me-2"></i>เวลาที่ใช้งานได้ของ QR Code
                            </h6>
                            <p class="mb-0">
                                QR Code จะมีอายุการใช้งานตามที่กำหนด หลังจากหมดเวลาจะไม่สามารถใช้งานได้
                            </p>
                        </div>

                        <!-- Quick Time Settings -->
                        <div class="quick-settings">
                            <label class="form-label">เลือกเวลาที่ใช้งานได้ (นาที)</label>
                            <div class="mb-3">
                                <button type="button" class="quick-time-btn" data-time="300">5 นาที</button>
                                <button type="button" class="quick-time-btn active" data-time="600">10 นาที</button>
                                <button type="button" class="quick-time-btn" data-time="900">15 นาที</button>
                                <button type="button" class="quick-time-btn" data-time="1800">30 นาที</button>
                                <button type="button" class="quick-time-btn" data-time="3600">1 ชั่วโมง</button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="time_limit" class="form-label">เวลาที่ใช้งานได้ (วินาที)</label>
                                    <input type="number" class="form-control" id="time_limit" name="time_limit" 
                                           value="600" min="60" max="3600" required>
                                    <small class="text-muted">ระหว่าง 60 วินาที (1 นาที) ถึง 3600 วินาที (1 ชั่วโมง)</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="location" class="form-label">สถานที่</label>
                                    <input type="text" class="form-control" id="location" name="location" 
                                           placeholder="เช่น ห้องเรียน 101, อาคาร A" maxlength="255">
                                    <small class="text-muted">ระบุสถานที่ที่ใช้ในการเช็คชื่อ (ไม่บังคับ)</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="note" class="form-label">หมายเหตุ</label>
                            <textarea class="form-control" id="note" name="note" rows="3" 
                                      placeholder="หมายเหตุเพิ่มเติม (ไม่บังคับ)" maxlength="500"></textarea>
                            <small class="text-muted">หมายเหตุจะบันทึกในข้อมูลการเช็คชื่อ</small>
                        </div>

                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-qrcode me-2"></i>สร้าง QR Code
                            </button>
                            <a href="{{ route('check-ins.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>กลับ
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Instructions Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>วิธีการใช้งาน
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-user-teacher text-primary me-2"></i>สำหรับครู/ผู้ดูแล</h6>
                            <ol>
                                <li>ตั้งค่าเวลาที่ใช้งานได้และสถานที่</li>
                                <li>กดปุ่ม "สร้าง QR Code"</li>
                                <li>แสดง QR Code ให้นักเรียนสแกน</li>
                                <li>ตรวจสอบรายการเช็คชื่อแบบเรียลไทม์</li>
                            </ol>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-user-graduate text-success me-2"></i>สำหรับนักเรียน</h6>
                            <ol>
                                <li>ใช้กล้องถ่ายรูปสแกน QR Code</li>
                                <li>เลือกชื่อของตนเองจากรายการ</li>
                                <li>กดปุ่ม "เช็คชื่อ"</li>
                                <li>ได้รับการยืนยันการเช็คชื่อ</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="live-preview">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-eye me-2"></i>ตัวอย่าง
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="qr-preview">
                            <div class="text-center">
                                <i class="fas fa-qrcode fa-4x text-muted mb-3"></i>
                                <h6 class="text-muted">QR Code จะแสดงที่นี่</h6>
                                <p class="text-muted small">หลังจากกดปุ่ม "สร้าง QR Code"</p>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">เวลาที่ใช้งานได้:</small>
                                <strong id="timeDisplay">10 นาที</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <small class="text-muted">สถานที่:</small>
                                <span id="locationDisplay" class="text-muted">-</span>
                            </div>
                        </div>
                        
                        <div class="alert alert-info mt-3">
                            <small>
                                <i class="fas fa-lightbulb me-1"></i>
                                แนะนำให้ใช้เวลา 10-15 นาที สำหรับการเช็คชื่อทั่วไป
                            </small>
                        </div>
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
    const timeLimitInput = document.getElementById('time_limit');
    const locationInput = document.getElementById('location');
    const timeDisplay = document.getElementById('timeDisplay');
    const locationDisplay = document.getElementById('locationDisplay');
    const quickTimeBtns = document.querySelectorAll('.quick-time-btn');

    // Update time display
    function updateTimeDisplay() {
        const seconds = parseInt(timeLimitInput.value);
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = seconds % 60;
        
        if (minutes > 0) {
            timeDisplay.textContent = remainingSeconds > 0 ? 
                `${minutes} นาที ${remainingSeconds} วินาที` : 
                `${minutes} นาที`;
        } else {
            timeDisplay.textContent = `${seconds} วินาที`;
        }
    }

    // Update location display
    function updateLocationDisplay() {
        locationDisplay.textContent = locationInput.value || '-';
    }

    // Quick time buttons
    quickTimeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const time = this.getAttribute('data-time');
            timeLimitInput.value = time;
            
            // Update active state
            quickTimeBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            updateTimeDisplay();
        });
    });

    // Event listeners
    timeLimitInput.addEventListener('input', updateTimeDisplay);
    locationInput.addEventListener('input', updateLocationDisplay);

    // Initial update
    updateTimeDisplay();
    updateLocationDisplay();

    // Form validation
    document.getElementById('qrForm').addEventListener('submit', function(e) {
        const timeLimit = parseInt(timeLimitInput.value);
        if (timeLimit < 60 || timeLimit > 3600) {
            e.preventDefault();
            alert('เวลาที่ใช้งานได้ต้องอยู่ระหว่าง 60 ถึง 3600 วินาที');
            timeLimitInput.focus();
        }
    });
});
</script>
@endpush