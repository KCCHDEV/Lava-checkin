<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::user()->canView()) {
            abort(403, 'ไม่มีสิทธิ์ในการดูข้อมูล');
        }

        $students = Student::latest()->paginate(10);
        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user()->canCreate()) {
            abort(403, 'ไม่มีสิทธิ์ในการเพิ่มข้อมูล');
        }

        return view('students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->canCreate()) {
            abort(403, 'ไม่มีสิทธิ์ในการเพิ่มข้อมูล');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'status' => 'required|in:present,absent,late',
        ]);

        Student::create($request->all());

        return redirect()->route('students.index')
                        ->with('success', 'เพิ่มข้อมูลนักเรียนเรียบร้อยแล้ว');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        if (!Auth::user()->canView()) {
            abort(403, 'ไม่มีสิทธิ์ในการดูข้อมูล');
        }

        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        if (!Auth::user()->canEdit()) {
            abort(403, 'ไม่มีสิทธิ์ในการแก้ไขข้อมูล');
        }

        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        if (!Auth::user()->canEdit()) {
            abort(403, 'ไม่มีสิทธิ์ในการแก้ไขข้อมูล');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'status' => 'required|in:present,absent,late',
        ]);

        $student->update($request->all());

        return redirect()->route('students.index')
                        ->with('success', 'แก้ไขข้อมูลนักเรียนเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        if (!Auth::user()->canDelete()) {
            abort(403, 'ไม่มีสิทธิ์ในการลบข้อมูล');
        }

        $student->delete();

        return redirect()->route('students.index')
                        ->with('success', 'ลบข้อมูลนักเรียนเรียบร้อยแล้ว');
    }
}
