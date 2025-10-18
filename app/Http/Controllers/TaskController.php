<?php
namespace App\Http\Controllers;

use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\TaskRequest;
use Illuminate\Routing\Controller;
class TaskController extends Controller
{
    public function __construct(private TaskService $taskService) {
        $this->middleware('auth:sanctum');
    }

    public function index(): JsonResponse
    {
      
        $tasks = $this->taskService->getAllTasks();
        return response()->json($tasks);
    }

    public function assigned(): JsonResponse
    {
        $tasks = $this->taskService->getAssignedTasks();
        return response()->json($tasks);
    }

    public function show(int $id): JsonResponse
    {
        $task = $this->taskService->getTask($id);
        
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        return response()->json($task);
    }

    public function store(TaskRequest $request): JsonResponse
    {

        $task = $this->taskService->createTask($request->validated());
        
        return response()->json($task, 201);
    }

    public function update(TaskRequest $request, int $id): JsonResponse
    {

        $result = $this->taskService->updateTask($id, $request->validated());
        
        if (!$result) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        return response()->json(['message' => 'Task updated successfully']);
    }

    public function destroy(int $id): JsonResponse
    {
        $result = $this->taskService->deleteTask($id);
        
        if (!$result) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        return response()->json(['message' => 'Task deleted successfully']);
    }
    public function myTasks(): JsonResponse
    {
        $tasks = $this->taskService->getUserTasks();
        return response()->json($tasks);
    }

    public function assignedTasks(): JsonResponse
    {
        $tasks = $this->taskService->getAssignedTasks();
        return response()->json($tasks);
    }
    
}