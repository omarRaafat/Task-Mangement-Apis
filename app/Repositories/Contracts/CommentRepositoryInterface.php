<?php
namespace App\Repositories\Contracts;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;

interface CommentRepositoryInterface
{
    public function getTaskComments(int $taskId): Collection;
    public function find(int $id): ?Comment;
    public function create(array $data): Comment;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function findForTask(int $taskId, int $commentId): ?Comment;
}