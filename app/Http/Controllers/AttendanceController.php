<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $attendances = Attendance::with('student')
            ->whereDate('date', $date)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $students = Student::all();
        
        return view('attendances.index', compact('attendances', 'students', 'date'));
    }

    public function create()
    {
        $students = Student::all();
        $date = Carbon::today()->format('Y-m-d');
        
        return view('attendances.create', compact('students', 'date'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,sick',
            'time' => 'nullable|date_format:H:i',
            'note' => 'nullable|string|max:500'
        ]);

        $attendance = Attendance::create([
            'student_id' => $request->student_id,
            'date' => $request->date,
            'status' => $request->status,
            'time' => $request->time,
            'note' => $request->note,
            'recorded_by' => auth()->id(),
            'recorder_id' => auth()->id()
        ]);

        // Clear cache
        cache()->forget('today_attendances_stats');
        cache()->forget('recent_attendances');
        cache()->forget("student_attendances_{$request->student_id}");

        return redirect()->route('attendances.index')
            ->with('success', 'เพิ่มข้อมูลการเช็คชื่อเรียบร้อยแล้ว');
    }

    public function show(Attendance $attendance)
    {
        $attendance->load('student', 'recorder');
        return view('attendances.show', compact('attendance'));
    }

    public function edit(Attendance $attendance)
    {
        $students = Student::all();
        return view('attendances.edit', compact('attendance', 'students'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,sick',
            'time' => 'nullable|date_format:H:i',
            'note' => 'nullable|string|max:500'
        ]);

        $attendance->update([
            'student_id' => $request->student_id,
            'date' => $request->date,
            'status' => $request->status,
            'time' => $request->time,
            'note' => $request->note
        ]);

        // Clear cache
        cache()->forget('today_attendances_stats');
        cache()->forget('recent_attendances');
        cache()->forget("student_attendances_{$request->student_id}");

        return redirect()->route('attendances.index')
            ->with('success', 'อัปเดตข้อมูลการเช็คชื่อเรียบร้อยแล้ว');
    }

    public function destroy(Attendance $attendance)
    {
        $studentId = $attendance->student_id;
        $attendance->delete();

        // Clear cache
        cache()->forget('today_attendances_stats');
        cache()->forget('recent_attendances');
        cache()->forget("student_attendances_{$studentId}");

        return redirect()->route('attendances.index')
            ->with('success', 'ลบข้อมูลการเช็คชื่อเรียบร้อยแล้ว');
    }

    // ฟังก์ชันสำหรับบันทึกการเช็คชื่อแบบกลุ่ม
    public function bulkStore(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,sick',
            'time' => 'nullable|date_format:H:i',
            'note' => 'nullable|string|max:500'
        ]);

        $created = 0;
        $skipped = 0;

        foreach ($request->student_ids as $studentId) {
            // Check if attendance already exists for this student on this date
            $existing = Attendance::where('student_id', $studentId)
                ->whereDate('date', $request->date)
                ->first();

            if ($existing) {
                $skipped++;
                continue;
            }

            Attendance::create([
                'student_id' => $studentId,
                'date' => $request->date,
                'status' => $request->status,
                'time' => $request->time,
                'note' => $request->note,
                'recorded_by' => auth()->id(),
                'recorder_id' => auth()->id()
            ]);

            $created++;
        }

        // Clear cache
        cache()->forget('today_attendances_stats');
        cache()->forget('recent_attendances');
        foreach ($request->student_ids as $studentId) {
            cache()->forget("student_attendances_{$studentId}");
        }

        $message = "เพิ่มข้อมูลการเช็คชื่อเรียบร้อยแล้ว: {$created} รายการ";
        if ($skipped > 0) {
            $message .= ", ข้าม {$skipped} รายการ (มีข้อมูลอยู่แล้ว)";
        }

        return redirect()->route('attendances.index')->with('success', $message);
    }
}
