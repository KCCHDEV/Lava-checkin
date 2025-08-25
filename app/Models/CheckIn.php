<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CheckIn extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'recorded_by',
        'check_in_code',
        'check_in_time',
        'check_in_date',
        'status',
        'location',
        'device_info',
        'ip_address',
        'note',
        'is_valid'
    ];

    protected $casts = [
        'check_in_time' => 'datetime',
        'check_in_date' => 'date',
        'is_valid' => 'boolean'
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // Helper methods
    public function getStatusTextAttribute()
    {
        $statuses = [
            'present' => 'เช็คชื่อแล้ว',
            'late' => 'มาสาย',
            'invalid' => 'ไม่ถูกต้อง'
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'present' => 'success',
            'late' => 'warning',
            'invalid' => 'danger'
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    // Generate unique check-in code
    public static function generateCheckInCode()
    {
        do {
            $code = Str::random(12) . time();
        } while (self::where('check_in_code', $code)->exists());

        return $code;
    }

    // Check if code is still valid (within time limit)
    public function isCodeValid($timeLimit = 600) // 10 minutes default
    {
        if (!$this->is_valid) {
            return false;
        }

        $createdTime = $this->created_at;
        $currentTime = Carbon::now();
        
        return $currentTime->diffInSeconds($createdTime) <= $timeLimit;
    }

    // Get check-ins for today
    public static function todayCheckIns()
    {
        return self::whereDate('check_in_date', Carbon::today())
                   ->with(['student', 'recorder'])
                   ->orderBy('check_in_time', 'desc');
    }

    // Get check-ins by date range
    public static function checkInsByDateRange($startDate, $endDate)
    {
        return self::whereBetween('check_in_date', [$startDate, $endDate])
                   ->with(['student', 'recorder'])
                   ->orderBy('check_in_time', 'desc');
    }
}
