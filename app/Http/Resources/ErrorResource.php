<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ErrorResource extends JsonResource
{
    /**
     * @var string
     */
    public $msg;

    public function __construct($msg = 'error')
    {
        $this->msg = $msg;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        self::withoutWrapping();

        return [
            'status' => false,
            'message' => $this->msg,
        ];
    }
}
