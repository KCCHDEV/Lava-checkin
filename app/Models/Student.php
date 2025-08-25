<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'student_id',
        'class'
    ];

    // ความสัมพันธ์กับตาราง attendances
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // ความสัมพันธ์กับตาราง subject_attendances
    public function subjectAttendances()
    {
        return $this->hasMany(SubjectAttendance::class);
    }

    // ความสัมพันธ์กับตาราง line_attendances
    public function lineAttendances()
    {
        return $this->hasMany(LineAttendance::class);
    }

    // ความสัมพันธ์กับตาราง users
    public function user()
    {
        return $this->hasOne(User::class);
    }

    // ฟังก์ชันสำหรับนับจำนวนครั้งที่มาเรียน
    public function getAttendanceCount($status = 'present')
    {
        return $this->attendances()->where('status', $status)->count();
    }

    // ฟังก์ชันสำหรับคำนวณเปอร์เซ็นต์การมาเรียน
    public function getAttendancePercentage()
    {
        $total = $this->attendances()->count();
        $present = $this->getAttendanceCount('present');
        
        return $total > 0 ? round(($present / $total) * 100, 2) : 0;
    }

    // ฟังก์ชันสำหรับนับจำนวนครั้งที่มาเรียนรายวิชา
    public function getSubjectAttendanceCount($status = 'present', $subjectId = null)
    {
        $query = $this->subjectAttendances()->where('status', $status);
        
        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }
        
        return $query->count();
    }

    // ฟังก์ชันสำหรับคำนวณเปอร์เซ็นต์การมาเรียนรายวิชา
    public function getSubjectAttendancePercentage($subjectId = null)
    {
        $query = $this->subjectAttendances();
        
        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }
        
        $total = $query->count();
        $present = $this->getSubjectAttendanceCount('present', $subjectId);
        
        return $total > 0 ? round(($present / $total) * 100, 2) : 0;
    }

    // ฟังก์ชันสำหรับนับจำนวนครั้งที่มาเข้าแถว
    public function getLineAttendanceCount($status = 'present')
    {
        return $this->lineAttendances()->where('status', $status)->count();
    }

    // ฟังก์ชันสำหรับคำนวณเปอร์เซ็นต์การเข้าแถว
    public function getLineAttendancePercentage()
    {
        $total = $this->lineAttendances()->count();
        $present = $this->getLineAttendanceCount('present');
        
        return $total > 0 ? round(($present / $total) * 100, 2) : 0;
    }
}
