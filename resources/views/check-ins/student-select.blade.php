<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เลือกชื่อเพื่อเช็คชื่อ - ระบบจัดการโรงเรียน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Sarabun', sans-serif;
            min-height: 100vh;
        }

        .container {
            padding-top: 2rem;
            padding-bottom: 2rem;
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .card-header {
            background: white;
            border-bottom: none;
            padding: 1.5rem;
            text-align: center;
        }

        .school-logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 2rem;
        }

        .student-search {
            margin-bottom: 1rem;
        }

        .student-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .student-item {
            padding: 1rem;
            border: 2px solid #e9ecef;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }

        .student-item:hover {
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .student-item.selected {
            border-color: #667eea;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .student-name {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .student-info {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .btn-checkin {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            color: white;
            width: 100%;
            margin-top: 1rem;
            transition: transform 0.2s ease;
        }

        .btn-checkin:hover {
            transform: translateY(-2px);
            color: white;
        }

        .btn-checkin:disabled {
            background: #6c757d;
            transform: none;
        }

        .alert {
            border: none;
            border-radius: 0.5rem;
        }

        .loading {
            display: none;
            text-align: center;
            padding: 2rem;
        }

        .success-animation {
            animation: success 0.6s ease;
        }

        @keyframes success {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .search-results-info {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            text-align: center;
        }

        @media (max-width: 768px) {
            .container {
                padding-top: 1rem;
            }
            
            .student-item {
                padding: 0.75rem;
            }
            
            .school-logo {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <div class="school-logo">
                            <i class="fas fa-school"></i>
                        </div>
                        <h4 class="mb-2">ระบบเช็คชื่อโรงเรียน</h4>
                        <p class="text-muted mb-0">เลือกชื่อของคุณเพื่อเช็คชื่อ</p>
                    </div>
                    <div class="card-body">
                        <!-- Search Box -->
                        <div class="student-search">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" 
                                       class="form-control" 
                                       id="searchInput" 
                                       placeholder="ค้นหาชื่อหรือรหัสนักเรียน...">
                            </div>
                        </div>

                        <!-- Search Results Info -->
                        <div class="search-results-info" id="searchInfo">
                            แสดง {{ count($students) }} คน
                        </div>

                        <!-- Student List -->
                        <div class="student-list" id="studentList">
                            @forelse($students as $student)
                                <div class="student-item" 
                                     data-student-id="{{ $student->id }}"
                                     data-student-name="{{ $student->name }}"
                                     data-student-code="{{ $student->student_code }}"
                                     data-search-text="{{ strtolower($student->name . ' ' . $student->student_code . ' ' . ($student->class ?? '') . ' ' . ($student->major ?? '')) }}">
                                    <div class="student-name">{{ $student->name }}</div>
                                    <div class="student-info">
                                        รหัส: {{ $student->student_code }}
                                        @if($student->class || $student->major)
                                            | {{ $student->class }} {{ $student->major }}
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-users fa-2x mb-2"></i>
                                    <p>ไม่พบข้อมูลนักเรียน</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Check-in Form -->
                        <form id="checkinForm">
                            @csrf
                            <input type="hidden" name="check_in_code" value="{{ $code }}">
                            <input type="hidden" name="student_id" id="selectedStudentId">
                            
                            <button type="submit" class="btn-checkin" id="checkinBtn" disabled>
                                <i class="fas fa-check-circle me-2"></i>
                                เลือกชื่อเพื่อเช็คชื่อ
                            </button>
                        </form>

                        <!-- Loading State -->
                        <div class="loading" id="loadingState">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">กำลังดำเนินการ...</span>
                            </div>
                            <p class="mt-2 mb-0">กำลังบันทึกการเช็คชื่อ...</p>
                        </div>

                        <!-- Result Messages -->
                        <div id="alertContainer"></div>
                    </div>
                </div>

                <!-- QR Code Info -->
                <div class="text-center mt-3">
                    <small class="text-white">
                        <i class="fas fa-qrcode me-1"></i>
                        รหัส QR: {{ $code }}
                    </small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const studentItems = document.querySelectorAll('.student-item');
            const checkinBtn = document.getElementById('checkinBtn');
            const selectedStudentIdInput = document.getElementById('selectedStudentId');
            const searchInput = document.getElementById('searchInput');
            const studentList = document.getElementById('studentList');
            const searchInfo = document.getElementById('searchInfo');
            const checkinForm = document.getElementById('checkinForm');
            const loadingState = document.getElementById('loadingState');
            const alertContainer = document.getElementById('alertContainer');

            let selectedStudent = null;
            let allStudents = Array.from(studentItems);

            // Student selection
            studentItems.forEach(item => {
                item.addEventListener('click', function() {
                    // Remove previous selection
                    studentItems.forEach(i => i.classList.remove('selected'));
                    
                    // Select current item
                    this.classList.add('selected');
                    
                    // Update form
                    selectedStudent = {
                        id: this.dataset.studentId,
                        name: this.dataset.studentName,
                        code: this.dataset.studentCode
                    };
                    
                    selectedStudentIdInput.value = selectedStudent.id;
                    checkinBtn.disabled = false;
                    checkinBtn.innerHTML = `<i class="fas fa-check-circle me-2"></i>เช็คชื่อ: ${selectedStudent.name}`;
                });
            });

            // Search functionality
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                let visibleCount = 0;

                allStudents.forEach(item => {
                    const searchText = item.dataset.searchText;
                    const isVisible = searchText.includes(searchTerm);
                    
                    if (isVisible) {
                        item.style.display = 'block';
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                        // Remove selection if hidden
                        if (item.classList.contains('selected')) {
                            item.classList.remove('selected');
                            selectedStudent = null;
                            selectedStudentIdInput.value = '';
                            checkinBtn.disabled = true;
                            checkinBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i>เลือกชื่อเพื่อเช็คชื่อ';
                        }
                    }
                });

                // Update search info
                if (searchTerm) {
                    searchInfo.textContent = `พบ ${visibleCount} คน จาก "${searchTerm}"`;
                } else {
                    searchInfo.textContent = `แสดง ${visibleCount} คน`;
                }

                // Show no results message
                if (visibleCount === 0 && searchTerm) {
                    if (!document.getElementById('noResults')) {
                        const noResults = document.createElement('div');
                        noResults.id = 'noResults';
                        noResults.className = 'text-center text-muted py-4';
                        noResults.innerHTML = `
                            <i class="fas fa-search fa-2x mb-2"></i>
                            <p>ไม่พบ "${searchTerm}"</p>
                            <small>ลองค้นหาด้วยชื่อหรือรหัสนักเรียน</small>
                        `;
                        studentList.appendChild(noResults);
                    }
                } else {
                    const noResults = document.getElementById('noResults');
                    if (noResults) {
                        noResults.remove();
                    }
                }
            });

            // Form submission
            checkinForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (!selectedStudent) {
                    showAlert('กรุณาเลือกชื่อของคุณก่อน', 'warning');
                    return;
                }

                // Show loading
                checkinBtn.style.display = 'none';
                loadingState.style.display = 'block';

                // Submit form
                const formData = new FormData(this);
                
                fetch('{{ route("check-ins.scan.submit") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    loadingState.style.display = 'none';
                    
                    if (data.success) {
                        showSuccessMessage(data.message, data.data);
                    } else {
                        checkinBtn.style.display = 'block';
                        showAlert(data.message, 'danger');
                    }
                })
                .catch(error => {
                    loadingState.style.display = 'none';
                    checkinBtn.style.display = 'block';
                    showAlert('เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง', 'danger');
                    console.error('Error:', error);
                });
            });

            // Show alert message
            function showAlert(message, type) {
                const alert = document.createElement('div');
                alert.className = `alert alert-${type} mt-3`;
                alert.innerHTML = `
                    <i class="fas fa-${type === 'danger' ? 'exclamation-triangle' : type === 'warning' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
                    ${message}
                `;
                
                alertContainer.innerHTML = '';
                alertContainer.appendChild(alert);
                
                // Auto hide after 5 seconds
                setTimeout(() => {
                    alert.remove();
                }, 5000);
            }

            // Show success message
            function showSuccessMessage(message, data) {
                const successCard = document.createElement('div');
                successCard.className = 'alert alert-success success-animation mt-3';
                successCard.innerHTML = `
                    <div class="text-center">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h5 class="text-success">เช็คชื่อสำเร็จ!</h5>
                        <p class="mb-2">${message}</p>
                        ${data ? `
                        <div class="mt-3 p-3 bg-light rounded">
                            <strong>รายละเอียด:</strong><br>
                            ชื่อ: ${data.student_name}<br>
                            รหัส: ${data.student_code}<br>
                            เวลา: ${data.check_in_time}<br>
                            สถานะ: <span class="badge bg-${data.status_color}">${data.status}</span>
                        </div>
                        ` : ''}
                        <button type="button" class="btn btn-primary mt-3" onclick="window.close()">
                            ปิดหน้าต่าง
                        </button>
                    </div>
                `;
                
                alertContainer.innerHTML = '';
                alertContainer.appendChild(successCard);
                
                // Hide form
                checkinForm.style.display = 'none';
                
                // Play success sound
                playSuccessSound();
                
                // Auto close after 10 seconds
                setTimeout(() => {
                    window.close();
                }, 10000);
            }

            // Play success sound
            function playSuccessSound() {
                const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmQgDC2U4vHPfGw=');
                audio.play().catch(e => console.log('Audio play failed:', e));
            }

            // Focus search input
            searchInput.focus();
        });
    </script>
</body>
</html>