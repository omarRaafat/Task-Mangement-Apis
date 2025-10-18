<?php
namespace App\Repositories;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class TaskRepository implements TaskRepositoryInterface
{
    protected const CACHE_TTL = 3600; // 1 hour in seconds
    protected const CACHE_PREFIX = 'tasks';

    public function all(): LengthAwarePaginator
    {
        $userId = auth()->id();
        $page = request('page', 1);
        $perPage = request('per_page', 10);
        
        $cacheKey = "{$this->getCachePrefix()}.user.{$userId}.page.{$page}.per_page.{$perPage}";
        
        return Cache::tags([$this->getUserTag($userId), $this->getListTag()])
            ->remember($cacheKey, self::CACHE_TTL, function () use ($userId, $perPage) {
                return Task::where('user_id', $userId)
                    ->with(['user', 'assignee'])
                    ->paginate($perPage);
            });
    }

    public function find(int $id): ?Task
    {
        $cacheKey = "{$this->getCachePrefix()}.{$id}";
        
        return Cache::tags([$this->getTaskTag($id), $this->getListTag()])
            ->remember($cacheKey, self::CACHE_TTL, function () use ($id) {
                return Task::with(['user', 'assignee', 'comments.user'])
                    ->find($id);
            });
    }

    public function create(array $data): Task
    {
        $task = Task::create($data);
        $this->clearUserCaches($task->user_id);
        
        if (isset($data['assigned_to'])) {
            $this->clearUserCaches($data['assigned_to']);
        }
        
        // Clear general list caches
        $this->clearListCaches();
        
        return $task;
    }

    public function update(int $id, array $data): bool
    {
        $task = Task::findOrFail($id);
        $oldAssignedTo = $task->assigned_to;
        $oldUserId = $task->user_id;
        
        $result = $task->update($data);
        
        if ($result) {
            $this->clearTaskCache($id);
            $this->clearUserCaches($oldUserId);
            $this->clearUserCaches($oldAssignedTo);
            
            // Clear for new assignee if changed
            if (isset($data['assigned_to']) && $data['assigned_to'] != $oldAssignedTo) {
                $this->clearUserCaches($data['assigned_to']);
            }
            
            // Clear for new owner if changed (though this shouldn't happen often)
            if (isset($data['user_id']) && $data['user_id'] != $oldUserId) {
                $this->clearUserCaches($data['user_id']);
            }
            
            $this->clearListCaches();
        }
        
        return $result;
    }

    public function delete(int $id): bool
    {
        $task = Task::findOrFail($id);
        $userId = $task->user_id;
        $assignedTo = $task->assigned_to;
        
        $result = $task->delete();
        
        if ($result) {
            $this->clearTaskCache($id);
            $this->clearUserCaches($userId);
            $this->clearUserCaches($assignedTo);
            $this->clearListCaches();
        }
        
        return $result;
    }

    public function getUserTasks(int $userId): LengthAwarePaginator
    {
        $page = request('page', 1);
        $perPage = request('per_page', 10);
        
        $cacheKey = "{$this->getCachePrefix()}.user_all.{$userId}.page.{$page}.per_page.{$perPage}";
        
        return Cache::tags([$this->getUserTag($userId), $this->getListTag()])
            ->remember($cacheKey, self::CACHE_TTL, function () use ($userId, $perPage) {
                return Task::where('user_id', $userId)
                    ->orWhere('assigned_to', $userId)
                    ->with(['user', 'assignee'])
                    ->paginate($perPage);
            });
    }

    public function getAssignedTasks(int $userId): LengthAwarePaginator
    {
        $page = request('page', 1);
        $perPage = request('per_page', 10);
        
        $cacheKey = "{$this->getCachePrefix()}.assigned.{$userId}.page.{$page}.per_page.{$perPage}";
        
        return Cache::tags([$this->getUserTag($userId), $this->getListTag()])
            ->remember($cacheKey, self::CACHE_TTL, function () use ($userId, $perPage) {
                return Task::where('assigned_to', $userId)
                    ->with(['user', 'assignee'])
                    ->paginate($perPage);
            });
    }

    /**
     * Clear all caches for a specific task
     */
    private function clearTaskCache(int $taskId): void
    {
        Cache::tags([$this->getTaskTag($taskId)])->flush();
    }

    /**
     * Clear all caches for a specific user (both owned and assigned tasks)
     */
    private function clearUserCaches(int $userId): void
    {
        if ($userId) {
            Cache::tags([$this->getUserTag($userId)])->flush();
        }
    }

    /**
     * Clear all list-based caches
     */
    private function clearListCaches(): void
    {
        Cache::tags([$this->getListTag()])->flush();
    }

    /**
     * Cache key helpers
     */
    private function getCachePrefix(): string
    {
        return self::CACHE_PREFIX;
    }

    private function getUserTag(int $userId): string
    {
        return "{$this->getCachePrefix()}.user.{$userId}";
    }

    private function getTaskTag(int $taskId): string
    {
        return "{$this->getCachePrefix()}.task.{$taskId}";
    }

    private function getListTag(): string
    {
        return "{$this->getCachePrefix()}.lists";
    }

    /**
     * Method to clear all task caches (useful for maintenance)
     */
    public function clearAllCaches(): void
    {
        Cache::tags([$this->getListTag()])->flush();
        // Note: We can't flush all user tags without knowing all user IDs
    }
}