<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->seedRolesAndPermissions();

        $this->call([
            AcademySeeder::class,
            TeacherSeeder::class,
            CourseSeeder::class,
            StudentSeeder::class,
            CourseStudentSeeder::class,
        ]);
    }

    /**
     * Seed roles and permissions.
     */
    private function seedRolesAndPermissions(): void
    {
        $academy = Role::query()->firstOrCreate(['name' => 'academy']);
        $teacher = Role::query()->firstOrCreate(['name' => 'teacher']);
        $student = Role::query()->firstOrCreate(['name' => 'student']);

        $permissions = [
            'delete course',
            'delete teacher',
            'fire student',
            'enroll to course',
            'drop course',
            'view own course',
            'view all courses',
            'view students',
            'view course students'
        ];

        foreach ($permissions as $permission) {
            Permission::query()->firstOrCreate(['name' => $permission]);
        }

        $academy->givePermissionTo(['view students', 'delete course', 'delete teacher', 'fire student', 'view own course', 'view all courses']);
        $teacher->givePermissionTo(['view course students', 'fire student', 'view own course']);
        $student->givePermissionTo(['enroll to course', 'drop course', 'view own course']);
    }
}
