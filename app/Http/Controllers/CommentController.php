<?php
namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Traits\ResponseTrait;
class CommentController extends Controller
{
    use ResponseTrait;
    public function __construct(private CommentService $commentService)
    {
        $this->middleware('auth:sanctum');
    }

    public function index(int $taskId): JsonResponse
    {
        try {
            $comments = $this->commentService->getTaskComments($taskId);
            return $this->successResponse($comments);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Task not found');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return $this->unauthorizedResponse();
        }
    }

    public function store(CommentRequest $request, int $taskId): JsonResponse
    {
        try {
            $comment = $this->commentService->createComment($taskId, $request->validated());
            return $this->successResponse($comment, 'Comment created', 201);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Task not found');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return $this->unauthorizedResponse();
        }
    }

    public function update(CommentRequest $request, int $taskId, int $commentId): JsonResponse
    {
        try {
        $result = $this->commentService->updateComment($taskId, $commentId, $request->validated());
        if (!$result) {
            return $this->notFoundResponse('Comment not found');
        }
        return $this->successResponse(null, 'Comment updated successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse('Task not found');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return $this->unauthorizedResponse();
        }
    }

    public function destroy(int $taskId, int $commentId): JsonResponse
    {
        $result = $this->commentService->deleteComment($taskId, $commentId);
        
        if (!$result) {
            return $this->notFoundResponse('Comment not found');
        }

        return $this->successResponse(null, 'Comment deleted successfully');
    }

    public function show(int $taskId, int $commentId): JsonResponse
    {
        $comment = $this->commentService->getCommentForTask($taskId, $commentId);
        
        if (!$comment) {
            return $this->notFoundResponse('Comment not found');
        }

        return $this->successResponse($comment);
    }
}