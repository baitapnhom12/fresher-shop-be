<?php

namespace App\Http\Resources\Post;

use App\Enums\PostDefine;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $reviews = !empty($this->reviews) ? collect($this->reviews)->sortByDesc('created_at')->map(fn ($review) => [
            'id' => $review['id'],
            'comment' => $review['comment'],
            'replyTo' => $review['reply_to'],
            'createdAt' => $review['created_at'],
            'user' => [
                'name' => $review['user']['name'],
                'email' => $review['user']['email'],
                'avatar' => $review['user']['images'] ? collect($review['user']['images'])
                    ->first()->path ?? null : null,
            ],
            'replies' => collect($this->reviews)->where('reply_to', $review['id'])->map(function ($reply) {
                return [
                    'id' => $reply['id'],
                    'comment' => $reply['comment'],
                    'replyTo' => $reply['reply_to'],
                    'createdAt' => $reply['created_at'],
                    'user' => [
                        'name' => $reply['user']['name'],
                        'email' => $reply['user']['email'],
                        'avatar' => $reply['user']['images'] ? collect($reply['user']['images'])
                            ->first()->path ?? null : null,
                    ],

                ];
            })->all(),
        ]
        )->filter(function ($review) {
            return empty($review['replyTo']);
        })->all() : [];

        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => $this->image,
            'author' => $this->author,
            'content' => $this->content,
            'slug' => $this->slug,
            'active' => $this->active == PostDefine::Active ? 'Active' : 'Hidden',
            'popular' => $this->popular == PostDefine::Popular ? 'Popular' : 'Unpopular',
            'createdAt' => $this->created_at,
            'articles' => collect($this->articlePost)->map(fn ($child) => [
                'name' => $child['name'],
            ]),
            'reviews' => $reviews,
        ];
    }
}
