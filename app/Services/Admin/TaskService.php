<?php

namespace App\Services\Admin;

use App\Models\Task;
use App\Helper\DataTableActions;

class TaskService
{
    use DataTableActions;

    public function getData()
    {
        $query = Task::query()->with(['jam', 'assignees']);

        return $this->getProcessedData($query, request()->input('per_page', 10));
    }

    public function getRecord($id)
    {
        return Task::with(['jam', 'assignees'])->findOrFail($id);
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
    }
}
