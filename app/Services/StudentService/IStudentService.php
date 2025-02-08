<?php

namespace App\Services\StudentService;

use App\Http\Requests\StudentRequests\DropStudentRequest;
use App\Http\Requests\StudentRequests\EnrollStudentRequest;
use App\Http\Requests\StudentRequests\StoreStudentRequest;
use App\Http\Requests\StudentRequests\UpdateStudentRequest;
use App\Models\Student;
use Illuminate\Http\Request;

interface IStudentService
{
    /**
     * Retrieve all students.
     *
     * @return array
     */
    public function getAllStudents(): array;

    /**
     * Retrieve student details along with related data.
     *
     * @param int $studentId
     * @return array
     */
    public function getStudentWithDetails(Student $student): array;

    /**
     * Create a new student.
     *
     * @param StoreStudentRequest $request
     * @return array
     */
    public function createStudent(StoreStudentRequest $request): array;

    /**
     * Update the student information.
     *
     * @param UpdateStudentRequest $request
     * @param int $id
     * @return array
     */
    public function updateStudent(UpdateStudentRequest $request, int $id): array;

    /**
     * Delete a student from the database.
     *
     * @param int $id
     * @return array
     */
    public function deleteStudent(int $id): array;

    /**
     * Enroll a student in a course.
     *
     * @param Request $request
     * @return array
     */
    public function enrollStudentInCourse(EnrollStudentRequest $request): array;

    /**
     * Drop a student from a course.
     *
     * @param Request $request
     * @return array
     */
    public function dropStudentFromCourse(DropStudentRequest $request): array;
}
