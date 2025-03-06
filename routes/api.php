<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/courses/teacher/{teacher_id}', [CourseController::class, 'getCoursesByTeacher']);
Route::get('/courses/except-own', [CourseController::class, 'getCoursesExceptOwn'])->middleware('auth:sanctum');
Route::delete('/api/courses/{id}', [CourseController::class, 'destroy']);

Route::apiResource("/courses",CourseController::class);
Route::apiResource("/categories", CategoryController::class);
Route::post('/register', [AuthController::class,'register'])->name('auth.register');
Route::post('/login', [AuthController::class,'login'])->name('auth.login');
Route::post('/logout', [AuthController::class,'logout'])->name('auth.logout')->middleware('auth:sanctum');


