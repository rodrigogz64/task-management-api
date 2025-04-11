<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TaskFileController extends Controller
{
    public function index(Task $task)
    {
        $files = $task->files()->with('user')->get();
        return response()->json($files);
    }

    public function store(Request $request, Task $task)
    {
        if (Auth::id() !== $task->created_by && Auth::id() !== $task->assigned_to) {
            return response()->json(['message' => 'Only the creator or assigned user can upload files'], 403);
        }

        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max size
        ]);

        $path = $request->file('file')->store("tasks/{$task->id}", 'public');

        $taskFile = $task->files()->create([
            'user_id' => Auth::id(),
            'file_path' => $path,
        ]);

        return response()->json($taskFile, 201);
    }
}