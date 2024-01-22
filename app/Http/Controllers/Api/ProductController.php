<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\CreateProductRequest;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $products = $this->productService->list($request);

        if ($request->perPage) {
            return $products;
        } else {
            return response()->json(['data' => $products->getData(),
                'meta' => [
                    'total' => count($products->getData()),
                    'perPage' => count($products->getData()),
                    'currentPage' => 1,
                    'lastPage' => 1,
                    'totalPage' => 1,
                ]], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateProductRequest $request)
    {
        return $this->productService->storeProduct($request);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return $this->productService->detail($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return response()->json('TODO');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->productService->delete($id);
    }

    public function relateProduct(Request $request, $id)
    {
        return $this->productService->relateProduct($request, $id);
    }
}
