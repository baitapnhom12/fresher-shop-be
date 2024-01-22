<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessResource extends JsonResource
{
    /**
     * @var string
     */
    public $msg;

    public function __construct($msg = 'success')
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
            'status' => true,
            'message' => $this->msg,
        ];
    }
}
