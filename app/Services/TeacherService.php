<?php

namespace App\Services;

use App\Http\Requests\CourseRequests\AddCourseRequest;
use App\Http\Requests\CourseRequests\StoreCourseRequest;
use App\Http\Requests\StudentRequests\FireStudentRequest;
use App\Http\Requests\TeacherRequests\StoreTeacherRequest;
use App\Http\Requests\TeacherRequests\UpdateTeacherRequest;
use App\Models\Course;
use App\Models\Student;
use App\Models\Teacher;
use Exception;
use Illuminate\Support\Facades\Log;

class TeacherService
{
    protected CourseService $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    /**
     * Get all teachers.
     *
     * @return array
     */
    public function getAllTeachers(): array
    {
        try {
            $teachers = Teacher::all();

            if ($teachers->isEmpty()) {
                return [
                    'success' => false,
                    'message' => 'No teachers found',
                ];
            }

            return [
                'success' => true,
                'teachers' => $teachers,
            ];
        } catch (Exception $e) {
            Log::error("Error fetching teachers: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'An error occurred while fetching the teachers',
            ];
        }
    }

    /**
     * Get the details of a teacher.
     *
     * @param Teacher $teacher
     * @return array
     */
    public function getTeacherDetails(Teacher $teacher): array
    {
        try {
            $teacherDetails = $teacher->load('academy', 'courses'); // Add relations if needed

            if (!$teacherDetails) {
                return [
                    'success' => false,
                    'message' => 'Teacher not found',
                ];
            }

            return [
                'success' => true,
                'teacher' => $teacherDetails,
            ];
        } catch (Exception $e) {
            Log::error("Error fetching teacher details: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'An error occurred while fetching the teacher details',
            ];
        }
    }

    /**
     * Create a teacher and a default course for them.
     *
     * @param StoreTeacherRequest $request
     * @return array
     */
    public function createTeacherWithCourse(StoreTeacherRequest $request): array
    {
        try {
            // Create the teacher
            $teacher = Teacher::query()->create($request->only(['name', 'email', 'phone', 'academy_id']));

            $courseData = [
                'title' => $request->input('course_title'),
                'description' => $request->input('course_description'),
                'teacher_id' => $teacher->id,
            ];

//            $teacher->courses()->create($courseData);
//            $teacher->courses()->createMany($courseData);

            $course = $this->courseService->createCourse(new StoreCourseRequest($courseData));

            if (!$course) {
                return [
                    'success' => false,
                    'message' => 'Failed to create default course',
                    'status' => 500,
                    'error' => 'Failed to create default course',
                ];
            }

            return [
                'success' => true,
                'teacher' => $teacher,
                'course' => $course,
                'status' => 201
            ];

        } catch (Exception $e) {
            Log::error("Error creating teacher or course: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to create teacher or course',
                'error' => $e->getMessage(),
                'status' => 500
            ];
        }
    }

    /**
     * Update the teacher's details.
     *
     * @param UpdateTeacherRequest $request
     * @param Teacher $teacher
     * @return array
     */
    public function updateTeacher(UpdateTeacherRequest $request, Teacher $teacher): array
    {
        try {
            $teacher->update([
                'email' => $request->input('email', $teacher->email),
                'phone' => $request->input('phone', $teacher->phone),
                'name' =>  $teacher->name,
                'academy_id' => $teacher->academy_id
            ]);

            return [
                'success' => true,
                'teacher' => $teacher,
                'status' => 200
            ];
        } catch (Exception $e) {
            Log::error("Error updating teacher: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to update the teacher.',
                'error' => $e->getMessage(),
                'status' => 500
            ];
        }
    }

    /**
     * Delete a teacher by their ID.
     *
     * @param int $id
     * @return array
     */
    public function deleteTeacher(int $id): array
    {
        try {
            $teacher = Teacher::query()->find($id);

            if (!$teacher) {
                return [
                    'success' => false,
                    'message' => 'Teacher not found.',
                    'status' => 404
                ];
            }

            $teacher->delete();

            return [
                'success' => true,
                'status' => 200
            ];
        } catch (Exception $e) {
            Log::error("Error deleting teacher: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to delete the teacher.',
                'error' => $e->getMessage(),
                'status' => 500
            ];
        }
    }

    /**
     * Fire a student from a course.
     *
     * @param FireStudentRequest $request
     * @param Teacher $teacher
     * @return array
     */
    public function fireStudentFromCourse(FireStudentRequest $request, Teacher $teacher): array
    {
        try {
            $course = Course::query()->find($request->input('course_id'));

            if ($course->teacher_id !== $teacher->id) {
                return [
                    'success' => false,
                    'message' => 'You do not have permission to remove students from this course.',
                    'status' => 403
                ];
            }

            $student = Student::query()->find($request->input('student_id'));

            if (!$course->students()->where('student_id', $student->id)->exists()) {
                return [
                    'success' => false,
                    'message' => 'The student is not enrolled in this course.',
                    'status' => 400
                ];
            }

            $course->students()->detach($student->id);

            return [
                'success' => true,
                'student' => $student,
                'course' => $course,
                'status' => 200
            ];
        } catch (Exception $e) {
            Log::error("Error firing student from course: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'An error occurred while removing the student from the course.',
                'error' => $e->getMessage(),
                'status' => 500
            ];
        }
    }

    /**
     * Add a new course for the teacher.
     *
     * @param AddCourseRequest $request
     * @param Teacher $teacher
     * @return array
     */
    public function addCourseForTeacher(AddCourseRequest $request, Teacher $teacher): array
    {
        // if i want to add many courses at one time use (createMany)

        try {
            if ($teacher->courses()->count() >= 3) {
                return [
                    'success' => false,
                    'message' => 'A teacher cannot have more than 3 courses.',
                    'status' => 400
                ];
            }

            $course = Course::query()->create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'teacher_id' => $teacher->id,
            ]);

            return [
                'success' => true,
                'course' => $course,
                'status' => 201
            ];
        } catch (Exception $e) {
            Log::error("Error adding course for teacher: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'An error occurred while adding the course.',
                'error' => $e->getMessage(),
                'status' => 500
            ];
        }
    }
}
