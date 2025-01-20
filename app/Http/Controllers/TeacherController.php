<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Models\Teacher;
use Exception;
use Illuminate\Http\JsonResponse;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : JsonResponse
    {
        try {
            $teachers = Teacher::all();

            if ($teachers->isEmpty()) {
                return response()->json(["message" => "No teachers"], 404);
            }
            return response()->json($teachers);
        }
        catch (Exception $e) {
            return response()->json([
                "message" => "An error occurred while retrieving the teachers",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTeacherRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher) : JsonResponse
    {
        try {
            $teacher->load('academy', 'courses');
            return response()->json($teacher);
        }
        catch (Exception $e) {
            return response()->json([
                "message" => "An error occurred while retrieving the teacher",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTeacherRequest $request, Teacher $teacher): JsonResponse
    {
        try {
            $name = $teacher->name;
            $academy_id = $teacher->academy_id;

            $teacher->update([
                'name' => $name,
                'academy_id' => $academy_id,
                'email' => $request->input('email', $teacher->email),
                'phone' => $request->input('phone', $teacher->phone)
            ]);
            return response()->json($teacher, 200);
        }
        catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to update the teacher.',
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
            $teacher = Teacher::query()->find($id);

            if (!$teacher) {
                return response()->json([
                    'message' => 'Teacher not found.',
                ], 404);
            }
            $teacher->delete();

            return response()->json([
                'message' => 'Teacher deleted successfully.',
            ], 200);
        }
        catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to delete the teacher.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
