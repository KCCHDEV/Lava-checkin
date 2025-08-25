<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubjectAttendance;
use App\Models\Subject;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SubjectAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $subjectId = $request->get('subject_id');
        $status = $request->get('status');
        
        $query = SubjectAttendance::with(['student', 'subject', 'recorder']);
        
        if ($date) {
            $query->whereDate('date', $date);
        }
        
        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $subjectAttendances = $query->orderBy('created_at', 'desc')->paginate(20);
        $subjects = Subject::where('is_active', true)->get();
        $students = Student::all();
        
        // สถิติ
        $stats = [
            'total' => SubjectAttendance::count(),
            'present' => SubjectAttendance::where('status', 'present')->count(),
            'absent' => SubjectAttendance::where('status', 'absent')->count(),
            'late' => SubjectAttendance::where('status', 'late')->count(),
            'sick' => SubjectAttendance::where('status', 'sick')->count(),
        ];
        
        return view('subject-attendances.index', compact('subjectAttendances', 'subjects', 'students', 'date', 'subjectId', 'status', 'stats'));
    }

    public function create()
    {
        $subjects = Subject::where('is_active', true)->get();
        $students = Student::all();
        $date = Carbon::today()->format('Y-m-d');
        
        return view('subject-attendances.create', compact('subjects', 'students', 'date'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,sick',
            'time' => 'nullable|date_format:H:i',
            'period' => 'nullable|string',
            'note' => 'nullable|string'
        ]);

        // ตรวจสอบว่ามีการบันทึกในวันเดียวกันหรือไม่
        $existing = SubjectAttendance::where('student_id', $request->student_id)
            ->where('subject_id', $request->subject_id)
            ->whereDate('date', $request->date)
            ->first();

        if ($existing) {
            return back()->withErrors(['student_id' => 'มีการบันทึกข้อมูลในวันนี้แล้ว']);
        }

        SubjectAttendance::create([
            'student_id' => $request->student_id,
            'subject_id' => $request->subject_id,
            'date' => $request->date,
            'time' => $request->time,
            'period' => $request->period,
            'status' => $request->status,
            'note' => $request->note,
            'recorded_by' => Auth::id()
        ]);

        // Clear cache
        cache()->forget("student_attendances_{$request->student_id}");

        return redirect()->route('subject-attendances.index')
            ->with('success', 'บันทึกการเช็คชื่อรายวิชาเรียบร้อยแล้ว');
    }

    public function show(SubjectAttendance $subjectAttendance)
    {
        $subjectAttendance->load('student', 'subject', 'recorder');
        return view('subject-attendances.show', compact('subjectAttendance'));
    }

    public function edit(SubjectAttendance $subjectAttendance)
    {
        $subjects = Subject::where('is_active', true)->get();
        $students = Student::all();
        return view('subject-attendances.edit', compact('subjectAttendance', 'subjects', 'students'));
    }

    public function update(Request $request, SubjectAttendance $subjectAttendance)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,sick',
            'time' => 'nullable|date_format:H:i',
            'period' => 'nullable|string',
            'note' => 'nullable|string'
        ]);

        // ตรวจสอบว่ามีการบันทึกซ้ำหรือไม่ (ยกเว้นรายการที่กำลังแก้ไข)
        $existing = SubjectAttendance::where('student_id', $request->student_id)
            ->where('subject_id', $request->subject_id)
            ->whereDate('date', $request->date)
            ->where('id', '!=', $subjectAttendance->id)
            ->first();

        if ($existing) {
            return back()->withErrors(['student_id' => 'มีการบันทึกข้อมูลในวันนี้แล้ว']);
        }

        $subjectAttendance->update([
            'student_id' => $request->student_id,
            'subject_id' => $request->subject_id,
            'date' => $request->date,
            'time' => $request->time,
            'period' => $request->period,
            'status' => $request->status,
            'note' => $request->note
        ]);

        // Clear cache
        cache()->forget("student_attendances_{$request->student_id}");

        return redirect()->route('subject-attendances.index')
            ->with('success', 'อัปเดตการเช็คชื่อรายวิชาเรียบร้อยแล้ว');
    }

    public function destroy(SubjectAttendance $subjectAttendance)
    {
        $studentId = $subjectAttendance->student_id;
        $subjectAttendance->delete();

        // Clear cache
        cache()->forget("student_attendances_{$studentId}");

        return redirect()->route('subject-attendances.index')
            ->with('success', 'ลบข้อมูลการเช็คชื่อรายวิชาเรียบร้อยแล้ว');
    }

    // ฟังก์ชันสำหรับบันทึกการเช็คชื่อแบบกลุ่ม
    public function bulkStore(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,sick',
            'time' => 'nullable|date_format:H:i',
            'period' => 'nullable|string',
            'note' => 'nullable|string',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id'
        ]);

        $createdCount = 0;
        $skippedCount = 0;

        foreach ($request->student_ids as $studentId) {
            // ตรวจสอบว่ามีการบันทึกในวันเดียวกันหรือไม่
            $existing = SubjectAttendance::where('student_id', $studentId)
                ->where('subject_id', $request->subject_id)
                ->whereDate('date', $request->date)
                ->first();

            if (!$existing) {
                SubjectAttendance::create([
                    'student_id' => $studentId,
                    'subject_id' => $request->subject_id,
                    'date' => $request->date,
                    'time' => $request->time,
                    'period' => $request->period,
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

        $message = "บันทึกการเช็คชื่อรายวิชาเรียบร้อยแล้ว ({$createdCount} รายการ)";
        if ($skippedCount > 0) {
            $message .= " ข้าม {$skippedCount} รายการที่มีอยู่แล้ว";
        }

        return redirect()->route('subject-attendances.index')
            ->with('success', $message);
    }
}
