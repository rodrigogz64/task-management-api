<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index(Task $task)
    {
        $comments = $task->comments()->with('user')->get();
        return response()->json($comments);
    }

    public function store(Request $request, Task $task)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        $comment = $task->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        $comment->load('user');
        return response()->json($comment, 201);
    }
}