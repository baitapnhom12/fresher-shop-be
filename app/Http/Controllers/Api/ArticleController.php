<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Article\ArticlePostResources;
use App\Http\Resources\Article\ArticleResources;
use App\Services\ArticleService;

class ArticleController extends Controller
{
    private $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    public function index()
    {
        return ArticleResources::collection($this->articleService->list());
    }

    public function showArticle(string $slug)
    {
        return new ArticleResources($this->articleService->showArticle($slug));
    }

    public function showPost(string $slug)
    {
        return ArticlePostResources::collection($this->articleService->showPost($slug));
    }
}
