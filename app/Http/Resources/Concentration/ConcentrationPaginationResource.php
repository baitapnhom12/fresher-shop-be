<?php

namespace App\Http\Resources\Concentration;

use App\Http\Resources\PaginationResource;
use Illuminate\Http\Request;

class ConcentrationPaginationResource extends PaginationResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => ConcentrationResource::collection($this->collection),
            'meta' => $this->pagination,
            'status' => true,
            'message' => $this->msg,
        ];
    }
}
