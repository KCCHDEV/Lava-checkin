<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'date',
        'time',
        'period',
        'status',
        'note',
        'recorded_by'
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i'
    ];

    // ความสัมพันธ์กับตาราง students
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // ความสัมพันธ์กับตาราง subjects
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    // ความสัมพันธ์กับตาราง users (ผู้บันทึก)
    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // ฟังก์ชันสำหรับแปลงสถานะเป็นภาษาไทย
    public function getStatusTextAttribute()
    {
        $statuses = [
            'present' => 'มาเรียน',
            'absent' => 'ขาดเรียน',
            'late' => 'มาสาย',
            'sick' => 'ป่วย'
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    // ฟังก์ชันสำหรับสีของสถานะ
    public function getStatusColorAttribute()
    {
        $colors = [
            'present' => 'success',
            'absent' => 'danger',
            'late' => 'warning',
            'sick' => 'info'
        ];

        return $colors[$this->status] ?? 'secondary';
    }
}
