@extends('layouts.app')

@section('title', 'การตั้งค่าส่วนตัว - ระบบเช็คชื่อการมาเรียน')

@section('page-title', 'การตั้งค่าส่วนตัว')

@section('page-content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-sliders-h me-2"></i>การตั้งค่าส่วนตัว
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.preferences.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Language Settings -->
                    <div class="mb-4">
                        <h6 class="mb-3">ภาษา</h6>
                        <div class="mb-3">
                            <label class="form-label">ภาษาที่ใช้</label>
                            <select class="form-select" name="language">
                                <option value="th" {{ old('language', $user->language ?? 'th') === 'th' ? 'selected' : '' }}>ไทย</option>
                                <option value="en" {{ old('language', $user->language ?? 'th') === 'en' ? 'selected' : '' }}>English</option>
                            </select>
                        </div>
                    </div>

                    <!-- Timezone Settings -->
                    <div class="mb-4">
                        <h6 class="mb-3">เขตเวลา</h6>
                        <div class="mb-3">
                            <label class="form-label">เขตเวลา</label>
                            <select class="form-select" name="timezone">
                                <option value="Asia/Bangkok" {{ old('timezone', $user->timezone ?? 'Asia/Bangkok') === 'Asia/Bangkok' ? 'selected' : '' }}>Asia/Bangkok (GMT+7)</option>
                                <option value="Asia/Tokyo" {{ old('timezone', $user->timezone ?? 'Asia/Bangkok') === 'Asia/Tokyo' ? 'selected' : '' }}>Asia/Tokyo (GMT+9)</option>
                                <option value="Asia/Seoul" {{ old('timezone', $user->timezone ?? 'Asia/Bangkok') === 'Asia/Seoul' ? 'selected' : '' }}>Asia/Seoul (GMT+9)</option>
                                <option value="Asia/Singapore" {{ old('timezone', $user->timezone ?? 'Asia/Bangkok') === 'Asia/Singapore' ? 'selected' : '' }}>Asia/Singapore (GMT+8)</option>
                                <option value="UTC" {{ old('timezone', $user->timezone ?? 'Asia/Bangkok') === 'UTC' ? 'selected' : '' }}>UTC (GMT+0)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Notification Settings -->
                    <div class="mb-4">
                        <h6 class="mb-3">การแจ้งเตือน</h6>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="notifications_email" 
                                       value="1" {{ old('notifications_email', $user->notifications_email ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label">
                                    รับการแจ้งเตือนทางอีเมล
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="notifications_sms" 
                                       value="1" {{ old('notifications_sms', $user->notifications_sms ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label">
                                    รับการแจ้งเตือนทาง SMS
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Theme Settings -->
                    <div class="mb-4">
                        <h6 class="mb-3">ธีม</h6>
                        <div class="mb-3">
                            <label class="form-label">ธีมที่ต้องการ</label>
                            <select class="form-select" name="theme_preference">
                                <option value="auto" {{ old('theme_preference', $user->theme_preference ?? 'auto') === 'auto' ? 'selected' : '' }}>อัตโนมัติ (ตามระบบ)</option>
                                <option value="light" {{ old('theme_preference', $user->theme_preference ?? 'auto') === 'light' ? 'selected' : '' }}>สว่าง</option>
                                <option value="dark" {{ old('theme_preference', $user->theme_preference ?? 'auto') === 'dark' ? 'selected' : '' }}>มืด</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>บันทึกการตั้งค่า
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Current Settings -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-info-circle me-2"></i>การตั้งค่าปัจจุบัน
                </h6>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-6"><strong>ภาษา:</strong></div>
                    <div class="col-6">
                        @if(($user->language ?? 'th') === 'th')
                            <span class="badge bg-primary">ไทย</span>
                        @else
                            <span class="badge bg-secondary">English</span>
                        @endif
                    </div>
                </div>
                
                <div class="row mb-2">
                    <div class="col-6"><strong>เขตเวลา:</strong></div>
                    <div class="col-6">{{ $user->timezone ?? 'Asia/Bangkok' }}</div>
                </div>
                
                <div class="row mb-2">
                    <div class="col-6"><strong>อีเมล:</strong></div>
                    <div class="col-6">
                        @if($user->notifications_email ?? false)
                            <span class="badge bg-success">เปิด</span>
                        @else
                            <span class="badge bg-secondary">ปิด</span>
                        @endif
                    </div>
                </div>
                
                <div class="row mb-2">
                    <div class="col-6"><strong>SMS:</strong></div>
                    <div class="col-6">
                        @if($user->notifications_sms ?? false)
                            <span class="badge bg-success">เปิด</span>
                        @else
                            <span class="badge bg-secondary">ปิด</span>
                        @endif
                    </div>
                </div>
                
                <div class="row mb-2">
                    <div class="col-6"><strong>ธีม:</strong></div>
                    <div class="col-6">
                        @switch($user->theme_preference ?? 'auto')
                            @case('light')
                                <span class="badge bg-warning">สว่าง</span>
                                @break
                            @case('dark')
                                <span class="badge bg-dark">มืด</span>
                                @break
                            @default
                                <span class="badge bg-info">อัตโนมัติ</span>
                        @endswitch
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card shadow mt-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-cog me-2"></i>การดำเนินการ
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('profile.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-user me-2"></i>จัดการโปรไฟล์
                    </a>
                    
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.theme.index') }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-palette me-2"></i>จัดการธีมระบบ
                        </a>
                    @endif
                    
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-home me-2"></i>กลับหน้าหลัก
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.form-check-input:checked {
    background-color: #007bff;
    border-color: #007bff;
}
</style>
@endsection
