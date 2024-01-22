<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class AuthResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $expiration = Config::get('sanctum.expiration');

        return [
            'access_token' => $this->createToken('api')->plainTextToken,
            'token_type' => 'Bearer',
            'max_age' => $expiration * 60,
        ];
    }
}
