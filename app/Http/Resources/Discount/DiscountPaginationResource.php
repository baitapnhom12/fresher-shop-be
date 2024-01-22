<?php

namespace App\Http\Resources\Discount;

use App\Http\Resources\PaginationResource;
use Illuminate\Http\Request;

class DiscountPaginationResource extends PaginationResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => DiscountResource::collection($this->collection),
            'meta' => $this->pagination,
            'status' => true,
            'message' => $this->msg,
        ];
    }
}
