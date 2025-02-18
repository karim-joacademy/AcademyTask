<?php

namespace App\Services\TeacherService;

use App\Http\Requests\CourseRequests\AddCourseRequest;
use App\Http\Requests\StudentRequests\FireStudentRequest;
use App\Http\Requests\TeacherRequests\StoreTeacherRequest;
use App\Http\Requests\TeacherRequests\UpdateTeacherRequest;
use App\Models\Teacher;

interface ITeacherService
{
    /**
     * Retrieve all teachers.
     *
     * @return array
     */
    public function getAllTeachers(): array;

    /**
     * Create a new teacher and assign a default course.
     *
     * @param StoreTeacherRequest $request
     * @return array
     */
    public function createTeacherWithCourse(StoreTeacherRequest $request): array;

    /**
     * Retrieve details of a specific teacher.
     *
     * @param Teacher $teacher
     * @return array
     */
    public function getTeacherDetails(Teacher $teacher): array;

    /**
     * Update the details of an existing teacher.
     *
     * @param UpdateTeacherRequest $request
     * @param Teacher $teacher
     * @return array
     */
    public function updateTeacher(UpdateTeacherRequest $request, Teacher $teacher): array;

    /**
     * Delete the teacher from the system.
     *
     * @param int $id
     * @return array
     */
    public function deleteTeacher(int $id): array;

    /**
     * Fire a student from a teacher's course.
     *
     * @param FireStudentRequest $request
     * @param Teacher $teacher
     * @return array
     */
    public function fireStudentFromCourse(FireStudentRequest $request, Teacher $teacher): array;

    /**
     * Add a new course for a teacher.
     *
     * @param AddCourseRequest $request
     * @param Teacher $teacher
     * @return array
     */
    public function addCourseForTeacher(AddCourseRequest $request, Teacher $teacher): array;
}
