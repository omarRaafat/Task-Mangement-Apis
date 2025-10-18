<?php
namespace App\Services;

use App\Jobs\SendCommentNotification;
use App\Models\Comment;
use App\Models\Task;
use App\Repositories\CommentRepository;
use App\Repositories\TaskRepository;
use Illuminate\Database\Eloquent\Collection;

class CommentService
{
    public function __construct(
        private CommentRepository $commentRepository,
        private TaskRepository $taskRepository,
        private CommentAuthorizationService $authService
    ) {}

    public function getTaskComments(int $taskId): Collection
    {
        $task = $this->taskRepository->find($taskId);
        
        if (!$task) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException('Task not found');
        }

        $this->authService->authorize('view-any', $task);
        
        return $this->commentRepository->getTaskComments($taskId);
    }

    public function getComment(int $commentId): ?Comment
    {
        $comment = $this->commentRepository->find($commentId);
        
        if (!$comment) {
            return null;
        }

        $this->authService->authorize('view', $comment);
        return $comment;
    }

    public function createComment(int $taskId, array $data): Comment
    {
        $task = $this->taskRepository->find($taskId);
        
        if (!$task) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException('Task not found');
        }

        $this->authService->authorize('create', $task);

        $commentData = array_merge($data, [
            'task_id' => $taskId,
            'user_id' => auth()->id(),
        ]);

        $comment = $this->commentRepository->create($commentData);

        // Dispatch job to send notification
        SendCommentNotification::dispatch($task, $comment);

        return $comment;
    }

    public function updateComment(int $taskId, int $commentId, array $data): bool
    {
        $comment = $this->commentRepository->findForTask($taskId, $commentId);
        
        if (!$comment) {
            return false;
        }

        $this->authService->authorize('update', $comment);
        
        return $this->commentRepository->update($commentId, $data);
    }

    public function deleteComment(int $taskId, int $commentId): bool
    {
        $comment = $this->commentRepository->findForTask($taskId, $commentId);
        
        if (!$comment) {
            return false;
        }

        $this->authService->authorize('delete', $comment);
        
        return $this->commentRepository->delete($commentId);
    }

    public function getCommentForTask(int $taskId, int $commentId): ?Comment
    {
        $comment = $this->commentRepository->findForTask($taskId, $commentId);
        
        if (!$comment) {
            return null;
        }

        $this->authService->authorize('view', $comment);
        return $comment;
    }
}