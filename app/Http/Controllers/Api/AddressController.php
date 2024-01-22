<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\address\AddressRequest;
use App\Http\Resources\Addresses\AddressResource;
use App\Services\AddressService;
use Illuminate\Support\Facades\Response;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    public function list()
    {
        return AddressResource::collection($this->addressService->list());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddressRequest $request)
    {
        try {
            $this->addressService->store($request->all());

            return Response::json([
                'message' => 'Created successfully',
            ], 200);
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
    public function show(string $id)
    {
        $result = $this->addressService->show($id);
        if (!$result) {
            return Response::json([
                'message' => 'Addresses not found',
            ], 404);
        }

        return new AddressResource($result);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AddressRequest $request, string $id)
    {
        try {
            $this->addressService->update($request->all(), $id);

            return Response::json([
                'message' => 'Updated successfully',
            ], 200);
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
    public function destroy(string $id)
    {
        try {
            $this->addressService->destroy($id);

            return Response::json([
                'message' => 'Deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return Response::json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
