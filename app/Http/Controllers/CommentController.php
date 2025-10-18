<?php
namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class CommentController extends Controller
{
    public function __construct(private CommentService $commentService)
    {
        $this->middleware('auth:sanctum');
    }

    public function index(int $taskId): JsonResponse
    {
        try {
            $comments = $this->commentService->getTaskComments($taskId);
            return response()->json($comments);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Task not found'], 404);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    }

    public function store(CommentRequest $request, int $taskId): JsonResponse
    {
        try {
            $comment = $this->commentService->createComment($taskId, $request->validated());
            return response()->json($comment, 201);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Task not found'], 404);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    }

    public function update(CommentRequest $request, int $taskId, int $commentId): JsonResponse
    {
        try {
        $result = $this->commentService->updateComment($taskId, $commentId, $request->validated());
        if (!$result) {
            return response()->json(['message' => 'Comment not found'], 404);
        }
        return response()->json(['message' => 'Comment updated successfully']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Task not found'], 404);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    }

    public function destroy(int $taskId, int $commentId): JsonResponse
    {
        $result = $this->commentService->deleteComment($taskId, $commentId);
        
        if (!$result) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        return response()->json(['message' => 'Comment deleted successfully']);
    }

    public function show(int $taskId, int $commentId): JsonResponse
    {
        $comment = $this->commentService->getCommentForTask($taskId, $commentId);
        
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        return response()->json($comment);
    }
}