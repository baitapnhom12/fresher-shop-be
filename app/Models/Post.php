<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    protected $table = 'posts';

    protected $fillable = ['article_id', 'title', 'image', 'author', 'content', 'slug', 'active', 'popular'];

    public function articlePost(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'articles_posts', 'post_id', 'article_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'post_id', 'id');
    }
}
