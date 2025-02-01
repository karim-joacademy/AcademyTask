<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCourseRequest;
use App\Http\Requests\FireStudentRequest;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Models\Teacher;
use App\Services\TeacherService;
use Illuminate\Http\JsonResponse;

class TeacherController extends Controller
{
    protected TeacherService $teacherService;

    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
    }

    /**
     * Retrieve all teachers.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $result = $this->teacherService->getAllTeachers();

        if (!$result['success']) {
            return response()->json(["message" => $result['message']], 404);
        }

        return response()->json($result['teachers'], 200);
    }

    /**
     * Store a new teacher and create a default course for them.
     *
     * @param StoreTeacherRequest $request
     * @return JsonResponse
     */
    public function store(StoreTeacherRequest $request): JsonResponse
    {
        $result = $this->teacherService->createTeacherWithCourse($request);

        if (!$result['success']) {
            return response()->json([
                'message' => $result['message'],
                'error' => $result['error'] ?? null
            ], $result['status']);
        }

        return response()->json([
            'message' => 'Teacher created successfully with a course',
            'teacher' => $result['teacher'],
            'course' => $result['course'],
        ], 201);
    }

    /**
     * Retrieve details of a specific teacher.
     *
     * @param Teacher $teacher
     * @return JsonResponse
     */
    public function show(Teacher $teacher): JsonResponse
    {
        $result = $this->teacherService->getTeacherDetails($teacher);

        if (!$result['success']) {
            return response()->json(["message" => $result['message']], 404);
        }

        return response()->json($result['teacher'], 200);
    }

    /**
     * Update the details of an existing teacher.
     *
     * @param UpdateTeacherRequest $request
     * @param Teacher $teacher
     * @return JsonResponse
     */
    public function update(UpdateTeacherRequest $request, Teacher $teacher): JsonResponse
    {
        $result = $this->teacherService->updateTeacher($request, $teacher);

        if (!$result['success']) {
            return response()->json([
                'message' => $result['message'],
                'error' => $result['error'] ?? null
            ], $result['status']);
        }

        return response()->json($result['teacher'], 200);
    }

    /**
     * Delete the teacher from the system.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $result = $this->teacherService->deleteTeacher($id);

        if (!$result['success']) {
            return response()->json([
                'message' => $result['message'],
                'error' => $result['error'] ?? null
            ], $result['status']);
        }

        return response()->json(['message' => 'Teacher deleted successfully.'], 200);
    }

    /**
     * Fire a student from a course.
     *
     * @param FireStudentRequest $request
     * @param Teacher $teacher
     * @return JsonResponse
     */
    public function fireStudent(FireStudentRequest $request, Teacher $teacher): JsonResponse
    {
        $result = $this->teacherService->fireStudentFromCourse($request, $teacher);

        if (!$result['success']) {
            return response()->json([
                'message' => $result['message'],
                'error' => $result['error'] ?? null
            ], $result['status']);
        }

        return response()->json([
            'message' => 'Student has been removed from the course successfully.',
            'student' => $result['student'],
            'course' => $result['course']
        ], 200);
    }

    /**
     * Add a new course for a teacher.
     *
     * @param AddCourseRequest $request
     * @param Teacher $teacher
     * @return JsonResponse
     */
    public function addCourse(AddCourseRequest $request, Teacher $teacher): JsonResponse
    {
        $result = $this->teacherService->addCourseForTeacher($request, $teacher);

        if (!$result['success']) {
            return response()->json([
                'message' => $result['message'],
                'error' => $result['error'] ?? null
            ], $result['status']);
        }

        return response()->json([
            'message' => 'Course added successfully.',
            'course' => $result['course']
        ], 201);
    }
}
