@extends('layouts.app')

@section('title', 'จัดการโปรไฟล์ - ระบบเช็คชื่อการมาเรียน')

@section('page-title', 'จัดการโปรไฟล์')

@section('page-content')
<div class="row">
    <!-- Profile Information -->
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-user me-2"></i>ข้อมูลส่วนตัว
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">ชื่อ-นามสกุล <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">อีเมล <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">เบอร์โทรศัพท์</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       name="phone" value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">วันเกิด</label>
                                <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                       name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth) }}">
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">เพศ</label>
                                <select class="form-select @error('gender') is-invalid @enderror" name="gender">
                                    <option value="">เลือกเพศ</option>
                                    <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>ชาย</option>
                                    <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>หญิง</option>
                                    <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>อื่นๆ</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">รูปโปรไฟล์</label>
                                <input type="file" class="form-control @error('avatar') is-invalid @enderror" 
                                       name="avatar" accept="image/*">
                                @error('avatar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">รองรับไฟล์ JPG, PNG, GIF ขนาดไม่เกิน 2MB</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">ที่อยู่</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">ข้อมูลเพิ่มเติม</label>
                        <textarea class="form-control @error('bio') is-invalid @enderror" 
                                  name="bio" rows="3">{{ old('bio', $user->bio) }}</textarea>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>บันทึกข้อมูล
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Change Password -->
        <div class="card shadow mt-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-lock me-2"></i>เปลี่ยนรหัสผ่าน
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">รหัสผ่านปัจจุบัน</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                       name="current_password">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">รหัสผ่านใหม่</label>
                                <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                       name="new_password">
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">ยืนยันรหัสผ่านใหม่</label>
                                <input type="password" class="form-control" name="new_password_confirmation">
                            </div>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-key me-2"></i>เปลี่ยนรหัสผ่าน
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Profile Sidebar -->
    <div class="col-lg-4">
        <!-- Avatar Card -->
        <div class="card shadow">
            <div class="card-body text-center">
                <div class="mb-3">
                    @if($user->avatar)
                        <img src="{{ Storage::url($user->avatar) }}" 
                             alt="Avatar" class="rounded-circle" 
                             style="width: 120px; height: 120px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto"
                             style="width: 120px; height: 120px; font-size: 3rem;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                
                <h5 class="card-title">{{ $user->name }}</h5>
                <p class="text-muted">{{ $user->email }}</p>
                
                @if($user->avatar)
                    <form action="{{ route('profile.delete-avatar') }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                onclick="return confirm('คุณแน่ใจหรือไม่ที่จะลบรูปโปรไฟล์?')">
                            <i class="fas fa-trash me-1"></i>ลบรูป
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Account Information -->
        <div class="card shadow mt-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-info-circle me-2"></i>ข้อมูลบัญชี
                </h6>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-4"><strong>สถานะ:</strong></div>
                    <div class="col-8">
                        @if($user->email_verified_at)
                            <span class="badge bg-success">ยืนยันแล้ว</span>
                        @else
                            <span class="badge bg-warning">ยังไม่ยืนยัน</span>
                        @endif
                    </div>
                </div>
                
                <div class="row mb-2">
                    <div class="col-4"><strong>บทบาท:</strong></div>
                    <div class="col-8">
                        @if($user->isAdmin())
                            <span class="badge bg-danger">ผู้ดูแลระบบ</span>
                        @elseif($user->isTeacher())
                            <span class="badge bg-primary">ครู</span>
                        @elseif($user->isStudent())
                            <span class="badge bg-info">นักเรียน</span>
                        @else
                            <span class="badge bg-secondary">ผู้ใช้</span>
                        @endif
                    </div>
                </div>
                
                <div class="row mb-2">
                    <div class="col-4"><strong>เข้าร่วม:</strong></div>
                    <div class="col-8">{{ $user->created_at->format('d/m/Y') }}</div>
                </div>
                
                <div class="row mb-2">
                    <div class="col-4"><strong>อัปเดตล่าสุด:</strong></div>
                    <div class="col-8">{{ $user->updated_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card shadow mt-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-cog me-2"></i>การตั้งค่า
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('profile.preferences') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-sliders-h me-2"></i>การตั้งค่าส่วนตัว
                    </a>
                    
                    @if($user->isStudent())
                        <a href="{{ route('student.dashboard') }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-tachometer-alt me-2"></i>แดชบอร์ดนักเรียน
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-tachometer-alt me-2"></i>แดชบอร์ด
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.rounded-circle {
    border: 3px solid #e3e6f0;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}
</style>
@endsection
