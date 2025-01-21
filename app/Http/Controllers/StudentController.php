<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Student;
use Exception;
use Illuminate\Http\JsonResponse;

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
    public function store(StoreStudentRequest $request) : JsonResponse
    {
        return response()->json();
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
        catch (\Exception $e) {
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
}
