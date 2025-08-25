<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'teacher_name',
        'class',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // ความสัมพันธ์กับตาราง subject_attendances
    public function subjectAttendances()
    {
        return $this->hasMany(SubjectAttendance::class);
    }

    // ฟังก์ชันสำหรับนับจำนวนนักเรียนที่มาเรียนในวิชานี้
    public function getAttendanceCount($status = 'present', $date = null)
    {
        $query = $this->subjectAttendances()->where('status', $status);
        
        if ($date) {
            $query->whereDate('date', $date);
        }
        
        return $query->count();
    }

    // ฟังก์ชันสำหรับคำนวณเปอร์เซ็นต์การมาเรียน
    public function getAttendancePercentage($date = null)
    {
        $query = $this->subjectAttendances();
        
        if ($date) {
            $query->whereDate('date', $date);
        }
        
        $total = $query->count();
        $present = $this->getAttendanceCount('present', $date);
        
        return $total > 0 ? round(($present / $total) * 100, 2) : 0;
    }
}
