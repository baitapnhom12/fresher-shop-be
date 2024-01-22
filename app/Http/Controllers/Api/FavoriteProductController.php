<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FavoriteProducts\FavoriteProductRequest;
use App\Services\FavoriteProductService;
use Illuminate\Http\Request;

class FavoriteProductController extends Controller
{
    private $model;

    private $favoriteProductService;

    public function __construct(FavoriteProductService $favoriteProductService)
    {
        $this->favoriteProductService = $favoriteProductService;
    }

    /**
     * Display a listing of the resource.
     */
    public function list()
    {
        return $this->favoriteProductService->listFavoriteProduct();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function addOrRemoveFavoriteProduct(FavoriteProductRequest $request)
    {
        return $this->favoriteProductService->addOrRemoveFavoriteProduct($request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
