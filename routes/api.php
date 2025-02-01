<?php

use App\Http\Controllers\AcademyController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout']);

Route::apiResource('academies', AcademyController::class);
Route::apiResource('teachers', TeacherController::class);
Route::apiResource('courses', CourseController::class);
Route::apiResource('students', StudentController::class);

Route::post('students/enroll', [StudentController::class, 'enroll']);
Route::post('students/drop', [StudentController::class, 'drop']);

Route::post('/teachers/{teacher}/fire-student', [TeacherController::class, 'fireStudent']);
Route::post('/teachers/{teacher}/add-course', [TeacherController::class, 'addCourse']);
