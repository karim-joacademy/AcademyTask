<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Database\Seeder;

class CourseStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = Course::all();
        $students = Student::all();

        $students->each(function ($student) use ($courses) {
            $student->courses()->attach(
                $courses->random(rand(1, 5))
                        ->pluck('id')
                        ->toArray()
                );
        });
    }
}
