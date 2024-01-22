<?php

namespace App\Services;

use App\Models\PaymentMethod;

class PaymentMethodService
{
    protected $paymentMethodModel;

    public function __construct(PaymentMethod $paymentMethodModel)
    {
        $this->paymentMethodModel = $paymentMethodModel;
    }

    public function list()
    {
        return $this->paymentMethodModel->where('user_id', auth()->user()->id)->latest('id')->get(['id', 'user_id', 'type', 'provider', 'provider', 'account_number']);
    }

    public function store($request)
    {
        $this->paymentMethodModel->create([
            'provider' => $request->provider,
            'account_number' => $request->accountNumber,
            'user_id' => auth()->user()->id,
        ]);
    }

    public function show(string $id)
    {
        $paymentMethod = $this->paymentMethodModel->find($id);
        if (!$paymentMethod) {
            return response()->json('Payment method not found', 400);
        }

        return response()->json([
            'id' => $paymentMethod['id'],
            'provider' => $paymentMethod['provider'],
            'accountNumber' => $paymentMethod['account_number'],
        ], 200);
    }

    public function update($request, $id)
    {
        $paymentMethod = $this->paymentMethodModel->find($id);
        if (!$paymentMethod) {
            return response()->json('Payment method not found', 400);
        }

        $paymentMethod->update([
            'provider' => $request->provider,
            'account_number' => $request->accountNumber,
        ]);
    }

    public function destroy(string $id)
    {
        $paymentMethod = $this->paymentMethodModel->find($id);
        if (!$paymentMethod) {
            return response()->json('Payment method not found', 400);
        }

        $paymentMethod->delete();
    }
}
