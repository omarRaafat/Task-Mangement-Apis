<?php
namespace App\Repositories;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class TaskRepository implements TaskRepositoryInterface
{
    public function all(): LengthAwarePaginator
    {
        return Cache::remember('tasks.all', 60, function () {
            return Task::with(['user', 'assignee'])->paginate(10);
        });
    }

    public function find(int $id): ?Task
    {
        return Cache::remember("tasks.{$id}", 60, function () use ($id) {
            return Task::with(['user', 'assignee', 'comments.user'])->find($id);
        });
    }

    public function create(array $data): Task
    {
        $task = Task::create($data);
        Cache::forget('tasks.all');
        Cache::forget("tasks.assigned.{$data['assigned_to']}");
        return $task;
    }

    public function update(int $id, array $data): bool
    {
        $task = Task::findOrFail($id);
        $result = $task->update($data);
        
        if ($result) {
            Cache::forget("tasks.{$id}");
            Cache::forget('tasks.all');
            Cache::forget("tasks.assigned.{$task->assigned_to}");
            if (isset($data['assigned_to'])) {
                Cache::forget("tasks.assigned.{$data['assigned_to']}");
            }
        }
        
        return $result;
    }

    public function delete(int $id): bool
    {
        $task = Task::findOrFail($id);
        $assignedTo = $task->assigned_to;
        $result = $task->delete();
        
        if ($result) {
            Cache::forget("tasks.{$id}");
            Cache::forget('tasks.all');
            Cache::forget("tasks.assigned.{$assignedTo}");
        }
        
        return $result;
    }

    public function getUserTasks(int $userId): LengthAwarePaginator
    {
        return Cache::remember("tasks.user.{$userId}", 60, function () use ($userId) {
            return Task::where('user_id', $userId)->with(['user', 'assignee'])->paginate(10);
        });
    }

    public function getAssignedTasks(int $userId): LengthAwarePaginator
    {
        return Cache::remember("tasks.assigned.{$userId}", 60, function () use ($userId) {
            return Task::where('assigned_to', $userId)->with(['user', 'assignee'])->paginate(10);
        });
    }
}