<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" =>$this->id,
            "content" =>$this->content,
            "task_id" =>$this->task_id,
            "user_id" =>$this->user_id,
            "created_at"=>$this->created_at,
        ];
    }
}
