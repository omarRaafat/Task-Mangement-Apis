<?php
namespace App\Services;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

class TaskAuthorizationService
{
    public function authorize(string $ability, $arguments = []): void
    {
        if (!$this->hasPermission($ability, $arguments)) {
            throw new AuthorizationException('This action is unauthorized.');
        }
    }

    private function hasPermission(string $ability, $arguments): bool
    {
        $user = auth()->user();

        switch ($ability) {
            case 'view-any':
                return $this->canViewAnyTasks($user);
            
            case 'view':
                return $this->canViewTask($user, $arguments);
            
            case 'create':
                return $this->canCreateTask($user);
            
            case 'update':
                return $this->canUpdateTask($user, $arguments);
            
            case 'delete':
                return $this->canDeleteTask($user, $arguments);
            
            default:
                return false;
        }
    }

    private function canViewAnyTasks(User $user): bool
    {
        // Allow users to view their own and assigned tasks
        return true;
    }

    private function canViewTask(User $user, Task $task): bool
    {
        // User can view if they own the task or are assigned to it
        return $user->id === $task->user_id || $user->id === $task->assigned_to;
    }

    private function canCreateTask(User $user): bool
    {
        // Any authenticated user can create tasks
        return true;
    }

    private function canUpdateTask(User $user, Task $task): bool
    {
        // Only task owner can update the task
        return $user->id === $task->user_id;
    }

    private function canDeleteTask(User $user, Task $task): bool
    {
        // Only task owner can delete the task
        return $user->id === $task->user_id;
    }
}