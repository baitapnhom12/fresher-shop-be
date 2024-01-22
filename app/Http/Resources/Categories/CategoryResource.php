<?php

namespace App\Http\Resources\Categories;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this['id'],
            'name' => $this['name'],
            'parentId' => $this['parent_id'],
            'slug' => $this['slug'],
            'images' => $this['image'],
            'createdAt' => $this['created_at'],
            'updatedAt' => $this['updated_at'],
            'children' => $this['children'],
        ];
    }
}
