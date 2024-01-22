<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginationResource extends ResourceCollection
{
    public function __construct($resource, $msg = 'success')
    {
        $this->pagination = [
            'total' => $resource['total'],
            'perPage' => $resource['per_page'],
            'currentPage' => $resource['current_page'],
            'lastPage' => $resource['last_page'],
            'totalPage' => ceil($resource['total'] / $resource['per_page']),
        ];
        $this->msg = $msg;
        $resource = $resource['data'];

        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this['data'],
            'meta' => $this->pagination,
            'status' => true,
            'message' => $this->msg,
        ];
    }
}
