<?php
namespace App\Repositories\Contracts;

use App\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\JsonResource;
interface TaskRepositoryInterface
{
    public function all(): LengthAwarePaginator;
    public function find(int $id): ?Task;
    public function create(array $data): JsonResource;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function getUserTasks(int $userId): LengthAwarePaginator;
    public function getAssignedTasks(int $userId): LengthAwarePaginator;
}