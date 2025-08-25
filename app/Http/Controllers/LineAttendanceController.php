<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LineAttendance;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LineAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $status = $request->get('status');
        $lineNumber = $request->get('line_number');
        
        $query = LineAttendance::with(['student', 'recorder']);
        
        if ($date) {
            $query->whereDate('date', $date);
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        if ($lineNumber) {
            $query->where('line_number', $lineNumber);
        }
        
        $lineAttendances = $query->orderBy('created_at', 'desc')->paginate(20);
        $students = Student::all();
        
        // สถิติ
        $stats = [
            'total' => LineAttendance::count(),
            'present' => LineAttendance::where('status', 'present')->count(),
            'absent' => LineAttendance::where('status', 'absent')->count(),
            'late' => LineAttendance::where('status', 'late')->count(),
            'sick' => LineAttendance::where('status', 'sick')->count(),
        ];
        
        return view('line-attendances.index', compact('lineAttendances', 'students', 'date', 'status', 'lineNumber', 'stats'));
    }

    public function create()
    {
        $students = Student::all();
        $date = Carbon::today()->format('Y-m-d');
        $time = Carbon::now()->format('H:i');
        
        return view('line-attendances.create', compact('students', 'date', 'time'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
            'line_number' => 'required|integer|min:1|max:10',
            'position' => 'required|integer|min:1|max:50',
            'status' => 'required|in:present,absent,late,sick',
            'time' => 'nullable|date_format:H:i',
            'note' => 'nullable|string'
        ]);

        // ตรวจสอบว่ามีการบันทึกในวันเดียวกันหรือไม่
        $existing = LineAttendance::where('student_id', $request->student_id)
            ->whereDate('date', $request->date)
            ->first();

        if ($existing) {
            return back()->withErrors(['student_id' => 'มีการบันทึกข้อมูลในวันนี้แล้ว']);
        }

        LineAttendance::create([
            'student_id' => $request->student_id,
            'date' => $request->date,
            'line_number' => $request->line_number,
            'position' => $request->position,
            'time' => $request->time,
            'status' => $request->status,
            'note' => $request->note,
            'recorded_by' => Auth::id()
        ]);

        // Clear cache
        cache()->forget("student_attendances_{$request->student_id}");

        return redirect()->route('line-attendances.index')
            ->with('success', 'บันทึกการเช็คเข้าแถวเรียบร้อยแล้ว');
    }

    public function show(LineAttendance $lineAttendance)
    {
        $lineAttendance->load('student', 'recorder');
        return view('line-attendances.show', compact('lineAttendance'));
    }

    public function edit(LineAttendance $lineAttendance)
    {
        $students = Student::all();
        return view('line-attendances.edit', compact('lineAttendance', 'students'));
    }

    public function update(Request $request, LineAttendance $lineAttendance)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
            'line_number' => 'required|integer|min:1|max:10',
            'position' => 'required|integer|min:1|max:50',
            'status' => 'required|in:present,absent,late,sick',
            'time' => 'nullable|date_format:H:i',
            'note' => 'nullable|string'
        ]);

        // ตรวจสอบว่ามีการบันทึกซ้ำหรือไม่ (ยกเว้นรายการที่กำลังแก้ไข)
        $existing = LineAttendance::where('student_id', $request->student_id)
            ->whereDate('date', $request->date)
            ->where('id', '!=', $lineAttendance->id)
            ->first();

        if ($existing) {
            return back()->withErrors(['student_id' => 'มีการบันทึกข้อมูลในวันนี้แล้ว']);
        }

        $lineAttendance->update([
            'student_id' => $request->student_id,
            'date' => $request->date,
            'line_number' => $request->line_number,
            'position' => $request->position,
            'time' => $request->time,
            'status' => $request->status,
            'note' => $request->note
        ]);

        // Clear cache
        cache()->forget("student_attendances_{$request->student_id}");

        return redirect()->route('line-attendances.index')
            ->with('success', 'อัปเดตการเช็คเข้าแถวเรียบร้อยแล้ว');
    }

    public function destroy(LineAttendance $lineAttendance)
    {
        $studentId = $lineAttendance->student_id;
        $lineAttendance->delete();

        // Clear cache
        cache()->forget("student_attendances_{$studentId}");

        return redirect()->route('line-attendances.index')
            ->with('success', 'ลบข้อมูลการเช็คเข้าแถวเรียบร้อยแล้ว');
    }

    // ฟังก์ชันสำหรับบันทึกการเช็คเข้าแถวแบบกลุ่ม
    public function bulkStore(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'line_number' => 'required|integer|min:1|max:10',
            'status' => 'required|in:present,absent,late,sick',
            'time' => 'nullable|date_format:H:i',
            'note' => 'nullable|string',
            'start_position' => 'nullable|integer|min:1',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id'
        ]);

        $startPosition = $request->start_position ?? 1;
        $createdCount = 0;
        $skippedCount = 0;

        foreach ($request->student_ids as $index => $studentId) {
            // ตรวจสอบว่ามีการบันทึกในวันเดียวกันหรือไม่
            $existing = LineAttendance::where('student_id', $studentId)
                ->whereDate('date', $request->date)
                ->first();

            if (!$existing) {
                LineAttendance::create([
                    'student_id' => $studentId,
                    'date' => $request->date,
                    'line_number' => $request->line_number,
                    'position' => $startPosition + $index,
                    'time' => $request->time,
                    'status' => $request->status,
                    'note' => $request->note,
                    'recorded_by' => Auth::id()
                ]);
                $createdCount++;
            } else {
                $skippedCount++;
            }
        }

        // Clear cache for all affected students
        foreach ($request->student_ids as $studentId) {
            cache()->forget("student_attendances_{$studentId}");
        }

        $message = "บันทึกการเช็คเข้าแถวเรียบร้อยแล้ว ({$createdCount} รายการ)";
        if ($skippedCount > 0) {
            $message .= " ข้าม {$skippedCount} รายการที่มีอยู่แล้ว";
        }

        return redirect()->route('line-attendances.index')
            ->with('success', $message);
    }
}
