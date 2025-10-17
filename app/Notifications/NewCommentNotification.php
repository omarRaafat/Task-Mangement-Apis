<?php
namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Comment $comment)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Comment on Your Task')
            ->line("A new comment has been added to your task: {$this->comment->task->title}")
            ->line("Comment: {$this->comment->content}")
            ->action('View Task', url("/tasks/{$this->comment->task_id}"))
            ->line('Thank you for using our application!');
    }
}