<?php

namespace App\Http\Controllers;

use App\Http\Resources\SuccessResource;
use App\Services\QuestionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class QuestionController extends Controller
{
    private $questionService;

    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function viewList()
    {
        return view('question.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function list()
    {
        try {
            return Response::json([
                'data' => $this->questionService->list(),
            ]);
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
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|unique:questions,name',
            'content' => 'required',
        ]);
        try {
            $this->questionService->store($request);

            return new SuccessResource();
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
        $result = $this->questionService->show($request->id);

        return Response::json([
            'data' => $result,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request->id;
        $request->validate([
            'name' => 'required|max:255|unique:questions,name,' . $id,
            'content' => 'required',
        ]);
        try {
            $this->questionService->update($request, $id);

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
            $this->questionService->destroy($request->id);

            return new SuccessResource;
        } catch (\Throwable $e) {
            return Response::json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);
        }
    }
}
