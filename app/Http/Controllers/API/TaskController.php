<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['createdBy', 'assignedTo'])->get();
        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'due_date' => 'required|date',
            'assigned_to' => 'required|exists:users,id',
        ]);

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'due_date' => $request->due_date,
            'created_by' => Auth::id(),
            'assigned_to' => $request->assigned_to,
            'total_time_spent' => 0,
        ]);

        return response()->json($task, 201);
    }

    public function show(Task $task)
    {
        $task->load(['createdBy', 'assignedTo', 'comments.user', 'timeLogs.user', 'files']);
        return response()->json($task);
    }

    public function update(Request $request, Task $task)
    {
        if (Auth::id() !== $task->created_by && Auth::id() !== $task->assigned_to) {
            return response()->json([
                'message' => 'You must be the creator or assigned user to update this task'
            ], 403);
        }

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|required|in:pending,in_progress,completed',
            'due_date' => 'sometimes|required|date',
            'assigned_to' => 'sometimes|required|exists:users,id',
        ]);

        if ($request->has('status') && $request->status === 'completed') {
            if (Auth::id() !== $task->assigned_to) {
                return response()->json([
                    'message' => 'Only the assigned user can mark a task as completed'
                ], 403);
            }

            $task->total_time_spent = $task->timeLogs()->sum('minutes');
        }

        $task->update($request->all());

        return response()->json([
            'message' => 'Task updated successfully',
            'task' => $task->load(['createdBy', 'assignedTo', 'comments.user', 'timeLogs.user', 'files'])
        ], 200);
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();
        return response()->json(null, 204);
    }
}