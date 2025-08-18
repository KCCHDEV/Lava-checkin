<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ระบบเช็คชื่อนักเรียน</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        .btn-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            color: white;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="#">
                <i class="fas fa-clipboard-check me-2"></i>
                ระบบเช็คชื่อนักเรียน
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt me-1"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('students.index') }}">
                                <i class="fas fa-users me-1"></i>
                                จัดการนักเรียน
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>
                                เข้าสู่ระบบ
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i>
                                สมัครสมาชิก
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold text-white mb-4">
                        ระบบเช็คชื่อนักเรียน
                        <br>
                        <span class="text-warning">อัจฉริยะ</span>
                    </h1>
                    <p class="lead text-white-50 mb-4">
                        จัดการการเช็คชื่อนักเรียนอย่างมีประสิทธิภาพ พร้อมระบบสิทธิ์การใช้งานที่ครบครัน
                        สำหรับผู้ดูแลระบบและครูผู้สอน
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-custom btn-lg">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                เข้าสู่ Dashboard
                            </a>
                            <a href="{{ route('students.index') }}" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-users me-2"></i>
                                จัดการนักเรียน
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-custom btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                เข้าสู่ระบบ
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-user-plus me-2"></i>
                                สมัครสมาชิก
                            </a>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <i class="fas fa-clipboard-check" style="font-size: 15rem; color: rgba(255,255,255,0.2);"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="display-5 fw-bold mb-3">ฟีเจอร์หลัก</h2>
                    <p class="lead text-muted">ระบบครบครันสำหรับการจัดการการเช็คชื่อนักเรียน</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card feature-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="fas fa-user-plus text-primary" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="card-title">เพิ่มข้อมูลนักเรียน</h5>
                            <p class="card-text text-muted">
                                เพิ่มข้อมูลนักเรียนพร้อมชื่อ-นามสกุล เบอร์โทร ที่อยู่ และสถานะการมาเรียน
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card feature-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="fas fa-edit text-success" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="card-title">แก้ไขข้อมูล</h5>
                            <p class="card-text text-muted">
                                แก้ไขข้อมูลนักเรียนและอัปเดตสถานะการมาเรียนได้อย่างง่ายดาย
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card feature-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="fas fa-trash-alt text-danger" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="card-title">ลบข้อมูล</h5>
                            <p class="card-text text-muted">
                                ลบข้อมูลนักเรียนที่ไม่ต้องการ (เฉพาะผู้ดูแลระบบ)
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card feature-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="fas fa-eye text-info" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="card-title">ดูข้อมูล</h5>
                            <p class="card-text text-muted">
                                ดูข้อมูลนักเรียนและสถิติการเช็คชื่อแบบละเอียด
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p class="mb-0">
                <i class="fas fa-clipboard-check me-2"></i>
                ระบบเช็คชื่อนักเรียน &copy; {{ date('Y') }} - พัฒนาด้วย Laravel
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>