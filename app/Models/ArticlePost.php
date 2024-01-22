<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticlePost extends Model
{
    protected $table = 'articles_posts';

    protected $fillable = ['post_id', 'article_id'];
}
