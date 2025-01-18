<?php

use App\Http\Controllers\AcademyController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return 'Hello World';
});

Route::apiResource('academies', AcademyController::class);
Route::apiResource('teachers', TeacherController::class);
Route::apiResource('courses', CourseController::class);
Route::apiResource('students', StudentController::class);



