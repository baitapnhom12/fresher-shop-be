<?php

namespace App\Http\Controllers;

use App\Enums\PostDefine;
use App\Http\Requests\post\PostRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Services\ArticleService;
use App\Services\PostService;
use Exception;
use Illuminate\Http\Request;

class PostController extends Controller
{
    private $postService;

    private $articleService;

    public function __construct(PostService $postService, ArticleService $articleService)
    {
        $this->postService = $postService;
        $this->articleService = $articleService;
    }

    public function list()
    {
        $enumactive = PostDefine::Active;
        $enumpopular = PostDefine::Popular;
        $posts = $this->postService->list();

        return view('post.list', compact('enumactive', 'posts', 'enumpopular'));
    }

    public function create()
    {
        $articles = $this->articleService->list();
        $enums = $this->postService->enums();

        return view('post.create', compact('articles', 'enums'));
    }

    public function store(PostRequest $request)
    {
        try {
            $this->postService->store($request);

            return new SuccessResource;
        } catch (\Throwable $th) {
            return new ErrorResource;
        }
    }

    public function edit(string $id)
    {
        try {
            $post = $this->postService->edit($id);
            $articlepost = $post->articlePost;
            $articles = $this->articleService->list();
            $enums = $this->postService->enums();
            $enumactive = PostDefine::Active;
            $enumpopular = PostDefine::Popular;

            return view('post.edit', compact('post', 'articles', 'enums', 'articlepost', 'enumactive', 'enumpopular'));
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|max:255|unique:posts,title,' . $id,
            'content' => 'required',
            'active' => 'required',
            'popular' => 'required',
            'articles' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);
        try {
            $this->postService->update($request, $id);

            return back()->with('success', 'Edited successfully');
        } catch (Exception $exception) {
            return back()->with('error', 'Error');
        }
    }

    public function delete(string $id)
    {
        try {
            $this->postService->destroy($id);

            return back()->with('success', 'Edited successfully');
        } catch (Exception $exception) {
            return back()->with('error', 'Error');
        }
    }
}
