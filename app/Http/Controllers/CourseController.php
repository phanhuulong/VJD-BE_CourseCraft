<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
class CourseController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];
    }
    public function index()
    {
        return response()->json(Course::all(), Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        // Gate::authorize('create-course');
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'teacher_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'thumbnail_course' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'url_video' => 'required|url'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($request->hasFile('thumbnail_course')) {
            $file = $request->file('thumbnail_course');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $fileName);
        } else {
            return response()->json(['error' => 'File upload failed'], Response::HTTP_BAD_REQUEST);
        }
        $course = Course::create([
            'title' => $request->title,
            'description' => $request->description,
            'teacher_id' => $request->teacher_id,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'thumbnail_course' => 'images/' . $fileName,
            'url_video' => $request->url_video
        ]);
        return response()->json($course, Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $course = Course::find($id);
        if (!$course) {
            return response()->json(['message' => 'Course not found'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($course, Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        try {
            $course = Course::find($id);
            if (!$course) {
                return response()->json(['message' => 'Course not found'], Response::HTTP_NOT_FOUND);
            }
            if (!Gate::allows('update', $course)) {
                abort(403);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'category_id' => 'required|exists:categories,id',
                'price' => 'required|numeric|min:0',
                'thumbnail_course' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'url_video' => 'required|url'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $course->title = $request->title;
            $course->description = $request->description;
            $course->category_id = (int) $request->category_id; // Typecast to integer
            $course->price = (float) $request->price; // Typecast to float
            $course->url_video = $request->url_video;

            if ($request->hasFile('thumbnail_course')) {
                if ($course->thumbnail_course) {
                    Storage::delete($course->thumbnail_course);
                }
                $file = $request->file('thumbnail_course');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('images'), $fileName);

                $course->thumbnail_course = 'images/' . $fileName;
            }

            $course->save();
            return response()->json($course, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the course', 'details' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        $course = Course::find($id);
        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        if (!Gate::allows('update', $course)) {
            abort(403);
        }

        $course->delete();
        return response()->json(['message' => 'Course deleted successfully']);
    }

    public function getCoursesByTeacher($teacher_id)
    {
        $courses = Course::where('teacher_id', $teacher_id)->get();

        if ($courses->isEmpty()) {
            return response()->json(['message' => 'No courses found for this teacher'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($courses, Response::HTTP_OK);
    }

    public function getCoursesExceptOwn(Request $request)
    {
        $user = $request->user();

        // \Log::info("Authorization Header: " . $request->header('Authorization'));
        // \Log::info("Authenticated User: " . json_encode($user));

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $courses = Course::where('teacher_id', '!=', $user->id)->get();
        return response()->json($courses, Response::HTTP_OK);
    }

}
