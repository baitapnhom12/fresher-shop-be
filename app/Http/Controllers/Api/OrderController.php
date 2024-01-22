<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\CheckoutRequest;
use App\Services\OrderService;

class OrderController extends Controller
{
    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function checkout(CheckoutRequest $request)
    {
        return $this->orderService->checkout($request);
    }

    public function orderDetailUser($orderCode)
    {
        return $this->orderService->orderDetail($orderCode);
    }

    public function listOrderOfUser()
    {
        return $this->orderService->listOrders();
    }

    public function paymentOrder($orderCode)
    {
        return $this->orderService->paymentOrder($orderCode);
    }

    public function checkPurchased($productId)
    {
        return $this->orderService->checkPurchased($productId);
    }
}
