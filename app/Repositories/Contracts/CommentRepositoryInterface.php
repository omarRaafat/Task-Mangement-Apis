<?php
namespace App\Repositories\Contracts;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
interface CommentRepositoryInterface
{
    public function getTaskComments(int $taskId): JsonResource;
    public function find(int $id): ?Comment;
    public function create(array $data): Comment;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function findForTask(int $taskId, int $commentId): ?Comment;
}