<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            [
                'code' => 'ค21101',
                'name' => 'คณิตศาสตร์พื้นฐาน 1',
                'description' => 'วิชาคณิตศาสตร์พื้นฐานสำหรับนักเรียนชั้นมัธยมศึกษาปีที่ 1',
                'teacher_name' => 'ครูสมชาย ใจดี',
                'class' => 'ม.1/1',
                'is_active' => true
            ],
            [
                'code' => 'ว21101',
                'name' => 'วิทยาศาสตร์พื้นฐาน 1',
                'description' => 'วิชาวิทยาศาสตร์พื้นฐานสำหรับนักเรียนชั้นมัธยมศึกษาปีที่ 1',
                'teacher_name' => 'ครูสมหญิง รักเรียน',
                'class' => 'ม.1/1',
                'is_active' => true
            ],
            [
                'code' => 'ท21101',
                'name' => 'ภาษาไทยพื้นฐาน 1',
                'description' => 'วิชาภาษาไทยพื้นฐานสำหรับนักเรียนชั้นมัธยมศึกษาปีที่ 1',
                'teacher_name' => 'ครูวิชัย มุ่งมั่น',
                'class' => 'ม.1/1',
                'is_active' => true
            ],
            [
                'code' => 'อ21101',
                'name' => 'ภาษาอังกฤษพื้นฐาน 1',
                'description' => 'วิชาภาษาอังกฤษพื้นฐานสำหรับนักเรียนชั้นมัธยมศึกษาปีที่ 1',
                'teacher_name' => 'ครูนารี สวยงาม',
                'class' => 'ม.1/1',
                'is_active' => true
            ],
            [
                'code' => 'ส21101',
                'name' => 'สังคมศึกษาพื้นฐาน 1',
                'description' => 'วิชาสังคมศึกษาพื้นฐานสำหรับนักเรียนชั้นมัธยมศึกษาปีที่ 1',
                'teacher_name' => 'ครูธนวัฒน์ เก่งกล้า',
                'class' => 'ม.1/1',
                'is_active' => true
            ],
            [
                'code' => 'ค22101',
                'name' => 'คณิตศาสตร์พื้นฐาน 2',
                'description' => 'วิชาคณิตศาสตร์พื้นฐานสำหรับนักเรียนชั้นมัธยมศึกษาปีที่ 2',
                'teacher_name' => 'ครูกัลยา น่ารัก',
                'class' => 'ม.2/1',
                'is_active' => true
            ],
            [
                'code' => 'ว22101',
                'name' => 'วิทยาศาสตร์พื้นฐาน 2',
                'description' => 'วิชาวิทยาศาสตร์พื้นฐานสำหรับนักเรียนชั้นมัธยมศึกษาปีที่ 2',
                'teacher_name' => 'ครูอภิชาติ ดีงาม',
                'class' => 'ม.2/1',
                'is_active' => true
            ],
            [
                'code' => 'ท22101',
                'name' => 'ภาษาไทยพื้นฐาน 2',
                'description' => 'วิชาภาษาไทยพื้นฐานสำหรับนักเรียนชั้นมัธยมศึกษาปีที่ 2',
                'teacher_name' => 'ครูรัตนา สดใส',
                'class' => 'ม.2/1',
                'is_active' => true
            ],
            [
                'code' => 'อ22101',
                'name' => 'ภาษาอังกฤษพื้นฐาน 2',
                'description' => 'วิชาภาษาอังกฤษพื้นฐานสำหรับนักเรียนชั้นมัธยมศึกษาปีที่ 2',
                'teacher_name' => 'ครูสุทธิพงษ์ ใจเย็น',
                'class' => 'ม.2/1',
                'is_active' => true
            ],
            [
                'code' => 'ส22101',
                'name' => 'สังคมศึกษาพื้นฐาน 2',
                'description' => 'วิชาสังคมศึกษาพื้นฐานสำหรับนักเรียนชั้นมัธยมศึกษาปีที่ 2',
                'teacher_name' => 'ครูปิยะดา สวยงาม',
                'class' => 'ม.2/1',
                'is_active' => true
            ]
        ];

        foreach ($subjects as $subject) {
            if (!Subject::where('code', $subject['code'])->exists()) {
                Subject::create($subject);
            }
        }
    }
}
