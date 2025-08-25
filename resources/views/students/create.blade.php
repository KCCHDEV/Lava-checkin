@extends('layouts.app')

@section('title', 'เพิ่มนักเรียนใหม่ - ระบบเช็คชื่อการมาเรียน')

@section('page-title', 'เพิ่มนักเรียนใหม่')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-user-plus me-2"></i>ข้อมูลนักเรียนใหม่
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('students.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">ชื่อ-นามสกุล <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="student_id" class="form-label">รหัสนักเรียน <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('student_id') is-invalid @enderror" 
                                       id="student_id" name="student_id" value="{{ old('student_id') }}" required>
                                @error('student_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="class" class="form-label">ชั้นเรียน <span class="text-danger">*</span></label>
                                <select class="form-select @error('class') is-invalid @enderror" id="class" name="class" required>
                                    <option value="">เลือกชั้นเรียน</option>
                                    <option value="ปวช.1/1" {{ old('class') == 'ปวช.1/1' ? 'selected' : '' }}>ปวช.1/1</option>
                                    <option value="ปวช.1/2" {{ old('class') == 'ปวช.1/2' ? 'selected' : '' }}>ปวช.1/2</option>
                                    <option value="ปวช.1/3" {{ old('class') == 'ปวช.1/3' ? 'selected' : '' }}>ปวช.1/3</option>
                                    <option value="ปวช.2/1" {{ old('class') == 'ปวช.2/1' ? 'selected' : '' }}>ปวช.2/1</option>
                                    <option value="ปวช.2/2" {{ old('class') == 'ปวช.2/2' ? 'selected' : '' }}>ปวช.2/2</option>
                                    <option value="ปวช.2/3" {{ old('class') == 'ปวช.2/3' ? 'selected' : '' }}>ปวช.2/3</option>
                                    <option value="ปวช.3/1" {{ old('class') == 'ปวช.3/1' ? 'selected' : '' }}>ปวช.3/1</option>
                                    <option value="ปวช.3/2" {{ old('class') == 'ปวช.3/2' ? 'selected' : '' }}>ปวช.3/2</option>
                                    <option value="ปวช.3/3" {{ old('class') == 'ปวช.3/3' ? 'selected' : '' }}>ปวช.3/3</option>
                                    <option value="ปวส.1/1" {{ old('class') == 'ปวส.1/1' ? 'selected' : '' }}>ปวส.1/1</option>
                                    <option value="ปวส.1/2" {{ old('class') == 'ปวส.1/2' ? 'selected' : '' }}>ปวส.1/2</option>
                                    <option value="ปวส.2/1" {{ old('class') == 'ปวส.2/1' ? 'selected' : '' }}>ปวส.2/1</option>
                                    <option value="ปวส.2/2" {{ old('class') == 'ปวส.2/2' ? 'selected' : '' }}>ปวส.2/2</option>
                                </select>
                                @error('class')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">เบอร์โทรศัพท์</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">ที่อยู่</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="3">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    <h6 class="mb-3">ข้อมูลบัญชีผู้ใช้</h6>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">อีเมล <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">รหัสผ่าน <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">ยืนยันรหัสผ่าน <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" 
                               id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>กลับ
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>บันทึกข้อมูล
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

