<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;
use Illuminate\Http\JsonResponse;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : JsonResponse
    {
        $courses = Course::all();

        if ($courses->isEmpty()) {
            return response()->json(["message" => "No courses"], 404);
        }

        return response()->json($courses);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request)
    {

        $course = Course::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'teacher_id' => $request->input('teacher_id'),
        ]);

        return response()->json($course, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course) : JsonResponse
    {
        $course->load('teacher');

        return response()->json($course);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, Course $course)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course) : JsonResponse
    {
        $course->delete();
        return response()->json(['message' => 'Course deleted successfully.']);
    }
}
