<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;
use App\Services\CourseService;
use Exception;
use Illuminate\Http\JsonResponse;

class CourseController extends Controller
{
    protected CourseService $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    /**
     * Retrieve all courses.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $result = $this->courseService->getAllCourses();

        if (!$result['success']) {
            return response()->json([
                'message' => $result['message'],
                'error' => $result['error'] ?? null,
            ], $result['status']);
        }

        return response()->json($result['courses'], 200);
    }

    /**
     * create a course
     * @param StoreCourseRequest $request
     * @return JsonResponse
     */
    public function store(StoreCourseRequest $request): JsonResponse
    {
        $result = $this->courseService->createCourse($request);

        if (!$result['success']) {
            return response()->json([
                'message' => $result['message'],
                'error' => $result['error'] ?? null,
            ], $result['status']);
        }

        return response()->json($result['course'], 201);
    }

    /**
     * retrieve a specific course with a teacher
     * @param Course $course
     * @return JsonResponse
     */
    public function show(Course $course): JsonResponse
    {
        $result = $this->courseService->getCourseWithTeacher($course);

        if (!$result['success']) {
            return response()->json([
                'message' => $result['message'],
                'error' => $result['error'] ?? null,
            ], $result['status']);
        }

        return response()->json($result['course']);
    }

    /**
     * update a course
     * @param UpdateCourseRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateCourseRequest $request, int $id): JsonResponse
    {
        $result = $this->courseService->updateCourse($request, $id);

        if (!$result['success']) {
            return response()->json([
                'message' => $result['message'],
                'error' => $result['error'] ?? null,
            ], $result['status']);
        }

        return response()->json($result['course'], 200);
    }

    /**
     * delete a course
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $result = $this->courseService->deleteCourse($id);

        if (!$result['success']) {
            return response()->json([
                'message' => $result['message'],
                'error' => $result['error'] ?? null,
            ], $result['status']);
        }

        return response()->json(['message' => 'Course deleted successfully'], 200);
    }
}

