<?php
namespace App\Services;

use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

class CommentAuthorizationService
{
    public function authorize(string $ability, $arguments = []): void
    {
        if (!$this->hasPermission($ability, $arguments)) {
            throw new AuthorizationException('This action is unauthorized.');
        }
    }

    private function hasPermission(string $ability, $arguments): bool
    {
        $user = auth()->user();

        switch ($ability) {
            case 'view-any':
                return $this->canViewAnyComments($user, $arguments);
            
            case 'view':
                return $this->canViewComment($user, $arguments);
            
            case 'create':
                return $this->canCreateComment($user, $arguments);
            
            case 'update':
                return $this->canUpdateComment($user, $arguments);
            
            case 'delete':
                return $this->canDeleteComment($user, $arguments);
            
            default:
                return false;
        }
    }

    private function canViewAnyComments(User $user, Task $task): bool
    {
        // User can view comments if they can view the task
        return $user->id === $task->user_id || $user->id === $task->assigned_to;
    }

    private function canViewComment(User $user, Comment $comment): bool
    {
        // User can view comment if they can view the task
        return $this->canViewAnyComments($user, $comment->task);
    }

    private function canCreateComment(User $user, Task $task): bool
    {
        // User can comment if they're associated with the task
        return $user->id === $task->user_id || $user->id === $task->assigned_to;
    }

    private function canUpdateComment(User $user, Comment $comment): bool
    {
        // Only comment owner can update their comment
        return $user->id === $comment->user_id;
    }

    private function canDeleteComment(User $user, Comment $comment): bool
    {
        // Comment owner or task owner can delete comments
        return $user->id === $comment->user_id || $user->id === $comment->task->user_id;
    }
}