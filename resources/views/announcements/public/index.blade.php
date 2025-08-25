@extends('layouts.base')

@section('title', 'ประกาศ - วิทยาลัยเทคนิคลำพูน')

@push('styles')
<style>
.announcement-card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.announcement-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.announcement-date {
    color: #6c757d;
    font-size: 0.9rem;
}

.announcement-excerpt {
    color: #495057;
    line-height: 1.6;
}

.read-more-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    padding: 8px 20px;
    border-radius: 25px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.read-more-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    color: white;
}

.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 60px 0;
    margin-bottom: 40px;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 10px;
}

.section-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
}

.back-btn {
    background: rgba(255,255,255,0.2);
    border: 2px solid rgba(255,255,255,0.3);
    color: white;
    padding: 10px 25px;
    border-radius: 25px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.back-btn:hover {
    background: rgba(255,255,255,0.3);
    color: white;
    transform: translateY(-2px);
}

.no-announcements {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}

.no-announcements i {
    font-size: 4rem;
    margin-bottom: 20px;
    opacity: 0.5;
}
</style>
@endpush

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="section-title">ประกาศ</h1>
                <p class="section-subtitle">ข่าวสารและประกาศสำคัญจากวิทยาลัยเทคนิคลำพูน</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('welcome') }}" class="back-btn">
                    <i class="fas fa-arrow-left me-2"></i>กลับหน้าหลัก
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Announcements Section -->
<div class="container">
    @if($announcements->count() > 0)
        <div class="row">
            @foreach($announcements as $announcement)
            <div class="col-lg-6 col-xl-4 mb-4">
                <div class="card announcement-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge bg-{{ $announcement->status === 'published' ? 'success' : 'warning' }}">
                                {{ $announcement->status === 'published' ? 'เผยแพร่' : 'ร่าง' }}
                            </span>
                            <small class="announcement-date">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $announcement->created_at->format('d/m/Y H:i') }}
                            </small>
                        </div>
                        
                        <h5 class="card-title mb-3">{{ $announcement->title }}</h5>
                        
                        <p class="announcement-excerpt">
                            {{ Str::limit($announcement->content, 150) }}
                        </p>
                        
                        <div class="mt-auto">
                            <a href="{{ route('announcements.public.show', $announcement->id) }}" class="read-more-btn">
                                <i class="fas fa-eye me-2"></i>อ่านเพิ่มเติม
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        @if($announcements->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $announcements->links() }}
        </div>
        @endif
    @else
        <div class="no-announcements">
            <i class="fas fa-bullhorn"></i>
            <h3>ยังไม่มีประกาศ</h3>
            <p>ยังไม่มีประกาศใดๆ ในขณะนี้ กรุณาตรวจสอบอีกครั้งในภายหลัง</p>
            <a href="{{ route('welcome') }}" class="btn btn-primary">
                <i class="fas fa-home me-2"></i>กลับหน้าหลัก
            </a>
        </div>
    @endif
</div>
@endsection
