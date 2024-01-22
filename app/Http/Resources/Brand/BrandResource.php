<?php

namespace App\Http\Resources\Brand;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $image = collect($this['images'])->first();

        return [
            'id' => $this['id'],
            'name' => $this['name'],
            'slug' => $this['slug'],
            'image' => $this['images'] ? $image['path'] : null,
        ];
    }
}
