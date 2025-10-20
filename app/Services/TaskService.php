<?php
namespace App\Services;

use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
class TaskService
{
    public function __construct(
        private TaskRepository $taskRepository,
        private TaskAuthorizationService $authService
    ) {}

    public function getUserTasks(): LengthAwarePaginator|JsonResource
    {
        
        $userTasks = $this->taskRepository->getUserTasks(auth()->id());
        $this->authService->authorize('view-any', $userTasks);
        
        return $userTasks;
    }

    public function getAssignedTasks(): LengthAwarePaginator|JsonResource
    {
        $assignedTasks = $this->taskRepository->getAssignedTasks(auth()->id());
        $this->authService->authorize('view-any', $assignedTasks);
        
        return $assignedTasks;
    }

    public function getTask(int $taskId): ?Task
    {
        $task = $this->taskRepository->find($taskId);
        
        if (!$task) {
            return null;
        }

        $this->authService->authorize('view', $task);
        return $task;
    }

    public function createTask(array $data): JsonResource
    {
        $this->authService->authorize('create', Task::class);
        
        // Ensure the current user is set as the task owner
        $data['user_id'] = auth()->id();
        
        return $this->taskRepository->create($data);
    }

    public function updateTask(int $taskId, array $data): bool
    {
        $task = $this->taskRepository->find($taskId);
        
        if (!$task) {
            return false;
        }

        $this->authService->authorize('update', $task);
        
        return $this->taskRepository->update($taskId, $data);
    }

    public function deleteTask(int $taskId): bool
    {
        $task = $this->taskRepository->find($taskId);
        
        if (!$task) {
            return false;
        }

        $this->authService->authorize('delete', $task);
        
        return $this->taskRepository->delete($taskId);
    }

    public function getAllTasks(): LengthAwarePaginator|JsonResource
    {
        $this->authService->authorize('view-any', Task::class);
        
        return $this->taskRepository->all();
    }

}