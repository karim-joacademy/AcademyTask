<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Models\Teacher;
use Illuminate\Http\JsonResponse;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : JsonResponse
    {
        $teachers = Teacher::all();

        if ($teachers->isEmpty()) {
            return response()->json(["message" => "No teachers"], 404);
        }
        return response()->json($teachers);
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
        $teacher->load('academy', 'courses');

        return response()->json($teacher);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTeacherRequest $request, Teacher $teacher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher) : JsonResponse
    {
        try {
            $teacher->delete();

            return response()->json([
                'message' => 'Teacher deleted successfully.',
            ], 200);
        } catch (\Exception $e) {
            // Handle exceptions and return an error response
            return response()->json([
                'message' => 'Failed to delete the teacher.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
