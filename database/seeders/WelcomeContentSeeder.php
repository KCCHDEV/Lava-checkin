<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WelcomeContent;

class WelcomeContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contents = [
            // Hero Section
            [
                'section' => 'hero_title',
                'content' => 'วิทยาลัยเทคนิคลำพูน',
                'type' => 'text',
                'is_active' => true
            ],
            [
                'section' => 'hero_subtitle',
                'content' => 'สถาบันการศึกษาระดับอาชีวศึกษาที่มุ่งมั่นพัฒนานักเรียนให้เป็นบุคลากรที่มีคุณภาพ พร้อมรับมือกับความท้าทายในอนาคต',
                'type' => 'text',
                'is_active' => true
            ],
            [
                'section' => 'banner_image',
                'content' => 'https://images.unsplash.com/photo-1523050854058-8df90110c9e1?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80',
                'type' => 'image',
                'is_active' => true
            ],
            
            // School Info
            [
                'section' => 'school_name',
                'content' => 'วิทยาลัยเทคนิคลำพูน',
                'type' => 'text',
                'is_active' => true
            ],
            [
                'section' => 'school_phone',
                'content' => '053-511234',
                'type' => 'text',
                'is_active' => true
            ],
            [
                'section' => 'school_email',
                'content' => 'info@lamphuntech.ac.th',
                'type' => 'text',
                'is_active' => true
            ],
            
            // Features
            [
                'section' => 'feature_1_title',
                'content' => 'ครูผู้สอนคุณภาพ',
                'type' => 'text',
                'is_active' => true
            ],
            [
                'section' => 'feature_1_description',
                'content' => 'ครูผู้สอนที่มีประสบการณ์และความรู้ความเชี่ยวชาญ พร้อมถ่ายทอดความรู้ให้นักเรียนอย่างเต็มที่',
                'type' => 'text',
                'is_active' => true
            ],
            [
                'section' => 'feature_2_title',
                'content' => 'อุปกรณ์ทันสมัย',
                'type' => 'text',
                'is_active' => true
            ],
            [
                'section' => 'feature_2_description',
                'content' => 'ห้องปฏิบัติการและอุปกรณ์การเรียนการสอนที่ทันสมัย เพื่อให้นักเรียนได้เรียนรู้จากประสบการณ์จริง',
                'type' => 'text',
                'is_active' => true
            ],
            [
                'section' => 'feature_3_title',
                'content' => 'ความร่วมมือกับภาคอุตสาหกรรม',
                'type' => 'text',
                'is_active' => true
            ],
            [
                'section' => 'feature_3_description',
                'content' => 'มีความร่วมมือกับบริษัทและโรงงานต่างๆ เพื่อให้นักเรียนได้ฝึกงานและมีโอกาสได้งานทำ',
                'type' => 'text',
                'is_active' => true
            ],
            
            // Programs
            [
                'section' => 'program_1_title',
                'content' => 'ช่างยนต์',
                'type' => 'text',
                'is_active' => true
            ],
            [
                'section' => 'program_1_description',
                'content' => 'เรียนรู้เกี่ยวกับการซ่อมบำรุงและดูแลรักษารถยนต์ พร้อมทักษะการใช้งานเครื่องมือและอุปกรณ์ต่างๆ',
                'type' => 'text',
                'is_active' => true
            ],
            [
                'section' => 'program_2_title',
                'content' => 'ช่างไฟฟ้า',
                'type' => 'text',
                'is_active' => true
            ],
            [
                'section' => 'program_2_description',
                'content' => 'ศึกษาเกี่ยวกับระบบไฟฟ้า การติดตั้งและซ่อมบำรุง อุปกรณ์ไฟฟ้าต่างๆ พร้อมความปลอดภัยในการทำงาน',
                'type' => 'text',
                'is_active' => true
            ],
            [
                'section' => 'program_3_title',
                'content' => 'คอมพิวเตอร์',
                'type' => 'text',
                'is_active' => true
            ],
            [
                'section' => 'program_3_description',
                'content' => 'เรียนรู้การเขียนโปรแกรม การออกแบบเว็บไซต์ และการจัดการระบบคอมพิวเตอร์',
                'type' => 'text',
                'is_active' => true
            ],
            [
                'section' => 'program_4_title',
                'content' => 'ช่างกล',
                'type' => 'text',
                'is_active' => true
            ],
            [
                'section' => 'program_4_description',
                'content' => 'ศึกษาเกี่ยวกับเครื่องจักรกล การบำรุงรักษา และการซ่อมแซมเครื่องมือเครื่องจักร',
                'type' => 'text',
                'is_active' => true
            ],
            [
                'section' => 'program_5_title',
                'content' => 'ช่างก่อสร้าง',
                'type' => 'text',
                'is_active' => true
            ],
            [
                'section' => 'program_5_description',
                'content' => 'เรียนรู้การก่อสร้าง การออกแบบ และการคำนวณ วัสดุก่อสร้างต่างๆ',
                'type' => 'text',
                'is_active' => true
            ],
            [
                'section' => 'program_6_title',
                'content' => 'ช่างซ่อมรถยนต์',
                'type' => 'text',
                'is_active' => true
            ],
            [
                'section' => 'program_6_description',
                'content' => 'ศึกษาเกี่ยวกับระบบต่างๆ ของรถยนต์ การวินิจฉัยและแก้ไขปัญหา',
                'type' => 'text',
                'is_active' => true
            ],
            
            // News
            [
                'section' => 'news_1_title',
                'content' => 'เปิดรับสมัครนักเรียนใหม่ ปีการศึกษา 2567',
                'type' => 'text',
                'is_active' => true
            ],
            [
                'section' => 'news_1_description',
                'content' => 'วิทยาลัยเทคนิคลำพูน เปิดรับสมัครนักเรียนใหม่ ในระดับประกาศนียบัตรวิชาชีพ (ปวช.) และประกาศนียบัตรวิชาชีพชั้นสูง (ปวส.)',
                'type' => 'text',
                'is_active' => true
            ],
            [
                'section' => 'news_1_date',
                'content' => '15 มกราคม 2024',
                'type' => 'text',
                'is_active' => true
            ],
            [
                'section' => 'news_2_title',
                'content' => 'กิจกรรมวันเด็กแห่งชาติ 2567',
                'type' => 'text',
                'is_active' => true
            ],
            [
                'section' => 'news_2_description',
                'content' => 'วิทยาลัยจัดกิจกรรมวันเด็กแห่งชาติ เพื่อส่งเสริมการเรียนรู้และพัฒนาทักษะของเด็กและเยาวชน',
                'type' => 'text',
                'is_active' => true
            ],
            [
                'section' => 'news_2_date',
                'content' => '10 มกราคม 2024',
                'type' => 'text',
                'is_active' => true
            ],
            [
                'section' => 'news_3_title',
                'content' => 'การแข่งขันทักษะวิชาชีพ ระดับจังหวัด',
                'type' => 'text',
                'is_active' => true
            ],
            [
                'section' => 'news_3_description',
                'content' => 'นักเรียนของวิทยาลัยเข้าร่วมการแข่งขันทักษะวิชาชีพ และได้รับรางวัลชนะเลิศในหลายสาขา',
                'type' => 'text',
                'is_active' => true
            ],
            [
                'section' => 'news_3_date',
                'content' => '5 มกราคม 2024',
                'type' => 'text',
                'is_active' => true
            ]
        ];

        foreach ($contents as $content) {
            WelcomeContent::updateOrCreate(
                ['section' => $content['section']],
                $content
            );
        }
    }
}
