<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // สร้าง Admin (ถ้ายังไม่มี)
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => 'ผู้ดูแลระบบ',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin'
            ]);
        }

        // สร้าง Teacher (ถ้ายังไม่มี)
        if (!User::where('email', 'teacher@example.com')->exists()) {
            User::create([
                'name' => 'ครูประจำชั้น',
                'email' => 'teacher@example.com',
                'password' => Hash::make('password'),
                'role' => 'teacher'
            ]);
        }

        // สร้างนักเรียนและ User สำหรับนักเรียน (ถ้ายังไม่มี)
        $students = Student::all();
        foreach ($students as $index => $student) {
            $email = 'student' . ($index + 1) . '@example.com';
            if (!User::where('email', $email)->exists()) {
                User::create([
                    'name' => $student->name,
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'role' => 'student',
                    'student_id' => $student->id
                ]);
            }
        }
    }
}
