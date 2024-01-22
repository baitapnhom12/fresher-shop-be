<?php

namespace App\Http\Resources\Post;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentPostResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = collect($this->user);

        return [
            'id' => $this->id,
            'comment' => $this->comment,
            'author' => $user['name'],
        ];
    }
}
