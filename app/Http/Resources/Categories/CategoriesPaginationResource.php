<?php

namespace App\Http\Resources\Categories;

use App\Http\Resources\PaginationResource;
use Illuminate\Http\Request;

class CategoriesPaginationResource extends PaginationResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => CategoryResource::collection($this->collection),
            'meta' => $this->pagination,
            'status' => true,
            'message' => $this->msg,
        ];
    }
}
