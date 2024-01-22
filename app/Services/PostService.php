<?php

namespace App\Services;

use App\Enums\PostDefine;
use App\Models\ArticlePost;
use App\Models\Post;
use App\Models\Review;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostService
{
    use ImageTrait;

    protected $postModel;

    protected $reviewModel;

    public function __construct(Post $postModel, Review $reviewModel)
    {
        $this->postModel = $postModel;
        $this->reviewModel = $reviewModel;
    }

    public function listActive()
    {
        return $this->postModel->with('articlePost')->where('active', PostDefine::Active)->latest('id')->paginate(10);
    }

    public function list()
    {
        return $this->postModel->with('articlePost')->latest('id')->get();
    }

    public function search(string $keyword)
    {
        return $this->postModel->where('title', 'like', '%' . $keyword . '%')->latest('id')->paginate(10);
    }

    public function popular()
    {
        return $this->postModel->with('articlePost')->where('popular', PostDefine::Popular)->where('active', PostDefine::Active)->latest('id')->paginate(10);
    }

    public function enums()
    {
        return [
            'hidden' => PostDefine::Hidden(),
            'active' => PostDefine::Active(),
            'popular' => PostDefine::Popular(),
            'unpopular' => PostDefine::Unpopular(),
        ];
    }

    public function show(string $slug)
    {
        return $this->postModel->with(
            'articlePost',
            'reviews:id,post_id,user_id,comment,reply_to,created_at',
            'reviews.user:id,name,email',
            'reviews.user.images:id,user_id,main,path'
        )->where('slug', $slug)->first();
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $post = $request->all();
            foreach ($post['articles'] as $value) {
                $post['article_id'] = $value[0];
            }
            $post['slug'] = Str::slug($request->title);
            $post['author'] = Auth::user()->name;
            $image = $request->file('image');
            if (!empty($image)) {
                $path = 'posts/';
                $fileData = $this->uploads($image, $path);
                $post['image'] = $fileData['filePath'];
            }
            $this->postModel->create($post)->articlePost()->attach($post['articles']);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
        }
    }

    public function edit(string $id)
    {
        return $this->postModel->find($id);
    }

    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();
            $post = $this->postModel::find($id);
            $data = $request->all();
            $data['slug'] = Str::slug($request->title);
            $data['author'] = Auth::user()->name;
            if ($image = $request->file('image')) {
                $path = 'posts/';
                $fileData = $this->uploads($image, $path);
                $data['image'] = $fileData['filePath'];
                $url = $post->pluck('image')->first();
                Storage::disk('cloudinary')->delete($url);
            }
            foreach ($data['articles'] as $value) {
                $post->article_id = $value[0];
            }
            $post->update($data);
            $post->articlePost()->sync($data['articles']);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }

    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $post = $this->postModel::find($id);
            $url = $post->pluck('image')->first();
            if (Storage::disk('cloudinary')->exists($url)) {
                Storage::disk('cloudinary')->delete($url);
            }
            ArticlePost::whereIn('post_id', [$post->id])->delete();
            $post->delete();
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
        }
    }

    public function comment(Request $request)
    {
        $this->reviewModel->create([
            'user_id' => auth()->user()->id,
            'post_id' => $request->postId,
            'comment' => $request->comment,
            'reply_to' => $request->replyTo,
        ]);

        return response()->json('Created successfully', 201);
    }
}
