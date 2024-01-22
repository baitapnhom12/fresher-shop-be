<?php

namespace App\Http\Resources\Color;

use App\Http\Resources\PaginationResource;
use Illuminate\Http\Request;

class ColorPaginationResource extends PaginationResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => ColorResource::collection($this->collection),
            'meta' => $this->pagination,
            'status' => true,
            'message' => $this->msg,
        ];
    }
}
