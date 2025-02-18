<?php

namespace App\Services\CourseService;

use App\Http\Requests\CourseRequests\StoreCourseRequest;
use App\Http\Requests\CourseRequests\UpdateCourseRequest;
use App\Models\Course;
use Exception;
use Illuminate\Support\Facades\Log;

class CourseService implements ICourseService
{
    /**
     * Retrieve all courses.
     *
     * @return array
     */
    public function getAllCourses(): array
    {
        try {
            $courses = Course::all();

            if ($courses->isEmpty()) {
                return [
                    'success' => false,
                    'message' => 'No courses found.',
                    'status' => 404,
                ];
            }

            return [
                'success' => true,
                'courses' => $courses->toArray(),
                'status' => 200,
            ];
        } catch (Exception $e) {
            Log::error("Error retrieving courses: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'An error occurred while retrieving the courses.',
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }

    /**
     * create a course
     * @param StoreCourseRequest $request
     * @return array
     */
    public function createCourse(StoreCourseRequest $request): array
    {
        try {
            $course = Course::query()->create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'teacher_id' => $request->input('teacher_id'),
            ]);

            return [
                'success' => true,
                'course' => $course->toArray(),
                'status' => 201,
            ];
        } catch (Exception $e) {
            Log::error("Error creating course: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to create course',
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }

    /**
     * get a course with a teacher
     * @param Course $course
     * @return array
     */
    public function getCourseWithTeacher(Course $course): array
    {
        try {
            $courseData = $course->load('teacher');

            if (!$courseData) {
                return [
                    'success' => false,
                    'message' => 'Course not found',
                    'status' => 404,
                ];
            }

            return [
                'success' => true,
                'course' => $courseData->toArray(),
                'status' => 200,
            ];
        } catch (Exception $e) {
            Log::error("Error retrieving course: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred while retrieving the course.',
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }

    /**
     * update a course
     * @param UpdateCourseRequest $request
     * @param int $id
     * @return array
     */
    public function updateCourse(UpdateCourseRequest $request, int $id): array
    {
        try {
            $course = Course::query()->find($id);

            if (!$course) {
                return [
                    'success' => false,
                    'message' => 'Course not found',
                    'status' => 404,
                ];
            }

            $course->update([
                'title' => $request->input('title', $course->title),
                'description' => $request->input('description', $course->description),
                'teacher_id' => $request->input('teacher_id', $course->teacher_id),
            ]);

            return [
                'success' => true,
                'course' => $course->toArray(),
                'status' => 200,
            ];
        } catch (Exception $e) {
            Log::error("Error updating course: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to update the course',
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }

    /**
     * delete a course
     * @param int $id
     * @return array
     */
    public function deleteCourse(int $id): array
    {
        try {
            $course = Course::query()->find($id);

            if (!$course) {
                return [
                    'success' => false,
                    'message' => 'Course not found',
                    'status' => 404,
                ];
            }

            $course->delete();

            return [
                'success' => true,
                'message' => 'Course deleted successfully',
                'status' => 200,
            ];
        } catch (Exception $e) {
            Log::error("Error deleting course: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to delete the course',
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }
}
