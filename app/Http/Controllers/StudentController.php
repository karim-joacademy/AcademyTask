<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Course;
use App\Models\Student;
use App\Services\StudentService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    protected StudentService $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    /**
     * Retrieve all students.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $result = $this->studentService->getAllStudents();

        if (!$result['success']) {
            return response()->json(["message" => $result['message']], 404);
        }

        return response()->json($result['data']);
    }

    /**
     * Store a new student.
     *
     * @param StoreStudentRequest $request
     * @return JsonResponse
     */
    public function store(StoreStudentRequest $request): JsonResponse
    {
        $result = $this->studentService->createStudent($request);

        if (!$result['success']) {
            return response()->json(["message" => $result['message']], 500);
        }

        return response()->json([
            "message" => "Student created successfully",
            "student" => $result['data']
        ], 201);
    }

    /**
     * Show details of a specific student.
     *
     * @param Student $student
     * @return JsonResponse
     */
    public function show(Student $student): JsonResponse
    {
        $result = $this->studentService->getStudentWithDetails($student);

        if (!$result['success']) {
            return response()->json(["message" => $result['message']], 404);
        }

        return response()->json($result['data']);
    }

    /**
     * Update the information of an existing student.
     *
     * @param UpdateStudentRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateStudentRequest $request, int $id): JsonResponse
    {
        $result = $this->studentService->updateStudent($request, $id);

        if (!$result['success']) {
            return response()->json(["message" => $result['message']], 500);
        }

        return response()->json($result['data'], 200);
    }

    /**
     * Remove the specified student.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $result = $this->studentService->deleteStudent($id);

        if (!$result['success']) {
            return response()->json(["message" => $result['message']], 404);
        }

        return response()->json(["message" => "Student deleted successfully"], 200);
    }

    /**
     * Enroll a student in a course.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function enroll(Request $request): JsonResponse
    {
        $result = $this->studentService->enrollStudentInCourse($request);

        if (!$result['success']) {
            return response()->json(["message" => $result['message']], $result['status']);
        }

        return response()->json(["message" => "Student enrolled successfully"], 200);
    }

    /**
     * Drop a student from a course.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function drop(Request $request): JsonResponse
    {
        $result = $this->studentService->dropStudentFromCourse($request);

        if (!$result['success']) {
            return response()->json(["message" => $result['message']], $result['status']);
        }

        return response()->json(["message" => "Student dropped the course successfully"], 200);
    }

}
