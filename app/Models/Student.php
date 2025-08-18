<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'name',    // ชื่อ-นามสกุล
        'phone',   // เบอร์โทรศัพท์
        'address', // ที่อยู่
        'status',  // สถานะการมาเรียน
    ];

    // Status constants
    const STATUS_PRESENT = 'present';
    const STATUS_ABSENT = 'absent';
    const STATUS_LATE = 'late';

    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'present' => '<span class="badge bg-success">มาเรียน</span>',
            'absent' => '<span class="badge bg-danger">ขาดเรียน</span>',
            'late' => '<span class="badge bg-warning">มาสาย</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-secondary">ไม่ทราบ</span>';
    }

    public function getStatusTextAttribute(): string
    {
        $statuses = [
            'present' => 'มาเรียน',
            'absent' => 'ขาดเรียน',
            'late' => 'มาสาย',
        ];

        return $statuses[$this->status] ?? 'ไม่ทราบ';
    }
}
