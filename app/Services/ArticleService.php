<?php

namespace App\Services;

use App\Enums\PostDefine;
use App\Models\Article;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ArticleService
{
    protected $articleModel;

    public function __construct(Article $articleModel)
    {
        $this->articleModel = $articleModel;
    }

    public function list()
    {
        return $this->articleModel->latest('id')->get();
    }

    public function showArticle(string $slug)
    {
        return $this->articleModel->where('slug', $slug)->first();
    }

    public function showPost(string $slug)
    {
        $slug = Article::where('slug', $slug)->first();
        $articlesPosts = DB::table('articles_posts')->where('article_id', [$slug->id])->get();
        $manyArticle = [];
        foreach ($articlesPosts as $value) {
            $manyArticle[] = $value->post_id;
        }
        $posts = Post::with('articlePost')->where('active', PostDefine::Active)->whereIn('id', $manyArticle)->latest('id')->paginate(10);

        return $posts;
    }

    public function paginate()
    {
        return $this->articleModel->latest('id')->paginate(10)->toArray();
    }

    public function store(Request $request)
    {
        $this->articleModel->create(
            [
                'name' => $request->name,
                'slug' => Str::slug($request->name),
            ]
        );
    }

    public function show(string $id)
    {
        return $this->articleModel->find($id);
    }

    public function update(Request $request, string $id)
    {
        $article = $this->articleModel->find($id);
        $article->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);
    }

    public function destroy(string $id)
    {
        $article = $this->articleModel->find($id);
        $article->delete();
    }
}
