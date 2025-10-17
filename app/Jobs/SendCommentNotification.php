<?php
namespace App\Jobs;

use App\Models\Task;
use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewCommentNotification;

class SendCommentNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Task $task, public Comment $comment)
    {
    }

    public function handle(): void
    {
        // Notify the task author about the new comment
        Notification::send($this->task->user, new NewCommentNotification($this->comment));
    }
}