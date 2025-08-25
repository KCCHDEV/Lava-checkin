@extends('layouts.app')

@section('title', 'ดูข้อมูลตาราง ' . ucfirst(str_replace('_', ' ', $table)) . ' - ระบบเช็คชื่อการมาเรียน')

@section('page-title', 'ดูข้อมูลตาราง: ' . ucfirst(str_replace('_', ' ', $table)))

@section('page-content')
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-table me-2"></i>{{ ucfirst(str_replace('_', ' ', $table)) }}
                    <small class="text-muted">({{ $data->total() }} รายการ)</small>
                </h6>
                <div>
                    <button type="button" class="btn btn-success btn-sm" onclick="exportTable('{{ $table }}', 'json')">
                        <i class="fas fa-file-code me-2"></i>Export JSON
                    </button>
                    <button type="button" class="btn btn-warning btn-sm" onclick="exportTable('{{ $table }}', 'csv')">
                        <i class="fas fa-file-csv me-2"></i>Export CSV
                    </button>
                    <a href="{{ route('admin.database.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-2"></i>กลับ
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($data->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-dark">
                                <tr>
                                    @foreach($columns as $column)
                                        <th>{{ ucfirst(str_replace('_', ' ', $column)) }}</th>
                                    @endforeach
                                    <th>การดำเนินการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $row)
                                <tr>
                                    @foreach($columns as $column)
                                        <td>
                                            @php
                                                $value = $row->$column;
                                                if (is_null($value)) {
                                                    echo '<span class="text-muted">NULL</span>';
                                                } elseif (is_bool($value)) {
                                                    echo $value ? '<span class="badge bg-success">True</span>' : '<span class="badge bg-secondary">False</span>';
                                                } elseif (is_string($value) && strlen($value) > 50) {
                                                    echo '<span title="' . htmlspecialchars($value) . '">' . htmlspecialchars(substr($value, 0, 50)) . '...</span>';
                                                } elseif (in_array($column, ['created_at', 'updated_at']) && $value) {
                                                    echo \Carbon\Carbon::parse($value)->format('d/m/Y H:i:s');
                                                } else {
                                                    echo htmlspecialchars($value);
                                                }
                                            @endphp
                                        </td>
                                    @endforeach
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="deleteRecord('{{ $table }}', {{ $row->id }})" 
                                                title="ลบ">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $data->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">ไม่มีข้อมูลในตารางนี้</h5>
                        <p class="text-muted">ตาราง {{ ucfirst(str_replace('_', ' ', $table)) }} ยังไม่มีข้อมูล</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Table Information -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-info-circle me-2"></i>ข้อมูลตาราง
                </h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td><strong>ชื่อตาราง:</strong></td>
                        <td>{{ $table }}</td>
                    </tr>
                    <tr>
                        <td><strong>จำนวนคอลัมน์:</strong></td>
                        <td>{{ count($columns) }}</td>
                    </tr>
                    <tr>
                        <td><strong>จำนวนข้อมูล:</strong></td>
                        <td>{{ $data->total() }}</td>
                    </tr>
                    <tr>
                        <td><strong>หน้าปัจจุบัน:</strong></td>
                        <td>{{ $data->currentPage() }} จาก {{ $data->lastPage() }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-list me-2"></i>รายชื่อคอลัมน์
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($columns as $column)
                        <div class="col-md-6 mb-2">
                            <span class="badge bg-light text-dark">{{ $column }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Form -->
<form id="exportForm" action="{{ route('admin.database.export') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="table" id="exportTable">
    <input type="hidden" name="format" id="exportFormat">
</form>

<!-- Delete Form -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function exportTable(table, format) {
    document.getElementById('exportTable').value = table;
    document.getElementById('exportFormat').value = format;
    document.getElementById('exportForm').submit();
}

function deleteRecord(table, id) {
    if (confirm('คุณแน่ใจหรือไม่ที่จะลบข้อมูลนี้?')) {
        const form = document.getElementById('deleteForm');
        form.action = "{{ route('admin.database.delete-record', ['table' => ':table', 'id' => ':id']) }}"
            .replace(':table', table)
            .replace(':id', id);
        form.submit();
    }
}
</script>

<style>
.table th {
    white-space: nowrap;
    font-size: 0.875rem;
}

.table td {
    font-size: 0.875rem;
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
}

.badge {
    font-size: 0.75rem;
}
</style>
@endsection
