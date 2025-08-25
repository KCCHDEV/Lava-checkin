@extends('layouts.base')

@section('title', $announcement->title . ' - วิทยาลัยเทคนิคลำพูน')

@push('styles')
<style>
.announcement-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 60px 0;
    margin-bottom: 40px;
}

.announcement-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 15px;
}

.announcement-meta {
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

.announcement-content {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    padding: 40px;
    margin-bottom: 30px;
}

.announcement-body {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #333;
}

.announcement-body p {
    margin-bottom: 1.5rem;
}

.announcement-info {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 30px;
}

.info-item {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.info-item:last-child {
    margin-bottom: 0;
}

.info-icon {
    width: 30px;
    color: #667eea;
    margin-right: 10px;
}

.info-text {
    color: #6c757d;
}

.status-badge {
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
}

.status-published {
    background: #d4edda;
    color: #155724;
}

.status-draft {
    background: #fff3cd;
    color: #856404;
}
</style>
@endpush

@section('content')
<!-- Announcement Header -->
<div class="announcement-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="announcement-title">{{ $announcement->title }}</h1>
                <div class="announcement-meta">
                    <i class="fas fa-calendar me-2"></i>
                    {{ $announcement->created_at->format('d/m/Y เวลา H:i น.') }}
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('announcements.public.index') }}" class="back-btn">
                    <i class="fas fa-arrow-left me-2"></i>กลับไปประกาศ
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Announcement Content -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Announcement Info -->
            <div class="announcement-info">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-item">
                            <i class="fas fa-user info-icon"></i>
                            <span class="info-text">ผู้ประกาศ: {{ $announcement->author->name ?? 'ไม่ระบุ' }}</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-eye info-icon"></i>
                            <span class="info-text">จำนวนการดู: {{ $announcement->view_count ?? 0 }} ครั้ง</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <i class="fas fa-tag info-icon"></i>
                            <span class="info-text">สถานะ: 
                                <span class="status-badge status-{{ $announcement->status }}">
                                    {{ $announcement->status === 'published' ? 'เผยแพร่' : 'ร่าง' }}
                                </span>
                            </span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-clock info-icon"></i>
                            <span class="info-text">อัปเดตล่าสุด: {{ $announcement->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Announcement Body -->
            <div class="announcement-content">
                <div class="announcement-body">
                    {!! nl2br(e($announcement->content)) !!}
                </div>
            </div>

            <!-- Navigation -->
            <div class="text-center">
                <a href="{{ route('announcements.public.index') }}" class="btn btn-primary me-3">
                    <i class="fas fa-list me-2"></i>ดูประกาศทั้งหมด
                </a>
                <a href="{{ route('welcome') }}" class="btn btn-outline-primary">
                    <i class="fas fa-home me-2"></i>กลับหน้าหลัก
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
