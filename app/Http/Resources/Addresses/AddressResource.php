<?php

namespace App\Http\Resources\Addresses;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this['id'],
            'user' => $this->user->only('name', 'email', 'birthday'),
            'receiver' => $this['receiver'] ?? null,
            'phone' => $this['phone'] ?? null,
            'address' => $this['address'] ?? null,
        ];
    }
}
