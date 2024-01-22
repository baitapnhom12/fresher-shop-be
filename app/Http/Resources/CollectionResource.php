<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CollectionResource extends ResourceCollection
{
    /**
     * @var string
     */
    public $msg;

    public function __construct($resource = null, $msg = 'success')
    {
        parent::__construct($resource);
        $this->msg = $msg;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
        ];
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function with($request)
    {
        return [
            'status' => true,
            'message' => $this->msg,
        ];
    }
}
