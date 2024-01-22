<?php

namespace App\Http\Controllers;

use App\Http\Requests\size\SizeRequest;
use App\Http\Resources\Size\SizeResource;
use App\Http\Resources\SuccessResource;
use App\Services\SizeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SizeController extends Controller
{
    private $sizeService;

    public function __construct(SizeService $sizeService)
    {
        $this->sizeService = $sizeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function viewList()
    {
        return view('size.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function list()
    {
        try {
            return SizeResource::collection($this->sizeService->list());
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getCode(),
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SizeRequest $request)
    {
        try {
            $data = $request->all();
            $this->sizeService->store($data);

            return new SuccessResource;
        } catch (\Throwable $e) {
            return Response::json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $result = $this->sizeService->show($request->id);
        if (!$result) {
            return Response::json([
                'message' => 'Discounts not found',
            ], 404);
        }

        return new SizeResource($result);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request->id;
        $request->validate([
            'name' => 'required|max:50|unique:sizes,name,' . $id,

        ]);
        try {
            $data = $request->all();
            $this->sizeService->update($data, $id);

            return new SuccessResource;
        } catch (\Throwable $e) {
            return Response::json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {
        try {
            $this->sizeService->destroy($request->id);

            return new SuccessResource;
        } catch (\Throwable $e) {
            return Response::json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
