<?php

namespace App\Http\Controllers;

use App\Events\CommentCreated;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index($courseId)
    {
        return Comment::where('course_id', $courseId)
            ->whereNull('parent_id')
            ->with('user', 'replies.user')
            ->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'content' => 'required',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        $comment = Comment::create([
            'user_id' => auth()->id(),
            'course_id' => $request->course_id,
            'parent_id' => $request->parent_id,
            'content' => $request->content
        ]);
        
        broadcast(new CommentCreated($comment))->toOthers();

        return response()->json($comment, 201);
        // return response()->json($comment->load('user'));
    }
}
