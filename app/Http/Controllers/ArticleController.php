<?php

namespace App\Http\Controllers;

use App\Http\Requests\article\ArticleRequest;
use App\Http\Resources\Article\ArticleResources;
use App\Http\Resources\SuccessResource;
use App\Services\ArticleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ArticleController extends Controller
{
    private $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    /**
     * Display a listing of the resource.
     */
    public function viewList()
    {
        return view('article.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function list()
    {
        try {
            return ArticleResources::collection($this->articleService->list());
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getCode(),
                'message' => $th->getMessage(),
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleRequest $request)
    {
        try {
            $this->articleService->store($request);

            return new SuccessResource;
        } catch (\Throwable $e) {
            return Response::json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $result = $this->articleService->show($request->id);
        if (!$result) {
            return Response::json([
                'message' => 'Discounts not found',
            ]);
        }

        return new ArticleResources($result);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request->id;
        $request->validate([
            'name' => 'required|max:50|unique:articles,name,' . $id,

        ]);
        try {
            $this->articleService->update($request, $id);

            return new SuccessResource;
        } catch (\Throwable $e) {
            return Response::json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {
        try {
            $this->articleService->destroy($request->id);

            return new SuccessResource;
        } catch (\Throwable $e) {
            return Response::json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);
        }
    }
}
