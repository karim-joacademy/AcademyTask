<?php

namespace App\Services\StudentService;

use App\Http\Requests\StudentRequests\DropStudentRequest;
use App\Http\Requests\StudentRequests\EnrollStudentRequest;
use App\Http\Requests\StudentRequests\StoreStudentRequest;
use App\Http\Requests\StudentRequests\UpdateStudentRequest;
use App\Models\Course;
use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StudentService implements IStudentService
{
    /**
     * Retrieve all students.
     *
     * @return array
     */
    public function getAllStudents(): array
    {
        try {
            $students = Student::query()->get();

            if ($students->isEmpty()) {
                return [
                    'success' => false,
                    'message' => 'No students found',
                    'data' => [],
                ];
            }

            return [
                'success' => true,
                'message' => 'Students retrieved successfully',
                'data' => $students,
            ];
        } catch (Exception $e) {
            Log::error("Error retrieving students: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'An error occurred while retrieving the students.',
                'data' => [],
            ];
        }
    }


    /**
     * Retrieve student details along with related data.
     *
     * @param Student $student
     * @return array
     */
    public function getStudentWithDetails(Student $student): array
    {
        try {
            $studentData = $student->load('courses', 'academy');

            return [
                'success' => true,
                'message' => 'Student data retrieved successfully',
                'data' => $studentData,
            ];
        } catch (Exception $e) {
            Log::error("Error retrieving student details: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to retrieve student details',
                'data' => [],
            ];
        }
    }


    /**
     * Create a new student.
     *
     * @param StoreStudentRequest $request
     * @return array
     */
    public function createStudent(StoreStudentRequest $request): array
    {
        try {
            $student = Student::query()->create($request->validated());

            return [
                'success' => true,
                'message' => 'Student created successfully',
                'data' => $student,
            ];
        } catch (Exception $e) {
            Log::error("Error creating student: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to create student',
                'data' => [],
            ];
        }
    }

    /**
     * Update the student information.
     *
     * @param UpdateStudentRequest $request
     * @param int $id
     * @return array
     */
    public function updateStudent(UpdateStudentRequest $request, int $id): array
    {
        try {
            $student = Student::query()->findOrFail($id);

            $student->update($request->validated());

            return [
                'success' => true,
                'message' => 'Student updated successfully',
                'data' => $student,
            ];
        } catch (Exception $e) {
            Log::error("Error updating student: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to update student',
                'data' => [],
            ];
        }
    }

    /**
     * Delete a student from the database.
     *
     * @param int $id
     * @return array
     */
    public function deleteStudent(int $id): array
    {
        try {
            $student = Student::query()->find($id);

            if (!$student) {
                return [
                    'success' => false,
                    'message' => 'Student not found or could not be deleted',
                ];
            }

            $student->delete();

            return [
                'success' => true,
                'message' => 'Student deleted successfully',
            ];
        } catch (Exception $e) {
            Log::error("Error deleting student: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'An error occurred while deleting the student',
            ];
        }
    }

    /**
     * Enroll a student in a course.
     *
     * @param Request $request
     * @return array
     */
    public function enrollStudentInCourse(EnrollStudentRequest $request): array
    {
        try {
            $studentCourse = $request->validated();

            $student = Student::query()->find($studentCourse['student_id']);
            $course = Course::query()->find($studentCourse['course_id']);

            if ($student->courses()->where('course_id', $course->id)->exists()) {
                return [
                    'success' => false,
                    'message' => 'Student is already enrolled in this course',
                    'status' => 400
                ];
            }

            if ($student->courses()->count() >= 5) {
                return [
                    'success' => false,
                    'message' => 'Student has already enrolled in the maximum of 5 courses',
                    'status' => 400
                ];
            }

            $student->courses()->attach($course->id);

            return [
                'success' => true,
                'message' => 'Student enrolled successfully',
                'status' => 200
            ];

        }
        catch (Exception $e) {
            Log::error("Error enrolling student: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred while enrolling the student',
                'status' => 500
            ];
        }
    }

    /**
     * Drop a student from a course.
     *
     * @param Request $request
     * @return array
     */
    public function dropStudentFromCourse(DropStudentRequest $request): array
    {
        try {
            $studentCourse = $request->validated();

            $student = Student::query()->find($studentCourse['student_id']);
            $course = Course::query()->find($studentCourse['course_id']);

            if (!$student->courses()->where('course_id', $course->id)->exists()) {
                return [
                    'success' => false,
                    'message' => 'Student is not enrolled in this course',
                    'status' => 400
                ];
            }

            $courseCount = $student->courses()->count();

            if ($courseCount == 1) {
                return [
                    'success' => false,
                    'message' => 'Student cannot drop their only course',
                    'status' => 400
                ];
            }

            $maxDropCount = floor($courseCount / 2);

            if (($courseCount - 1) <= $maxDropCount) {
                return [
                    'success' => false,
                    'message' => 'Student cannot drop more than the allowed number of courses',
                    'status' => 400
                ];
            }

            $student->courses()->detach($course->id);

            return [
                'success' => true,
                'message' => 'Student dropped the course successfully',
                'status' => 200
            ];

        } catch (Exception $e) {
            Log::error("Error dropping student from course: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred while dropping the course',
                'status' => 500
            ];
        }
    }
}
