<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $schoolInfo['name'] ?? 'วิทยาลัยเทคนิคลำพูน' }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: {{ $schoolInfo['primary_color'] ?? '#667eea' }};
            --secondary-color: {{ $schoolInfo['secondary_color'] ?? '#764ba2' }};
            --accent-color: {{ $schoolInfo['accent_color'] ?? '#28a745' }};
            --text-color: {{ $schoolInfo['text_color'] ?? '#333333' }};
            --bg-color: {{ $schoolInfo['background_color'] ?? '#f8f9fa' }};
        }

        body {
            font-family: '{{ $schoolInfo['font_family'] ?? 'Sarabun' }}', sans-serif;
            color: var(--text-color);
            background-color: var(--bg-color);
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 120px 0 80px;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .section-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .btn-custom {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            color: white;
            transform: translateY(-2px);
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .stats-card {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
        }

        .announcement-card {
            border-left: 4px solid var(--accent-color);
        }

        .blog-card {
            height: 100%;
        }

        .blog-card img {
            height: 200px;
            object-fit: cover;
        }

        .priority-badge {
            font-size: 0.7rem;
            padding: 4px 8px;
        }

        .footer {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 60px 0 30px;
        }

        .social-links a {
            color: white;
            font-size: 1.5rem;
            margin: 0 10px;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            color: var(--accent-color);
            transform: translateY(-2px);
        }

        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .section-padding {
            padding: 80px 0;
        }

        .text-gradient {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-graduation-cap me-2"></i>
                {{ $schoolInfo['name'] ?? 'วิทยาลัยเทคนิคลำพูน' }}
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">หน้าแรก</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">เกี่ยวกับเรา</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#programs">หลักสูตร</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#announcements">ประกาศ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#blogs">บทความ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">ติดต่อ</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-custom ms-2" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-2"></i>เข้าสู่ระบบ
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="floating-shapes">
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
        </div>
        
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <h1 class="section-title">
                        {{ $welcomeContent['hero_title'] ?? 'ยินดีต้อนรับสู่วิทยาลัยเทคนิคลำพูน' }}
                    </h1>
                    <p class="section-subtitle">
                        {{ $welcomeContent['hero_subtitle'] ?? 'สถาบันการศึกษาที่มุ่งเน้นการผลิตและพัฒนากำลังคนด้านวิชาชีพที่มีคุณภาพ' }}
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#programs" class="btn btn-custom">
                            <i class="fas fa-graduation-cap me-2"></i>ดูหลักสูตร
                        </a>
                        <a href="#contact" class="btn btn-custom">
                            <i class="fas fa-phone me-2"></i>ติดต่อเรา
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <i class="fas fa-university" style="font-size: 15rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="section-padding bg-white">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stats-card">
                        <i class="fas fa-users fa-3x mb-3"></i>
                        <h3 class="fw-bold">{{ number_format($stats['total_students']) }}</h3>
                        <p class="mb-0">นักเรียน</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stats-card">
                        <i class="fas fa-book fa-3x mb-3"></i>
                        <h3 class="fw-bold">{{ number_format($stats['total_subjects']) }}</h3>
                        <p class="mb-0">วิชาเรียน</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stats-card">
                        <i class="fas fa-bullhorn fa-3x mb-3"></i>
                        <h3 class="fw-bold">{{ number_format($stats['total_announcements']) }}</h3>
                        <p class="mb-0">ประกาศ</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stats-card">
                        <i class="fas fa-newspaper fa-3x mb-3"></i>
                        <h3 class="fw-bold">{{ number_format($stats['total_blogs']) }}</h3>
                        <p class="mb-0">บทความ</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="section-padding">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="section-title text-gradient">
                        {{ $welcomeContent['about_title'] ?? 'เกี่ยวกับเรา' }}
                    </h2>
                    <div class="mb-4">
                        {!! $welcomeContent['about_content'] ?? '<p>วิทยาลัยเทคนิคลำพูน เป็นสถาบันการศึกษาระดับอาชีวศึกษา ที่มุ่งเน้นการผลิตและพัฒนากำลังคนด้านวิชาชีพที่มีคุณภาพ</p>' !!}
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>คุณภาพการศึกษา</span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>เทคโนโลยีทันสมัย</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>อาจารย์ผู้เชี่ยวชาญ</span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>โอกาสการทำงาน</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body p-5">
                            <h4 class="text-center mb-4">ข้อมูลโรงเรียน</h4>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <strong>ชื่อ:</strong><br>
                                    {{ $schoolInfo['name'] ?? 'วิทยาลัยเทคนิคลำพูน' }}
                                </div>
                                <div class="col-6 mb-3">
                                    <strong>ที่อยู่:</strong><br>
                                    {{ $schoolInfo['address'] ?? 'ไม่ระบุ' }}
                                </div>
                                <div class="col-6 mb-3">
                                    <strong>เบอร์โทร:</strong><br>
                                    {{ $schoolInfo['phone'] ?? 'ไม่ระบุ' }}
                                </div>
                                <div class="col-6 mb-3">
                                    <strong>อีเมล:</strong><br>
                                    {{ $schoolInfo['email'] ?? 'ไม่ระบุ' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Programs Section -->
    <section id="programs" class="section-padding bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title text-gradient">หลักสูตรที่เปิดสอน</h2>
                <p class="section-subtitle">เลือกหลักสูตรที่เหมาะสมกับความสนใจและเป้าหมายของคุณ</p>
            </div>
            
            <div class="row">
                @for($i = 0; $i < 6; $i++)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body p-4">
                            <div class="text-center mb-3">
                                <i class="fas fa-graduation-cap fa-3x text-gradient"></i>
                            </div>
                            <h5 class="card-title text-center">
                                {{ $welcomeContent["program_{$i}_title"] ?? "หลักสูตรที่ " . ($i + 1) }}
                            </h5>
                            <p class="card-text text-center">
                                {{ $welcomeContent["program_{$i}_description"] ?? "รายละเอียดหลักสูตรที่ " . ($i + 1) }}
                            </p>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
        </div>
    </section>

    <!-- Announcements Section -->
    <section id="announcements" class="section-padding">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title text-gradient">ประกาศล่าสุด</h2>
                <p class="section-subtitle">ติดตามข่าวสารและประกาศสำคัญจากโรงเรียน</p>
            </div>
            
            <div class="row">
                @forelse($announcements as $announcement)
                <div class="col-lg-6 mb-4">
                    <div class="card announcement-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title mb-0">{{ $announcement->title }}</h5>
                                <span class="badge bg-{{ $announcement->priority_color }} priority-badge">
                                    {{ $announcement->priority_text }}
                                </span>
                            </div>
                            <p class="card-text text-muted">
                                {!! Str::limit(strip_tags($announcement->content), 150) !!}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $announcement->published_at ? $announcement->published_at->format('d/m/Y') : 'ไม่ระบุ' }}
                                </small>
                                <a href="{{ route('announcements.public.show', $announcement->id) }}" class="btn btn-sm btn-outline-primary">
                                    อ่านเพิ่มเติม
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center">
                    <i class="fas fa-bullhorn fa-3x text-muted mb-3"></i>
                    <p class="text-muted">ยังไม่มีประกาศ</p>
                </div>
                @endforelse
            </div>
            
            @if($announcements->count() > 0)
            <div class="text-center mt-4">
                <a href="{{ route('announcements.public.index') }}" class="btn btn-custom">
                    <i class="fas fa-list me-2"></i>ดูประกาศทั้งหมด
                </a>
            </div>
            @endif
        </div>
    </section>

    <!-- Featured Blogs Section -->
    @if($featuredBlogs->count() > 0)
    <section class="section-padding bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title text-gradient">บทความแนะนำ</h2>
                <p class="section-subtitle">บทความที่น่าสนใจและเป็นประโยชน์สำหรับนักเรียน</p>
            </div>
            
            <div class="row">
                @foreach($featuredBlogs as $blog)
                <div class="col-lg-4 mb-4">
                    <div class="card blog-card">
                        @if($blog->featured_image)
                        <img src="{{ Storage::url($blog->featured_image) }}" class="card-img-top" alt="{{ $blog->title }}">
                        @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-newspaper fa-3x text-muted"></i>
                        </div>
                        @endif
                        <div class="card-body">
                            <span class="badge bg-{{ $blog->category_color }} mb-2">{{ $blog->category_text }}</span>
                            <h5 class="card-title">{{ $blog->title }}</h5>
                            <p class="card-text text-muted">{{ $blog->excerpt }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-user me-1"></i>{{ $blog->author->name ?? 'ไม่ระบุ' }}
                                </small>
                                <a href="{{ route('blogs.public.show', $blog->slug) }}" class="btn btn-sm btn-outline-primary">
                                    อ่านเพิ่มเติม
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Contact Section -->
    <section id="contact" class="section-padding">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title text-gradient">ติดต่อเรา</h2>
                <p class="section-subtitle">ติดต่อโรงเรียนเพื่อสอบถามข้อมูลเพิ่มเติม</p>
            </div>
            
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-body p-5">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <h5><i class="fas fa-map-marker-alt text-primary me-2"></i>ที่อยู่</h5>
                                    <p class="text-muted">{{ $schoolInfo['address'] ?? 'ไม่ระบุ' }}</p>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <h5><i class="fas fa-phone text-primary me-2"></i>เบอร์โทรศัพท์</h5>
                                    <p class="text-muted">{{ $schoolInfo['phone'] ?? 'ไม่ระบุ' }}</p>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <h5><i class="fas fa-envelope text-primary me-2"></i>อีเมล</h5>
                                    <p class="text-muted">{{ $schoolInfo['email'] ?? 'ไม่ระบุ' }}</p>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <h5><i class="fas fa-globe text-primary me-2"></i>เว็บไซต์</h5>
                                    <p class="text-muted">{{ $schoolInfo['website'] ?? 'ไม่ระบุ' }}</p>
                                </div>
                            </div>
                            
                            <div class="text-center mt-4">
                                <h5 class="mb-3">ติดตามเราได้ที่</h5>
                                <div class="social-links">
                                    @if($schoolInfo['facebook'])
                                    <a href="{{ $schoolInfo['facebook'] }}" target="_blank"><i class="fab fa-facebook"></i></a>
                                    @endif
                                    @if($schoolInfo['youtube'])
                                    <a href="{{ $schoolInfo['youtube'] }}" target="_blank"><i class="fab fa-youtube"></i></a>
                                    @endif
                                    @if($schoolInfo['line'])
                                    <a href="https://line.me/ti/p/{{ $schoolInfo['line'] }}" target="_blank"><i class="fab fa-line"></i></a>
                                    @endif
                                    @if($schoolInfo['instagram'])
                                    <a href="{{ $schoolInfo['instagram'] }}" target="_blank"><i class="fab fa-instagram"></i></a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="mb-3">{{ $schoolInfo['name'] ?? 'วิทยาลัยเทคนิคลำพูน' }}</h5>
                    <p class="mb-3">{{ $schoolInfo['description'] ?? 'สถาบันการศึกษาระดับอาชีวศึกษา' }}</p>
                    <p class="mb-0"><strong>คำขวัญ:</strong> {{ $schoolInfo['motto'] ?? 'ไม่ระบุ' }}</p>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5 class="mb-3">ลิงก์ด่วน</h5>
                    <ul class="list-unstyled">
                        <li><a href="#home" class="text-white-50">หน้าแรก</a></li>
                        <li><a href="#about" class="text-white-50">เกี่ยวกับเรา</a></li>
                        <li><a href="#programs" class="text-white-50">หลักสูตร</a></li>
                        <li><a href="{{ route('announcements.public.index') }}" class="text-white-50">ประกาศ</a></li>
                        <li><a href="{{ route('blogs.public.index') }}" class="text-white-50">บทความ</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5 class="mb-3">ข้อมูลติดต่อ</h5>
                    <p class="mb-2"><i class="fas fa-map-marker-alt me-2"></i>{{ $schoolInfo['address'] ?? 'ไม่ระบุ' }}</p>
                    <p class="mb-2"><i class="fas fa-phone me-2"></i>{{ $schoolInfo['phone'] ?? 'ไม่ระบุ' }}</p>
                    <p class="mb-2"><i class="fas fa-envelope me-2"></i>{{ $schoolInfo['email'] ?? 'ไม่ระบุ' }}</p>
                    <p class="mb-0"><i class="fas fa-clock me-2"></i>{{ $schoolInfo['office_hours'] ?? 'ไม่ระบุ' }}</p>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; {{ date('Y') }} {{ $schoolInfo['name'] ?? 'วิทยาลัยเทคนิคลำพูน' }}. สงวนลิขสิทธิ์.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">พัฒนาโดย <strong>ทีมพัฒนาโรงเรียน</strong></p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Navbar background change on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(255, 255, 255, 0.98) !important';
            } else {
                navbar.style.background = 'rgba(255, 255, 255, 0.95) !important';
            }
        });
    </script>
</body>
</html>
