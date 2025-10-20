<?php
namespace App\Http\Controllers;

use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\TaskRequest;
use Illuminate\Routing\Controller;
use App\Traits\ResponseTrait;
class TaskController extends Controller
{
    use ResponseTrait;
    public function __construct(private TaskService $taskService) {
        $this->middleware('auth:sanctum');
    }

    public function index(): JsonResponse
    {
      
        $tasks = $this->taskService->getAllTasks();
        if ($tasks instanceof AnonymousResourceCollection) {
            return $this->successResponse($tasks);
        }
        return $this->successResponse($tasks);
    }

    public function assigned(): JsonResponse
    {
        $tasks = $this->taskService->getAssignedTasks();
        return $this->successResponse($tasks);
    }

    public function show(int $id): JsonResponse
    {
        $task = $this->taskService->getTask($id);
        
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        return $this->successResponse($task);
    }

    public function store(TaskRequest $request): JsonResponse
    {

        $task = $this->taskService->createTask($request->validated());
        
       return $this->successResponse($task , 'New Task Created',201);
    }

    public function update(TaskRequest $request, int $id): JsonResponse
    {

        $result = $this->taskService->updateTask($id, $request->validated());
        
        if (!$result) {
            return $this->notFoundResponse('Task not found');
        }

        return $this->successResponse(null, 'Task updated successfully');
    }

    public function destroy(int $id): JsonResponse
    {
        $result = $this->taskService->deleteTask($id);
        
        if (!$result) {
            return $this->notFoundResponse('Task not found');
        }

        return $this->successResponse(null, 'Task deleted successfully');
    }
    public function myTasks(): JsonResponse
    {
        $tasks = $this->taskService->getUserTasks();
        return $this->successResponse($tasks);
    }

    public function assignedTasks(): JsonResponse
    {
        $tasks = $this->taskService->getAssignedTasks();
        return $this->successResponse($tasks);
    }
    
}