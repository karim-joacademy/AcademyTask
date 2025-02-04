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
            AcademySeeder::class,
            TeacherSeeder::class,
            CourseSeeder::class,
            StudentSeeder::class,
            CourseStudentSeeder::class,
        ]);
    }
}
