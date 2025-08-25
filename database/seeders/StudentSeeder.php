<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = [
            [
                'name' => 'สมชาย ใจดี',
                'phone' => '0812345678',
                'address' => '123 ถนนสุขุมวิท แขวงคลองเตย เขตคลองเตย กรุงเทพฯ 10110',
                'student_id' => '6401001',
                'class' => 'ม.4/1'
            ],
            [
                'name' => 'สมหญิง รักเรียน',
                'phone' => '0823456789',
                'address' => '456 ถนนรัชดาภิเษก แขวงดินแดง เขตดินแดง กรุงเทพฯ 10400',
                'student_id' => '6401002',
                'class' => 'ม.4/1'
            ],
            [
                'name' => 'วิชัย มุ่งมั่น',
                'phone' => '0834567890',
                'address' => '789 ถนนลาดพร้าว แขวงจันทรเกษม เขตจตุจักร กรุงเทพฯ 10900',
                'student_id' => '6401003',
                'class' => 'ม.4/2'
            ],
            [
                'name' => 'นารี สวยงาม',
                'phone' => '0845678901',
                'address' => '321 ถนนวิภาวดีรังสิต แขวงดินแดง เขตดินแดง กรุงเทพฯ 10400',
                'student_id' => '6401004',
                'class' => 'ม.4/2'
            ],
            [
                'name' => 'ธนวัฒน์ เก่งกล้า',
                'phone' => '0856789012',
                'address' => '654 ถนนพระราม 9 แขวงห้วยขวาง เขตห้วยขวาง กรุงเทพฯ 10310',
                'student_id' => '6401005',
                'class' => 'ม.4/3'
            ],
            [
                'name' => 'กัลยา น่ารัก',
                'phone' => '0867890123',
                'address' => '987 ถนนอโศก แขวงคลองเตย เขตคลองเตย กรุงเทพฯ 10110',
                'student_id' => '6401006',
                'class' => 'ม.4/3'
            ],
            [
                'name' => 'อภิชาติ ดีงาม',
                'phone' => '0878901234',
                'address' => '147 ถนนสุขุมวิท แขวงคลองเตย เขตคลองเตย กรุงเทพฯ 10110',
                'student_id' => '6401007',
                'class' => 'ม.5/1'
            ],
            [
                'name' => 'รัตนา สดใส',
                'phone' => '0889012345',
                'address' => '258 ถนนรัชดาภิเษก แขวงดินแดง เขตดินแดง กรุงเทพฯ 10400',
                'student_id' => '6401008',
                'class' => 'ม.5/1'
            ],
            [
                'name' => 'สุทธิพงษ์ ใจเย็น',
                'phone' => '0890123456',
                'address' => '369 ถนนลาดพร้าว แขวงจันทรเกษม เขตจตุจักร กรุงเทพฯ 10900',
                'student_id' => '6401009',
                'class' => 'ม.5/2'
            ],
            [
                'name' => 'ปิยะดา สวยงาม',
                'phone' => '0901234567',
                'address' => '741 ถนนวิภาวดีรังสิต แขวงดินแดง เขตดินแดง กรุงเทพฯ 10400',
                'student_id' => '6401010',
                'class' => 'ม.5/2'
            ]
        ];

        foreach ($students as $student) {
            if (!Student::where('student_id', $student['student_id'])->exists()) {
                Student::create($student);
            }
        }
    }
}
