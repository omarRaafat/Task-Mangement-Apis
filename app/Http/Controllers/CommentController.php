<?php
namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Jobs\SendCommentNotification;
use App\Models\Task;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Routing\Controller;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(int $taskId): JsonResponse
    {
        $comments = Cache::remember("tasks.{$taskId}.comments", 60, function () use ($taskId) {
            return Comment::where('task_id', $taskId)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get();
        });

        return response()->json($comments);
    }

    public function store(CommentRequest $request, int $taskId): JsonResponse
    {
        $task = Task::find($taskId);
        
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $comment = Comment::create([
            'content' => $request->content,
            'task_id' => $taskId,
            'user_id' => auth()->id(),
        ]);

        // Dispatch job to send notification
        SendCommentNotification::dispatch($task, $comment);

        // Clear cache
        Cache::forget("tasks.{$taskId}.comments");
        Cache::forget("tasks.{$taskId}");

        return response()->json($comment, 201);
    }

    public function update(CommentRequest $request, int $taskId, int $commentId): JsonResponse
    {
        $comment = Comment::where('id', $commentId)
            ->where('task_id', $taskId)
            ->first();

        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        if ($comment->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->update($request->validated());

        Cache::forget("tasks.{$taskId}.comments");

        return response()->json(['message' => 'Comment updated successfully']);
    }

    public function destroy(int $taskId, int $commentId): JsonResponse
    {
        $comment = Comment::where('id', $commentId)
            ->where('task_id', $taskId)
            ->first();

        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        if ($comment->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->delete();

        Cache::forget("tasks.{$taskId}.comments");

        return response()->json(['message' => 'Comment deleted successfully']);
    }
}