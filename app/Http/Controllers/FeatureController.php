<?php

namespace App\Http\Controllers;

use App\Http\Requests\feature\FeatureRequest;
use App\Http\Resources\SuccessResource;
use App\Services\FeatureService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class FeatureController extends Controller
{
    private $featureService;

    public function __construct(FeatureService $featureService)
    {
        $this->featureService = $featureService;
    }

    /**
     * Display a listing of the resource.
     */
    public function viewList()
    {
        return view('feature.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function list()
    {
        try {
            return Response::json([
                'data' => $this->featureService->list(),
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
    public function store(FeatureRequest $request)
    {
        try {
            $this->featureService->store($request);

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
        $result = $this->featureService->show($request->id);

        return Response::json([
            'data' => $result,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FeatureRequest $request)
    {
        $id = $request->id;
        try {
            $this->featureService->update($request, $id);

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
            $this->featureService->destroy($request->id);

            return new SuccessResource;
        } catch (\Throwable $e) {
            return Response::json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);
        }
    }
}
