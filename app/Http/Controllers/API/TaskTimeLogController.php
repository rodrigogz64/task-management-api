<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskTimeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskTimeLogController extends Controller
{
    public function index(Task $task)
    {
        $timeLogs = $task->timeLogs()->with('user')->get();
        return response()->json($timeLogs);
    }

    public function store(Request $request, Task $task)
    {
        if (Auth::id() !== $task->assigned_to) {
            return response()->json(['message' => 'Only the assigned user can log time on this task'], 403);
        }

        $request->validate([
            'minutes' => 'required|integer|min:1',
        ]);

        $timeLog = $task->timeLogs()->create([
            'user_id' => Auth::id(),
            'minutes' => $request->minutes,
        ]);

        $task->increment('total_time_spent', $request->minutes);

        return response()->json($timeLog, 201);
    }
}