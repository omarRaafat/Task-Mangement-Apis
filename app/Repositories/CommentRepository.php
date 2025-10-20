<?php
namespace App\Repositories;

use App\Models\Comment;
use App\Repositories\Contracts\CommentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\CommentResource;
use Illuminate\Http\Resources\Json\JsonResource;
class CommentRepository implements CommentRepositoryInterface
{
    public function getTaskComments(int $taskId): JsonResource
    {
        $this->clearTaskCommentsCache($taskId);
        return Cache::remember("tasks.{$taskId}.comments", 3600, function () use ($taskId) {
            return CommentResource::collection(Comment::where('task_id', $taskId)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get());
        });
    }

    public function find(int $id): ?Comment
    {
        return Cache::remember("comments.{$id}", 3600, function () use ($id) {
            return Comment::with(['user', 'task'])->find($id);
        });
    }

    public function findForTask(int $taskId, int $commentId): ?Comment
    {
        return Cache::remember("tasks.{$taskId}.comments.{$commentId}", 3600, function () use ($taskId, $commentId) {
            return Comment::where('task_id', $taskId)
                ->where('id', $commentId)
                ->with(['user', 'task'])
                ->first();
        });
    }

    public function create(array $data): Comment
    {
        $comment = Comment::create($data);
        $this->clearTaskCommentsCache($comment->task_id);
        $this->clearTaskCache($comment->task_id);
        return $comment;
    }

    public function update(int $id, array $data): bool
    {
        $comment = Comment::findOrFail($id);
        $result = $comment->update($data);

        if ($result) {
            $this->clearCommentCache($id);
            $this->clearTaskCommentsCache($comment->task_id);
            $this->clearTaskCommentCache($comment->task_id, $id);
        }

        return $result;
    }

    public function delete(int $id): bool
    {
        $comment = Comment::findOrFail($id);
        $taskId = $comment->task_id;
        $result = $comment->delete();

        if ($result) {
            $this->clearCommentCache($id);
            $this->clearTaskCommentsCache($taskId);
            $this->clearTaskCommentCache($taskId, $id);
            $this->clearTaskCache($taskId);
        }

        return $result;
    }

    private function clearCommentCache(int $commentId): void
    {
        Cache::forget("comments.{$commentId}");
    }

    private function clearTaskCommentsCache(int $taskId): void
    {
        Cache::forget("tasks.{$taskId}.comments");
    }

    private function clearTaskCommentCache(int $taskId, int $commentId): void
    {
        Cache::forget("tasks.{$taskId}.comments.{$commentId}");
    }

    private function clearTaskCache(int $taskId): void
    {
        Cache::forget("tasks.{$taskId}");
    }
}