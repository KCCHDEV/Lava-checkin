<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            StudentSeeder::class,
            SubjectSeeder::class,
            WelcomeContentSeeder::class,
            SchoolSettingSeeder::class,
            AnnouncementSeeder::class,
            BlogSeeder::class,
        ]);
    }
}
