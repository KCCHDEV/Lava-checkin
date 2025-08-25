@extends('layouts.app')

@section('title', 'จัดการประกาศ - ระบบเช็คชื่อการมาเรียน')

@section('page-title', 'จัดการประกาศ')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-bullhorn me-2"></i>ประกาศทั้งหมด
                </h6>
                <a href="{{ route('announcements.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>เพิ่มประกาศใหม่
                </a>
            </div>
            <div class="card-body">
                @if($announcements->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>หัวข้อ</th>
                                <th>ประเภท</th>
                                <th>ความสำคัญ</th>
                                <th>สถานะ</th>
                                <th>วันที่เผยแพร่</th>
                                <th>ผู้สร้าง</th>
                                <th>การดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($announcements as $announcement)
                            <tr>
                                <td>
                                    <strong>{{ $announcement->title }}</strong>
                                    @if($announcement->is_expired)
                                        <span class="badge bg-danger ms-2">หมดอายุ</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $announcement->type_text }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $announcement->priority_color }}">
                                        {{ $announcement->priority_text }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $announcement->status === 'published' ? 'success' : ($announcement->status === 'draft' ? 'warning' : 'secondary') }}">
                                        {{ $announcement->status_text }}
                                    </span>
                                </td>
                                <td>
                                    {{ $announcement->published_at ? $announcement->published_at->format('d/m/Y H:i') : 'ไม่ระบุ' }}
                                </td>
                                <td>{{ $announcement->creator->name ?? 'ไม่ระบุ' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('announcements.show', $announcement) }}" 
                                           class="btn btn-sm btn-info" title="ดู">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('announcements.edit', $announcement) }}" 
                                           class="btn btn-sm btn-warning" title="แก้ไข">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('announcements.toggle-status', $announcement) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-{{ $announcement->status === 'published' ? 'secondary' : 'success' }}" 
                                                    title="{{ $announcement->status === 'published' ? 'เปลี่ยนเป็นร่าง' : 'เผยแพร่' }}">
                                                <i class="fas fa-{{ $announcement->status === 'published' ? 'eye-slash' : 'eye' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('announcements.destroy', $announcement) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบประกาศนี้?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $announcements->links() }}
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-bullhorn fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">ยังไม่มีประกาศ</h5>
                    <p class="text-muted">เริ่มต้นโดยการเพิ่มประกาศใหม่</p>
                    <a href="{{ route('announcements.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>เพิ่มประกาศใหม่
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
