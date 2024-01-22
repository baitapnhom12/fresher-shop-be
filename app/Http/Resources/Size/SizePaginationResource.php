<?php

namespace App\Http\Resources\Size;

use App\Http\Resources\PaginationResource;
use Illuminate\Http\Request;

class SizePaginationResource extends PaginationResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => SizeResource::collection($this->collection),
            'meta' => $this->pagination,
            'status' => true,
            'message' => $this->msg,
        ];
    }
}
