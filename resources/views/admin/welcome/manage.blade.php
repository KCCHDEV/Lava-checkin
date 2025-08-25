@extends('layouts.app')

@section('title', 'จัดการเว็บไซต์ - วิทยาลัยเทคนิคลำพูน')

@section('page-title', 'จัดการเว็บไซต์')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i>จัดการเนื้อหาเว็บไซต์
                </h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="row">
                    <!-- Hero Section -->
                    <div class="col-lg-6 mb-4">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-star me-2"></i>ส่วน Hero
                                </h6>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('admin.welcome.update') }}">
                                    @csrf
                                    <input type="hidden" name="section" value="hero_title">
                                    <input type="hidden" name="type" value="text">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">หัวข้อหลัก</label>
                                        <input type="text" class="form-control" name="content" 
                                               value="{{ $contents->where('section', 'hero_title')->first()->content ?? 'ระบบเช็คชื่อการมาเรียน' }}" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-save me-1"></i>บันทึก
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-4">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-star me-2"></i>คำอธิบาย Hero
                                </h6>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('admin.welcome.update') }}">
                                    @csrf
                                    <input type="hidden" name="section" value="hero_subtitle">
                                    <input type="hidden" name="type" value="text">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">คำอธิบาย</label>
                                        <textarea class="form-control" name="content" rows="3" required>{{ $contents->where('section', 'hero_subtitle')->first()->content ?? 'ระบบจัดการการเช็คชื่อนักเรียนที่ทันสมัย ใช้งานง่าย และมีประสิทธิภาพสูง พร้อมรายงานสถิติที่ครบถ้วน' }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-save me-1"></i>บันทึก
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Banner Image -->
                    <div class="col-lg-6 mb-4">
                        <div class="card border-success">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-image me-2"></i>รูปภาพ Banner
                                </h6>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('admin.welcome.update') }}">
                                    @csrf
                                    <input type="hidden" name="section" value="banner_image">
                                    <input type="hidden" name="type" value="image">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">URL รูปภาพ</label>
                                        <input type="url" class="form-control" name="content" 
                                               value="{{ $contents->where('section', 'banner_image')->first()->content ?? 'https://images.unsplash.com/photo-1523050854058-8df90110c9e1?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80' }}" required>
                                        <small class="text-muted">ใส่ URL ของรูปภาพที่ต้องการใช้เป็น background</small>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fas fa-save me-1"></i>บันทึก
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- School Info -->
                    <div class="col-lg-6 mb-4">
                        <div class="card border-info">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-school me-2"></i>ข้อมูลโรงเรียน
                                </h6>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('admin.welcome.update') }}">
                                    @csrf
                                    <input type="hidden" name="section" value="school_name">
                                    <input type="hidden" name="type" value="text">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">ชื่อโรงเรียน</label>
                                        <input type="text" class="form-control" name="content" 
                                               value="{{ $contents->where('section', 'school_name')->first()->content ?? 'วิทยาลัยเทคนิคลำพูน' }}" required>
                                    </div>
                                    <button type="submit" class="btn btn-info btn-sm">
                                        <i class="fas fa-save me-1"></i>บันทึก
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div class="col-lg-6 mb-4">
                        <div class="card border-warning">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0">
                                    <i class="fas fa-phone me-2"></i>ข้อมูลติดต่อ
                                </h6>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('admin.welcome.update') }}">
                                    @csrf
                                    <input type="hidden" name="section" value="school_phone">
                                    <input type="hidden" name="type" value="text">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">เบอร์โทรศัพท์</label>
                                        <input type="text" class="form-control" name="content" 
                                               value="{{ $contents->where('section', 'school_phone')->first()->content ?? '053-511234' }}" required>
                                    </div>
                                    <button type="submit" class="btn btn-warning btn-sm">
                                        <i class="fas fa-save me-1"></i>บันทึก
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-4">
                        <div class="card border-warning">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0">
                                    <i class="fas fa-envelope me-2"></i>อีเมล
                                </h6>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('admin.welcome.update') }}">
                                    @csrf
                                    <input type="hidden" name="section" value="school_email">
                                    <input type="hidden" name="type" value="text">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">อีเมล</label>
                                        <input type="email" class="form-control" name="content" 
                                               value="{{ $contents->where('section', 'school_email')->first()->content ?? 'info@lamphuntech.ac.th' }}" required>
                                    </div>
                                    <button type="submit" class="btn btn-warning btn-sm">
                                        <i class="fas fa-save me-1"></i>บันทึก
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Features Section -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h5 class="mb-3">
                            <i class="fas fa-cogs me-2"></i>จัดการฟีเจอร์
                        </h5>
                    </div>
                    
                    @for($i = 1; $i <= 3; $i++)
                    <div class="col-lg-4 mb-4">
                        <div class="card border-secondary">
                            <div class="card-header bg-secondary text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-cog me-2"></i>ฟีเจอร์ {{ $i }}
                                </h6>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('admin.welcome.update') }}">
                                    @csrf
                                    <input type="hidden" name="section" value="feature_{{ $i }}_title">
                                    <input type="hidden" name="type" value="text">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">หัวข้อฟีเจอร์ {{ $i }}</label>
                                        <input type="text" class="form-control" name="content" 
                                               value="{{ $contents->where('section', 'feature_' . $i . '_title')->first()->content ?? 'ฟีเจอร์ ' . $i }}" required>
                                    </div>
                                    <button type="submit" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-save me-1"></i>บันทึก
                                    </button>
                                </form>
                                
                                <hr>
                                
                                <form method="POST" action="{{ route('admin.welcome.update') }}">
                                    @csrf
                                    <input type="hidden" name="section" value="feature_{{ $i }}_description">
                                    <input type="hidden" name="type" value="text">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">คำอธิบายฟีเจอร์ {{ $i }}</label>
                                        <textarea class="form-control" name="content" rows="3" required>{{ $contents->where('section', 'feature_' . $i . '_description')->first()->content ?? 'คำอธิบายฟีเจอร์ ' . $i }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-save me-1"></i>บันทึก
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>

                <!-- Programs Section -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h5 class="mb-3">
                            <i class="fas fa-graduation-cap me-2"></i>จัดการหลักสูตร
                        </h5>
                    </div>
                    
                    @php
                        $programs = [
                            ['id' => 1, 'name' => 'ช่างยนต์', 'icon' => 'fas fa-cogs'],
                            ['id' => 2, 'name' => 'ช่างไฟฟ้า', 'icon' => 'fas fa-bolt'],
                            ['id' => 3, 'name' => 'คอมพิวเตอร์', 'icon' => 'fas fa-laptop-code'],
                            ['id' => 4, 'name' => 'ช่างกล', 'icon' => 'fas fa-tools'],
                            ['id' => 5, 'name' => 'ช่างก่อสร้าง', 'icon' => 'fas fa-paint-brush'],
                            ['id' => 6, 'name' => 'ช่างซ่อมรถยนต์', 'icon' => 'fas fa-car']
                        ];
                    @endphp
                    
                    @foreach($programs as $program)
                    <div class="col-lg-4 mb-4">
                        <div class="card border-info">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0">
                                    <i class="{{ $program['icon'] }} me-2"></i>{{ $program['name'] }}
                                </h6>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('admin.welcome.update') }}">
                                    @csrf
                                    <input type="hidden" name="section" value="program_{{ $program['id'] }}_title">
                                    <input type="hidden" name="type" value="text">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">ชื่อหลักสูตร</label>
                                        <input type="text" class="form-control" name="content" 
                                               value="{{ $contents->where('section', 'program_' . $program['id'] . '_title')->first()->content ?? $program['name'] }}" required>
                                    </div>
                                    <button type="submit" class="btn btn-info btn-sm">
                                        <i class="fas fa-save me-1"></i>บันทึก
                                    </button>
                                </form>
                                
                                <hr>
                                
                                <form method="POST" action="{{ route('admin.welcome.update') }}">
                                    @csrf
                                    <input type="hidden" name="section" value="program_{{ $program['id'] }}_description">
                                    <input type="hidden" name="type" value="text">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">คำอธิบายหลักสูตร</label>
                                        <textarea class="form-control" name="content" rows="3" required>{{ $contents->where('section', 'program_' . $program['id'] . '_description')->first()->content ?? 'คำอธิบายหลักสูตร ' . $program['name'] }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-info btn-sm">
                                        <i class="fas fa-save me-1"></i>บันทึก
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- News Section -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h5 class="mb-3">
                            <i class="fas fa-newspaper me-2"></i>จัดการข่าวสาร
                        </h5>
                    </div>
                    
                    @for($i = 1; $i <= 3; $i++)
                    <div class="col-lg-4 mb-4">
                        <div class="card border-warning">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0">
                                    <i class="fas fa-newspaper me-2"></i>ข่าวสาร {{ $i }}
                                </h6>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('admin.welcome.update') }}">
                                    @csrf
                                    <input type="hidden" name="section" value="news_{{ $i }}_title">
                                    <input type="hidden" name="type" value="text">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">หัวข้อข่าว</label>
                                        <input type="text" class="form-control" name="content" 
                                               value="{{ $contents->where('section', 'news_' . $i . '_title')->first()->content ?? 'ข่าวสาร ' . $i }}" required>
                                    </div>
                                    <button type="submit" class="btn btn-warning btn-sm">
                                        <i class="fas fa-save me-1"></i>บันทึก
                                    </button>
                                </form>
                                
                                <hr>
                                
                                <form method="POST" action="{{ route('admin.welcome.update') }}">
                                    @csrf
                                    <input type="hidden" name="section" value="news_{{ $i }}_description">
                                    <input type="hidden" name="type" value="text">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">เนื้อหาข่าว</label>
                                        <textarea class="form-control" name="content" rows="3" required>{{ $contents->where('section', 'news_' . $i . '_description')->first()->content ?? 'เนื้อหาข่าว ' . $i }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-warning btn-sm">
                                        <i class="fas fa-save me-1"></i>บันทึก
                                    </button>
                                </form>
                                
                                <hr>
                                
                                <form method="POST" action="{{ route('admin.welcome.update') }}">
                                    @csrf
                                    <input type="hidden" name="section" value="news_{{ $i }}_date">
                                    <input type="hidden" name="type" value="text">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">วันที่</label>
                                        <input type="text" class="form-control" name="content" 
                                               value="{{ $contents->where('section', 'news_' . $i . '_date')->first()->content ?? '15 มกราคม 2024' }}" required>
                                    </div>
                                    <button type="submit" class="btn btn-warning btn-sm">
                                        <i class="fas fa-save me-1"></i>บันทึก
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>

                <!-- Preview Button -->
                <div class="row mt-4">
                    <div class="col-12 text-center">
                        <a href="{{ route('welcome') }}" target="_blank" class="btn btn-primary btn-lg">
                            <i class="fas fa-eye me-2"></i>ดูตัวอย่างเว็บไซต์
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
