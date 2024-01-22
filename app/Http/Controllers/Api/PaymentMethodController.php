<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\paymentmethods\PaymentMethodRequest;
use App\Services\PaymentMethodService;
use Illuminate\Support\Facades\Response;

class PaymentMethodController extends Controller
{
    private $paymentMethodService;

    public function __construct(PaymentMethodService $paymentMethodService)
    {
        $this->paymentMethodService = $paymentMethodService;
    }

    public function index()
    {
        $paymentMethods = $this->paymentMethodService->list()->toArray();
        $paymentMethods = collect($paymentMethods)->map(fn ($paymentMethod) => [
            'id' => $paymentMethod['id'],
            'provider' => $paymentMethod['provider'],
            'accountNumber' => $paymentMethod['account_number'],
        ]
        );

        return response()->json($paymentMethods);
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
    public function store(PaymentMethodRequest $request)
    {
        try {
            $this->paymentMethodService->store($request);

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
        return $this->paymentMethodService->show($id);
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
    public function update(PaymentMethodRequest $request, string $id)
    {
        try {
            $this->paymentMethodService->update($request, $id);

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
            $this->paymentMethodService->destroy($id);

            return Response::json([
                'message' => 'Deleted successfully',
            ], 200);
        } catch (\Throwable $e) {
            return Response::json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
