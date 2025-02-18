<?php

use App\Http\Controllers\AcademyController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

// Protected Routes (Require Authentication)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    // Academy Routes (Only Academy Role)
    Route::middleware(['role:academy'])->group(function () {
        Route::apiResource('academies', AcademyController::class);
        Route::delete('/courses/{id}', [CourseController::class, 'destroy'])->middleware('can:delete course');
        Route::delete('/teachers/{id}', [TeacherController::class, 'destroy'])->middleware('can:delete teacher');
    });

    // Teacher Routes (Only Teacher Role)
    Route::middleware(['role:teacher'])->group(function () {
        Route::apiResource('teachers', TeacherController::class)->except(['destroy']);
        Route::post('/teachers/{teacher}/add-course', [TeacherController::class, 'addCourse']);
        Route::post('/courses/{course}/fire-student/{student}', [TeacherController::class, 'fireStudent'])
            ->middleware('can:fire student');
    });

    // Student Routes (Only Student Role)
    Route::middleware(['role:student'])->group(function () {
        Route::apiResource('students', StudentController::class)->except(['destroy']);
        Route::post('students/enroll', [StudentController::class, 'enroll'])->middleware('can:enroll to course');
        Route::post('students/drop', [StudentController::class, 'drop'])->middleware('can:drop course');
    });

    // General Routes (Accessible by Multiple Roles)
    Route::apiResource('courses', CourseController::class)->except(['destroy']);
});
