<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reviews\CreateReviewPostRequest;
use App\Http\Resources\Post\PostResources;
use App\Http\Resources\SuccessResource;
use App\Services\PostService;

class PostController extends Controller
{
    private $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index()
    {
        return PostResources::collection($this->postService->listActive());
    }

    public function show(string $slug)
    {
        return new PostResources($this->postService->show($slug));
    }

    public function search(string $keyword)
    {
        return PostResources::collection($this->postService->search($keyword));
    }

    public function popular()
    {
        return PostResources::collection($this->postService->popular());
    }

    public function comment(CreateReviewPostRequest $request)
    {
        try {
            $this->postService->comment($request);

            return new SuccessResource;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
