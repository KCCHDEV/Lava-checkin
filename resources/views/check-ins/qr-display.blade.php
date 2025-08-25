@extends('layouts.app')

@section('title', 'QR Code สำหรับเช็คชื่อ - ระบบจัดการโรงเรียน')

@section('page-title', 'QR Code สำหรับเช็คชื่อ')

@push('styles')
<style>
.qr-container {
    background: white;
    border-radius: 1rem;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    text-align: center;
    margin-bottom: 2rem;
}

.qr-code-wrapper {
    background: white;
    padding: 2rem;
    border-radius: 0.5rem;
    display: inline-block;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    margin: 1rem 0;
}

.timer-display {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
    color: white;
    border-radius: 0.5rem;
    padding: 1rem 2rem;
    font-size: 1.5rem;
    font-weight: bold;
    margin: 1rem 0;
    display: inline-block;
    min-width: 200px;
}

.info-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 0.5rem;
    padding: 1.5rem;
    margin-bottom: 1rem;
}

.stats-live {
    background: #f8f9fa;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 1rem;
}

.student-list {
    max-height: 400px;
    overflow-y: auto;
    background: white;
    border-radius: 0.5rem;
    border: 1px solid #dee2e6;
}

.student-item {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #f1f1f1;
    display: flex;
    justify-content: between;
    align-items: center;
}

.student-item:last-child {
    border-bottom: none;
}

.student-item.new-checkin {
    background: #d4edda;
    animation: slideIn 0.5s ease;
}

