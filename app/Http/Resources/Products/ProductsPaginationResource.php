<?php

namespace App\Http\Resources\Products;

use App\Http\Resources\PaginationResource;
use Illuminate\Http\Request;

class ProductsPaginationResource extends PaginationResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => ProductResource::collection($this->collection),
            'meta' => $this->pagination,
            'status' => true,
            'message' => $this->msg,
        ];
    }
}
