<?php

namespace App\Http\Resources\PaymentMethods;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethodResource extends JsonResource
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
            'type' => $this['type'] ?? null,
            'provider' => $this['provider'] ?? null,
            'account_number' => $this['account_number'] ?? null,
        ];
    }
}
