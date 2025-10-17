<?php
namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TaskController extends Controller
{
    public function __construct(private TaskRepositoryInterface $taskRepository)
    {
        $this->middleware('auth:sanctum');
    }

    public function index(): JsonResponse
    {
        $tasks = $this->taskRepository->all();
        return response()->json($tasks);
    }

    public function store(TaskRequest $request): JsonResponse
    {
        $task = $this->taskRepository->create(array_merge(
            $request->validated(),
            ['user_id' => auth()->id()]
        ));

        return response()->json($task, 201);
    }

    public function show(int $id): JsonResponse
    {
        $task = $this->taskRepository->find($id);
        
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        return response()->json($task);
    }

    public function update(TaskRequest $request, int $id): JsonResponse
    {
        $task = $this->taskRepository->find($id);
        
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        if ($task->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $this->taskRepository->update($id, $request->validated());

        return response()->json(['message' => 'Task updated successfully']);
    }

    public function destroy(int $id): JsonResponse
    {
        $task = $this->taskRepository->find($id);
        
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        if ($task->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $this->taskRepository->delete($id);

        return response()->json(['message' => 'Task deleted successfully']);
    }

    public function myTasks(): JsonResponse
    {
        $tasks = $this->taskRepository->getUserTasks(auth()->id());
        return response()->json($tasks);
    }

    public function assignedTasks(): JsonResponse
    {
        $tasks = $this->taskRepository->getAssignedTasks(auth()->id());
        return response()->json($tasks);
    }
}