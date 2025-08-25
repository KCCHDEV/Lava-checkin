@extends('layouts.app')

@section('title', 'รายละเอียดนักเรียน - ระบบเช็คชื่อการมาเรียน')

@section('page-title', 'รายละเอียดนักเรียน')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-user me-2"></i>ข้อมูลนักเรียน
                </h6>
                <div>
                    @if(Auth::user() && Auth::user()->isAdmin())
                    <a href="{{ route('students.edit', $student) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-2"></i>แก้ไข
                    </a>
                    @endif
                    <a href="{{ route('students.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-2"></i>กลับ
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 120px; height: 120px;">
                                <i class="fas fa-user text-white" style="font-size: 3rem;"></i>
                            </div>
                        </div>
                        <h5 class="mb-1">{{ $student->name }}</h5>
                        <p class="text-muted mb-0">{{ $student->student_id }}</p>
                        <span class="badge bg-info mt-2">{{ $student->class }}</span>
                    </div>
                    <div class="col-md-8">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th class="ps-0" scope="row" style="width: 150px;">รหัสนักเรียน:</th>
                                        <td class="text-muted">{{ $student->student_id }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">ชื่อ-นามสกุล:</th>
                                        <td class="text-muted">{{ $student->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">ชั้นเรียน:</th>
                                        <td class="text-muted">{{ $student->class }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">อีเมล:</th>
                                        <td class="text-muted">{{ $student->user->email ?? 'ไม่ระบุ' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">เบอร์โทรศัพท์:</th>
                                        <td class="text-muted">{{ $student->phone ?: 'ไม่ระบุ' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">ที่อยู่:</th>
                                        <td class="text-muted">{{ $student->address ?: 'ไม่ระบุ' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">วันที่สมัคร:</th>
                                        <td class="text-muted">{{ $student->created_at ? $student->created_at->format('d/m/Y H:i') : 'ไม่ระบุ' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">อัปเดตล่าสุด:</th>
                                        <td class="text-muted">{{ $student->updated_at ? $student->updated_at->format('d/m/Y H:i') : 'ไม่ระบุ' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @if(Auth::user() && Auth::user()->isAdmin())
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">การดำเนินการ</h6>
                    <div>
                        <a href="{{ route('students.edit', $student) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>แก้ไขข้อมูล
                        </a>
                        <form action="{{ route('students.destroy', $student) }}" 
                              method="POST" 
                              class="d-inline" 
                              onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบนักเรียนคนนี้? การดำเนินการนี้ไม่สามารถยกเลิกได้')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash me-2"></i>ลบนักเรียน
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
