<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::orderBy('due_at')->get();

        return response()->json($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|string|in:pending,in_progress,completed',
            'due_at'      => 'required|date',
        ]);

        $task = Task::create($data);

        return response()->json($task, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return response()->json($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $data = $request->validate([
            'title'       => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'sometimes|required|string|in:pending,in_progress,completed',
            'due_at'      => 'sometimes|required|date',
        ]);

        $task->update($data);

        return response()->json($task);
    }

    public function updateStatus(Request $request, Task $task)
    {
        $data = $request->validate([
            'status' => 'required|string|in:pending,in_progress,completed',
        ]);

        $task->update(['status' => $data['status']]);

        return response()->json($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
