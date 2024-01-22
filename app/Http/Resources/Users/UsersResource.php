<?php

namespace App\Http\Resources\Users;

use App\Http\Resources\CollectionResource;
use Illuminate\Http\Request;

class UsersResource extends CollectionResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->transform(function ($user) {
                return [
                    'id' => $this->id,
                    'name' => $this->name,
                    'role' => $this->role,
                    'birthday' => $this->birthday,
                    'phone' => $this->phone,
                    'image' => $this->image,
                    'created_at' => (string) $this->created_at,
                    'updated_at' => (string) $this->updated_at,
                ];
            }),
        ];
    }
}
