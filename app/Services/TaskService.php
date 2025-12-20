<?php

namespace App\Services;

use App\Models\Jam;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    public function createTask($jamId, $data)
    {
        $jam = Jam::findOrFail($jamId);

        // Check if the Jamboard is locked
        if ($jam->is_locked) {
            throw new \Exception('This Jamboard is locked and cannot be modified');
        }

        // Check if the user has permission (creator or can_edit_jamboard)
        $userRole = $jam->users()->where('user_id', Auth::id())->first();
        if (!$userRole || ($userRole->pivot->role !== 'creator' && !$userRole->pivot->can_edit_jamboard)) {
            throw new \Exception('You do not have permission to create tasks for this Jamboard');
        }

        return Task::create([
            'jam_id' => $jamId,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? 'pending',
            'due_date' => $data['due_date'] ?? null,
        ]);
    }

    public function updateTask($taskId, $data)
    {
        $task = Task::findOrFail($taskId);
        $jam = $task->jam;

        // Check if the Jamboard is locked
        if ($jam->is_locked) {
            throw new \Exception('This Jamboard is locked and cannot be modified');
        }

        // Check if the user has permission (creator or can_edit_jamboard)
        $userRole = $jam->users()->where('user_id', Auth::id())->first();
        if (!$userRole || ($userRole->pivot->role !== 'creator' && !$userRole->pivot->can_edit_jamboard)) {
            throw new \Exception('You do not have permission to update tasks for this Jamboard');
        }

        $task->update([
            'title' => $data['title'] ?? $task->title,
            'description' => $data['description'] ?? $task->description,
            'status' => $data['status'] ?? $task->status,
            'due_date' => $data['due_date'] ?? $task->due_date,
        ]);

        return $task;
    }

    public function deleteTask($taskId)
    {
        $task = Task::findOrFail($taskId);
        $jam = $task->jam;

        // Check if the Jamboard is locked
        if ($jam->is_locked) {
            throw new \Exception('This Jamboard is locked and cannot be modified');
        }

        // Check if the user has permission (creator or can_edit_jamboard)
        $userRole = $jam->users()->where('user_id', Auth::id())->first();
        if (!$userRole || ($userRole->pivot->role !== 'creator' && !$userRole->pivot->can_edit_jamboard)) {
            throw new \Exception('You do not have permission to delete tasks for this Jamboard');
        }

        $task->delete();
        return true;
    }

    public function assignUserToTask($taskId, $userId)
    {
        $task = Task::findOrFail($taskId);
        $jam = $task->jam;

        // Check if the Jamboard is locked
        if ($jam->is_locked) {
            throw new \Exception('This Jamboard is locked and cannot be modified');
        }

        // Check if the user has permission (creator or can_edit_jamboard)
        $userRole = $jam->users()->where('user_id', Auth::id())->first();
        if (!$userRole || ($userRole->pivot->role !== 'creator' && !$userRole->pivot->can_edit_jamboard)) {
            throw new \Exception('You do not have permission to assign users to tasks for this Jamboard');
        }

        // Check if the user is a member of the Jamboard
        $targetUser = $jam->users()->where('user_id', $userId)->first();
        if (!$targetUser) {
            throw new \Exception('User is not a member of this Jamboard');
        }

        // Check if already assigned
        if ($task->assignees()->where('user_id', $userId)->exists()) {
            throw new \Exception('User is already assigned to this task');
        }

        $task->assignees()->attach($userId);
        return $task;
    }

    public function removeUserFromTask($taskId, $userId)
    {
        $task = Task::findOrFail($taskId);
        $jam = $task->jam;

        // Check if the Jamboard is locked
        if ($jam->is_locked) {
            throw new \Exception('This Jamboard is locked and cannot be modified');
        }

        // Check if the user has permission (creator or can_edit_jamboard)
        $userRole = $jam->users()->where('user_id', Auth::id())->first();
        if (!$userRole || ($userRole->pivot->role !== 'creator' && !$userRole->pivot->can_edit_jamboard)) {
            throw new \Exception('You do not have permission to remove users from tasks for this Jamboard');
        }

        // Check if the user is assigned
        if (!$task->assignees()->where('user_id', $userId)->exists()) {
            throw new \Exception('User is not assigned to this task');
        }

        $task->assignees()->detach($userId);
        return $task;
    }
}