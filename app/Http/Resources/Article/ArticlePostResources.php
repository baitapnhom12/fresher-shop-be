<?php

namespace App\Http\Resources\Article;

use App\Enums\PostDefine;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticlePostResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => $this->image,
            'author' => $this->author,
            'content' => $this->content,
            'slug' => $this->slug,
            'active' => $this->active == PostDefine::Active ? 'Active' : 'Hidden',
            'created_at' => $this->created_at->format('d-m-Y H:i:s'),
            'articles' => collect($this->articlePost)->map(fn ($child) => [
                'name' => $child['name'],
            ]),
        ];
    }
}
