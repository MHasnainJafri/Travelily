<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function createTask(Request $request, $jamId)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:pending,in_progress,completed',
            'due_date' => 'nullable|date|after:now',
        ]);

        try {
            $task = $this->taskService->createTask($jamId, $data);
            return new TaskResource($task);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function updateTask(Request $request, $taskId)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:pending,in_progress,completed',
            'due_date' => 'nullable|date|after:now',
        ]);

        try {
            $task = $this->taskService->updateTask($taskId, $data);
            return new TaskResource($task);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function deleteTask($taskId)
    {
        try {
            $this->taskService->deleteTask($taskId);
            return response()->json(['message' => 'Task deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function assignUserToTask(Request $request, $taskId)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        try {
            $task = $this->taskService->assignUserToTask($taskId, $data['user_id']);
            return new TaskResource($task->load('assignees'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function removeUserFromTask(Request $request, $taskId)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        try {
            $task = $this->taskService->removeUserFromTask($taskId, $data['user_id']);
            return new TaskResource($task->load('assignees'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}