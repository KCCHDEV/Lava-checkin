<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Announcement;
use App\Models\User;
use Carbon\Carbon;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        
        if (!$admin) {
            $this->command->warn('No admin user found. Skipping announcement seeding.');
            return;
        }

        $announcements = [
            [
                'title' => 'ประกาศเปิดเรียนภาคเรียนที่ 1 ปีการศึกษา 2567',
                'content' => '<p>เรียน นักเรียนทุกคน</p><p>ขอแจ้งให้ทราบว่า ภาคเรียนที่ 1 ปีการศึกษา 2567 จะเปิดเรียนในวันที่ 16 พฤษภาคม 2567</p><p>นักเรียนทุกคนกรุณามาโรงเรียนตามเวลาปกติ และแต่งกายให้ถูกต้องตามระเบียบของโรงเรียน</p><p>หากมีข้อสงสัยเพิ่มเติม กรุณาติดต่อครูประจำชั้นหรือฝ่ายวิชาการ</p>',
                'type' => 'important',
                'priority' => 'high',
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(5),
                'expires_at' => Carbon::now()->addDays(30),
                'created_by' => $admin->id,
            ],
            [
                'title' => 'กิจกรรมวันวิทยาศาสตร์ 2567',
                'content' => '<p>โรงเรียนจะจัดกิจกรรมวันวิทยาศาสตร์ในวันที่ 18 สิงหาคม 2567</p><p>กิจกรรมประกอบด้วย:</p><ul><li>การแข่งขันโครงงานวิทยาศาสตร์</li><li>การแสดงผลงานนักเรียน</li><li>การบรรยายพิเศษจากวิทยากร</li></ul><p>นักเรียนที่สนใจเข้าร่วมกิจกรรม กรุณาลงทะเบียนที่ห้องวิทยาศาสตร์</p>',
                'type' => 'event',
                'priority' => 'normal',
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(3),
                'expires_at' => Carbon::now()->addDays(60),
                'created_by' => $admin->id,
            ],
            [
                'title' => 'ประกาศผลการสอบกลางภาค',
                'content' => '<p>ประกาศผลการสอบกลางภาค ภาคเรียนที่ 1 ปีการศึกษา 2567</p><p>นักเรียนสามารถดูผลการสอบได้ที่ห้องทะเบียน ตั้งแต่วันที่ 20 กันยายน 2567 เป็นต้นไป</p><p>หากมีข้อสงสัยเกี่ยวกับผลการสอบ กรุณาติดต่อครูประจำวิชา</p>',
                'type' => 'academic',
                'priority' => 'normal',
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(1),
                'expires_at' => Carbon::now()->addDays(15),
                'created_by' => $admin->id,
            ],
            [
                'title' => 'การปรับปรุงระบบอินเทอร์เน็ต',
                'content' => '<p>ขอแจ้งให้ทราบว่า โรงเรียนจะทำการปรับปรุงระบบอินเทอร์เน็ตในวันที่ 25 กันยายน 2567</p><p>การปรับปรุงจะใช้เวลาประมาณ 4-6 ชั่วโมง</p><p>ในช่วงเวลาดังกล่าว อาจมีปัญหาในการใช้งานอินเทอร์เน็ต</p><p>ขออภัยในความไม่สะดวก</p>',
                'type' => 'general',
                'priority' => 'low',
                'status' => 'published',
                'published_at' => Carbon::now(),
                'expires_at' => Carbon::now()->addDays(7),
                'created_by' => $admin->id,
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::updateOrCreate(
                ['title' => $announcement['title']],
                $announcement
            );
        }

        $this->command->info('Announcements seeded successfully!');
    }
}