@keyframes slideIn {
    from {
        transform: translateX(-100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.expired-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 1rem;
    z-index: 1000;
}

.btn-fullscreen {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 999;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    border: none;
    border-radius: 50%;
    width: 50px;
    height: 50px;
}

.school-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 2rem;
    text-align: center;
}

.class-info {
    background: #e3f2fd;
    border-left: 4px solid #2196f3;
    padding: 1rem;
    margin-bottom: 1rem;
    border-radius: 0 0.5rem 0.5rem 0;
}

@media print {
    .no-print {
        display: none !important;
    }
    
    .qr-container {
        box-shadow: none;
        border: 2px solid #000;
    }
}

@media (max-width: 768px) {
    .qr-code-wrapper {
        padding: 1rem;
    }
    
    .timer-display {
        font-size: 1.2rem;
        padding: 0.75rem 1.5rem;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- School Header -->
    <div class="school-header">
        <h2><i class="fas fa-school me-2"></i>{{ config('app.name', 'ระบบจัดการโรงเรียน') }}</h2>
        <p class="mb-0">ระบบเช็คชื่อนักเรียน QR Code</p>
    </div>

    <div class="row">
        <!-- QR Code Section -->
        <div class="col-lg-8">
            <div class="qr-container position-relative" id="qrContainer">
                <h3 class="mb-3">
                    <i class="fas fa-qrcode me-2"></i>QR Code สำหรับเช็คชื่อ
                </h3>
                
                <!-- Class Information -->
                <div class="class-info">
                    <div class="row">
                        <div class="col-md-6">
                            <strong><i class="fas fa-calendar me-1"></i>วันที่:</strong> 
                            {{ now()->format('d/m/Y') }}
                        </div>
                        <div class="col-md-6">
                            <strong><i class="fas fa-clock me-1"></i>เวลา:</strong> 
                            <span id="currentTime">{{ now()->format('H:i:s') }}</span>
                        </div>
                        @if(session('qr_location'))
                        <div class="col-12 mt-2">
                            <strong><i class="fas fa-map-marker-alt me-1"></i>สถานที่:</strong> 
                            {{ session('qr_location') }}
                        </div>
                        @endif
                    </div>
                </div>

                <!-- QR Code Display -->
                <div class="qr-code-wrapper pulse">
                    <div id="qrcode"></div>
                </div>

                <!-- Timer -->
                <div class="timer-display" id="timerDisplay">
                    เหลือเวลา: <span id="countdown">{{ gmdate('i:s', $timeLimit) }}</span>
                </div>

                <!-- QR Code Info -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            รหัส: {{ $checkInCode }}
                        </small>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">
                            <i class="fas fa-link me-1"></i>
                            {{ $qrUrl }}
                        </small>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="alert alert-info mt-3">
                    <h6><i class="fas fa-mobile-alt me-2"></i>วิธีการเช็คชื่อ:</h6>
                    <ol class="mb-0 text-start">
                        <li>นักเรียนใช้กล้องมือถือสแกน QR Code</li>
                        <li>เลือกชื่อของตนเองจากรายการ</li>
                        <li>กดปุ่ม "เช็คชื่อ" เพื่อยืนยัน</li>
                        <li>ตรวจสอบสถานะการเช็คชื่อ</li>
                    </ol>
                </div>

                <!-- Expired Overlay (hidden by default) -->
                <div class="expired-overlay d-none" id="expiredOverlay">
                    <div class="text-center">
                        <i class="fas fa-times-circle fa-3x mb-3"></i>
                        <h3>QR Code หมดอายุแล้ว</h3>
                        <p>กรุณาสร้าง QR Code ใหม่</p>
                        <a href="{{ route('check-ins.create') }}" class="btn btn-light">
                            สร้าง QR Code ใหม่
                        </a>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center no-print">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-primary" onclick="printQR()">
                        <i class="fas fa-print me-1"></i>พิมพ์
                    </button>
                    <button type="button" class="btn btn-success" onclick="toggleFullscreen()">
                        <i class="fas fa-expand me-1"></i>เต็มจอ
                    </button>
                    <a href="{{ route('check-ins.create') }}" class="btn btn-warning">
                        <i class="fas fa-plus me-1"></i>สร้างใหม่
                    </a>
                    <a href="{{ route('check-ins.index') }}" class="btn btn-secondary">
                        <i class="fas fa-list me-1"></i>ดูรายการ
                    </a>
                </div>
            </div>
        </div>

        <!-- Live Statistics -->
        <div class="col-lg-4">
            <!-- Statistics Card -->
            <div class="info-card">
                <h5><i class="fas fa-chart-line me-2"></i>สถิติการเช็คชื่อวันนี้</h5>
                <div class="row text-center">
                    <div class="col-6">
                        <h3 class="mb-0" id="totalCheckins">0</h3>
                        <small>เช็คชื่อแล้ว</small>
                    </div>
                    <div class="col-6">
                        <h3 class="mb-0" id="lateCheckins">0</h3>
                        <small>มาสาย</small>
                    </div>
                </div>
            </div>

            <!-- Recent Check-ins -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-users me-2"></i>การเช็คชื่อล่าสุด
                        <span class="badge bg-primary ms-2" id="recentCount">0</span>
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="student-list" id="recentCheckins">
                        <div class="text-center p-3 text-muted">
                            <i class="fas fa-clock me-1"></i>
                            รอการเช็คชื่อ...
                        </div>
                    </div>
                </div>
            </div>

            <!-- QR Code Status -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>สถานะ QR Code
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>สถานะ:</span>
                        <span class="badge bg-success" id="qrStatus">ใช้งานได้</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>เวลาที่เหลือ:</span>
                        <span id="timeRemaining">{{ gmdate('i:s', $timeLimit) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>ใช้งานแล้ว:</span>
                        <span id="usageCount">0 ครั้ง</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Fullscreen Button -->
<button type="button" class="btn-fullscreen d-none" id="fullscreenBtn" onclick="toggleFullscreen()">
    <i class="fas fa-compress"></i>
</button>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Generate QR Code
    const qrUrl = @json($qrUrl);
    const timeLimit = @json($timeLimit);
    let remainingTime = timeLimit;
    let checkInCount = 0;
    
    // Generate QR Code
    QRCode.toCanvas(document.getElementById('qrcode'), qrUrl, {
        width: 300,
        height: 300,
        margin: 2,
        color: {
            dark: '#000000',
            light: '#FFFFFF'
        }
    });

    // Update current time
    function updateCurrentTime() {
        const now = new Date();
        document.getElementById('currentTime').textContent = now.toLocaleTimeString('th-TH');
    }
    setInterval(updateCurrentTime, 1000);

    // Countdown timer
    function updateCountdown() {
        if (remainingTime <= 0) {
            document.getElementById('expiredOverlay').classList.remove('d-none');
            document.getElementById('qrStatus').textContent = 'หมดอายุ';
            document.getElementById('qrStatus').className = 'badge bg-danger';
            return;
        }

        const minutes = Math.floor(remainingTime / 60);
        const seconds = remainingTime % 60;
        const timeString = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        
        document.getElementById('countdown').textContent = timeString;
        document.getElementById('timeRemaining').textContent = timeString;
        
        remainingTime--;
    }

    // Start countdown
    updateCountdown();
    const countdownInterval = setInterval(updateCountdown, 1000);

    // Fetch recent check-ins
    function fetchRecentCheckins() {
        fetch('/api/recent-checkins')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateRecentCheckins(data.checkins);
                    updateStats(data.stats);
                }
            })
            .catch(error => console.log('Error fetching data:', error));
    }

    // Update recent check-ins display
    function updateRecentCheckins(checkins) {
        const container = document.getElementById('recentCheckins');
        const currentCount = document.querySelectorAll('.student-item').length;
        
        if (checkins.length === 0) {
            container.innerHTML = '<div class="text-center p-3 text-muted"><i class="fas fa-clock me-1"></i>รอการเช็คชื่อ...</div>';
            return;
        }

        let html = '';
        checkins.forEach((checkin, index) => {
            const isNew = index < (checkins.length - currentCount);
            html += `
                <div class="student-item ${isNew ? 'new-checkin' : ''}">
                    <div class="flex-grow-1">
                        <div class="fw-bold">${checkin.student_name}</div>
                        <small class="text-muted">${checkin.student_code} - ${checkin.check_in_time}</small>
                    </div>
                    <span class="badge bg-${checkin.status_color}">${checkin.status_text}</span>
                </div>
            `;
        });

        container.innerHTML = html;
        document.getElementById('recentCount').textContent = checkins.length;
        document.getElementById('usageCount').textContent = `${checkins.length} ครั้ง`;
    }

    // Update statistics
    function updateStats(stats) {
        document.getElementById('totalCheckins').textContent = stats.total || 0;
        document.getElementById('lateCheckins').textContent = stats.late || 0;
    }

    // Fetch data every 5 seconds
    setInterval(fetchRecentCheckins, 5000);
    fetchRecentCheckins(); // Initial fetch
});

// Print QR Code
function printQR() {
    window.print();
}

// Toggle fullscreen
function toggleFullscreen() {
    const container = document.getElementById('qrContainer');
    const btn = document.getElementById('fullscreenBtn');
    
    if (!document.fullscreenElement) {
        container.requestFullscreen().then(() => {
            btn.classList.remove('d-none');
            btn.innerHTML = '<i class="fas fa-compress"></i>';
        });
    } else {
        document.exitFullscreen().then(() => {
            btn.classList.add('d-none');
        });
    }
}

// Sound notification for new check-ins
function playNotificationSound() {
    const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmQgDC2U4vHPfGw=');
    audio.play().catch(e => console.log('Audio play failed:', e));
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.key === 'F11') {
        e.preventDefault();
        toggleFullscreen();
    } else if (e.ctrlKey && e.key === 'p') {
        e.preventDefault();
        printQR();
    }
});
</script>
@endpush