<?php

namespace App\Http\Controllers;

use App\Http\Requests\concentration\ConcentrationRequest;
use App\Http\Resources\Concentration\ConcentrationResource;
use App\Http\Resources\SuccessResource;
use App\Services\ConcentrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class ConcentrationController extends Controller
{
    private $concentrationService;

    public function __construct(ConcentrationService $concentrationService)
    {
        $this->concentrationService = $concentrationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function viewList()
    {
        return view('concentration.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function list()
    {
        try {
            return ConcentrationResource::collection($this->concentrationService->list());
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
    public function store(ConcentrationRequest $request)
    {
        try {
            $data = $request->all();
            $data['slug'] = Str::slug($request->name);
            $this->concentrationService->store($data);

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
        $result = $this->concentrationService->show($request->id);
        if (!$result) {
            return Response::json([
                'message' => 'Discounts not found',
            ], 404);
        }

        return new ConcentrationResource($result);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request->id;
        $request->validate([
            'name' => 'required|max:50|unique:concentrations,name,' . $id,
        ]);
        try {
            $data = $request->all();
            $data['slug'] = Str::slug($request->name);
            $this->concentrationService->update($data, $id);

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
            $this->concentrationService->destroy($request->id);

            return new SuccessResource;
        } catch (\Throwable $e) {
            return Response::json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
