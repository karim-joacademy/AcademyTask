<?php

use App\Http\Controllers\AcademyController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::middleware(['role:academy'])->group(function () {
        Route::apiResource('academies', AcademyController::class);
        Route::delete('/courses/{id}', [CourseController::class, 'destroy'])->middleware('can:delete-course');
        Route::delete('/teachers/{id}', [TeacherController::class, 'destroy'])->middleware('can:delete-teacher');
    });

    Route::middleware(['role:teacher'])->group(function () {
        Route::apiResource('teachers', TeacherController::class)->except(['destroy']);
        Route::post('/teachers/{teacher}/add-course', [TeacherController::class, 'addCourse']);
        Route::post('/teachers/{teacher}/fire-student', [TeacherController::class, 'fireStudent'])
            ->middleware('can:fire-student');

        Route::post('/courses', [CourseController::class, 'store'])
            ->middleware('can:create-course');

        Route::put('/courses/{course}', [CourseController::class, 'update'])
            ->middleware('can:update-course');
    });

    Route::middleware(['role:student'])->group(function () {
        Route::apiResource('students', StudentController::class)->except(['destroy']);
        Route::post('students/enroll', [StudentController::class, 'enroll'])->middleware('can:enroll-to-course');
        Route::post('students/drop', [StudentController::class, 'drop'])->middleware('can:drop-course');
    });

    Route::apiResource('courses', CourseController::class)->except(['store', 'update', 'destroy']);
});
