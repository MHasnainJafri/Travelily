<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\TaskService;
use Illuminate\Http\Request;
use Mhasnainjafri\RestApiKit\API;

class TaskController extends Controller
{
    protected $service;

    public function __construct(TaskService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return inertia('tasks/Index');
    }

    public function show($id)
    {
        $record = $this->service->getRecord($id);
        return inertia('tasks/View', ['record' => $record]);
    }

    public function destroy($id)
    {
        $this->service->destroy($id);
        return redirect()->back()->with('success', 'Task deleted successfully.');
    }

    public function getData()
    {
        $data = $this->service->getData();
        if ($data->isEmpty()) {
            return API::notFound('No tasks found');
        }

        $data->getCollection()->transform(function ($task) {
            return [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'status' => $task->status,
                'due_date' => $task->due_date,
                'jam' => $task->jam?->name,
                'assignees_count' => $task->assignees->count(),
                'created_at' => $task->created_at->diffForHumans(),
            ];
        });

        return API::paginated($data);
    }
}
