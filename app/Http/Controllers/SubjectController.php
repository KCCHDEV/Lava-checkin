<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::paginate(10);
        return view('subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('subjects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:subjects,code|max:20',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'teacher_name' => 'required|string|max:255',
            'class' => 'required|string|max:50'
        ]);

        Subject::create($request->all());

        return redirect()->route('subjects.index')
            ->with('success', 'เพิ่มข้อมูลวิชาเรียบร้อยแล้ว');
    }

    public function show(Subject $subject)
    {
        $subject->load('subjectAttendances.student');
        return view('subjects.show', compact('subject'));
    }

    public function edit(Subject $subject)
    {
        return view('subjects.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:subjects,code,' . $subject->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'teacher_name' => 'required|string|max:255',
            'class' => 'required|string|max:50'
        ]);

        $subject->update($request->all());

        return redirect()->route('subjects.index')
            ->with('success', 'อัปเดตข้อมูลวิชาเรียบร้อยแล้ว');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();

        return redirect()->route('subjects.index')
            ->with('success', 'ลบข้อมูลวิชาเรียบร้อยแล้ว');
    }

    // ฟังก์ชันสำหรับเปลี่ยนสถานะการใช้งาน
    public function toggleStatus(Subject $subject)
    {
        $subject->update(['is_active' => !$subject->is_active]);

        $status = $subject->is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน';
        return redirect()->route('subjects.index')
            ->with('success', "เปลี่ยนสถานะวิชาเป็น {$status} เรียบร้อยแล้ว");
    }
}
