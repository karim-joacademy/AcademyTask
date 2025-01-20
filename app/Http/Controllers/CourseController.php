<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $courses = Course::all();

            if ($courses->isEmpty()) {
                return response()->json(["message" => "No courses found"], 404);
            }
            return response()->json($courses, 200);
        }
        catch (Exception $e) {
            return response()->json([
                "message" => "An error occurred while retrieving the courses",
                "error" => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request)
    {
        try {
            $course = Course::create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'teacher_id' => $request->input('teacher_id'),
            ]);

            return response()->json($course, 201);
        }
        catch (Exception $e) {
            return response()->json([
                "message" => "An error occurred while creating the course",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course) : JsonResponse
    {
        try {
            $course->load('teacher');
            return response()->json($course);
        }
        catch (Exception $e) {
            return response()->json([
                "message" => "An error occurred while retrieving the course",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, int $id) : JsonResponse
    {
        try {
            $course = Course::findOrFail($id);
            $teacher_id = $course->teacher_id;

            $course->update([
                'title' => $request->input('title', $course->title),
                'description' => $request->input('description', $course->description),
                'teacher_id' => $teacher_id,
            ]);
            return response()->json($course, 200);
        }
        catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to update the course.',
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
            $course = Course::query()->find($id);

            if (!$course) {
                return response()->json(["message" => "Course not found"], 404);
            }
            $course->delete();

            return response()->json(['message' => 'Course deleted successfully.'], 200);
        }
        catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }
}
