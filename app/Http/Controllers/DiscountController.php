<?php

namespace App\Http\Controllers;

use App\Http\Requests\discount\DiscountRequest;
use App\Http\Resources\Discount\DiscountResource;
use App\Http\Resources\SuccessResource;
use App\Services\DiscountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DiscountController extends Controller
{
    private $discountService;

    public function __construct(DiscountService $discountService)
    {
        $this->discountService = $discountService;
    }

    /**
     * Display a listing of the resource.
     */
    public function viewList()
    {
        return view('discount.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function list()
    {
        try {
            return DiscountResource::collection($this->discountService->list());
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
    public function store(DiscountRequest $request)
    {
        try {
            $data = $request->all();
            $this->discountService->store($data);

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
        $result = $this->discountService->show($request->id);
        if (!$result) {
            return Response::json([
                'message' => 'Discounts not found',
            ], 404);
        }

        return new DiscountResource($result);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request->id;
        $request->validate([
            'percent' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'name' => 'required|max:50|unique:discounts,name,' . $id,
        ]);
        try {
            $data = $request->all();
            $this->discountService->update($data, $id);

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
            $this->discountService->destroy($request->id);

            return new SuccessResource;
        } catch (\Throwable $e) {
            return Response::json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
