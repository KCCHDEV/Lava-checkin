<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $totalStudents = Student::count();
        $presentStudents = Student::where('status', 'present')->count();
        $absentStudents = Student::where('status', 'absent')->count();
        $lateStudents = Student::where('status', 'late')->count();

        return view('dashboard', compact(
            'totalStudents',
            'presentStudents', 
            'absentStudents',
            'lateStudents'
        ));
    }
}
