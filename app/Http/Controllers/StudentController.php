<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Course;
use App\Models\Student;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : JsonResponse
    {
        try {
            $students = Student::all();

            if ($students->isEmpty()) {
                return response()->json(["message" => "No students"], 404);
            }
            return response()->json($students);
        }
        catch (Exception $e) {
            return response()->json([
                "message" => "An error occurred while retrieving students",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request): JsonResponse
    {
        try {
            $student = Student::query()->create($request->validated());

            return response()->json([
                'message' => 'Student created successfully',
                'student' => $student
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to create student',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student) : JsonResponse
    {
        try {
            $student->load('courses', 'academy');
            return response()->json($student);
        }
        catch (Exception $e) {
            return response()->json([
                "message" => "An error occurred while retrieving the student details",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, int $id): JsonResponse
    {
        try {
            $student = Student::query()->findOrFail($id);
            $name = $student->name;
            $academy_id = $student->academy_id;

            $student->update([
                'name' => $name,
                'academy_id' => $academy_id,
                'email' => $request->input('email', $student->email),
                'phone' => $request->input('phone', $student->phone),
            ]);
            return response()->json($student, 200);
        }
        catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to update the student.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id) : JsonResponse
    {
        try {
            $student = Student::query()->find($id);

            if (!$student) {
                return response()->json([
                    "message" => "Student not found"
                ], 404);
            }
            $student->delete();

            return response()->json([
                "message" => "Student deleted successfully"
            ], 200);
        }
        catch (Exception $e) {
            return response()->json([
                "message" => "An error occurred while deleting the student",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function enroll(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'student_id' => 'required|integer|exists:students,id',
                'course_id' => 'required|integer|exists:courses,id',
            ]);

            $student = Student::query()->find($validated['student_id']);
            $course = Course::query()->find($validated['course_id']);

            if ($student->courses()->where('course_id', $course->id)->exists()) {
                return response()->json(["message" => "Student is already enrolled in this course"], 400);
            }

            if ($student->courses()->count() >= 5) {
                return response()->json(["message" => "Student has already enrolled in the maximum of 5 courses"], 400);
            }

            $student->courses()->attach($course->id);

            return response()->json(["message" => "Student enrolled successfully"], 200);

        } catch (Exception $e) {
            return response()->json([
                "message" => "An error occurred while enrolling the student",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function drop(Request $request): JsonResponse
    {
        try {
            // Validate the request body
            $validated = $request->validate([
                'student_id' => 'required|integer|exists:students,id',
                'course_id' => 'required|integer|exists:courses,id',
            ]);

            $student = Student::query()->find($validated['student_id']);
            $course = Course::query()->find($validated['course_id']);

            if (!$student->courses()->where('course_id', $course->id)->exists()) {
                return response()->json(["message" => "Student is not enrolled in this course"], 400);
            }

            $courseCount = $student->courses()->count();

            if ($courseCount == 1) {
                return response()->json(["message" => "Student cannot drop their only course"], 400);
            }

            $maxDropCount = floor($courseCount / 2);

            if (($courseCount - 1) <= $maxDropCount) {
                return response()->json(["message" => "Student cannot drop more than the allowed number of courses"], 400);
            }

            $student->courses()->detach($course->id);

            return response()->json(["message" => "Student dropped the course successfully"], 200);

        } catch (Exception $e) {
            return response()->json([
                "message" => "An error occurred while dropping the course",
                "error" => $e->getMessage()
            ], 500);
        }
    }

}
