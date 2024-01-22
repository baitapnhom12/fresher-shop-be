<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model
{
    use HasFactory;

    protected $table = 'articles';

    protected $fillable = ['name', 'slug'];

    public function post(): HasMany
    {
        return $this->hasMany(Post::class, 'article_id', 'id');
    }
}
