<?php

namespace App\Http\Controllers;

use App\Services\OrderService;

class OrderController extends Controller
{
    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        $orders = $this->orderService->listOrders()->getData();

        return view('orders.list', compact('orders'));
    }

    public function detail($orderCode)
    {
        $order = $this->orderService->orderDetail($orderCode)->getData();

        return view('orders.detail', compact('order'));
    }

    public function approveOrder($orderCode)
    {
        try {
            $result = $this->orderService->approveOrder($orderCode);

            if ($result) {
                return back()->with('success', 'Updated successfully');
            } else {
                return back()->with('error', 'Updated unsuccessfully');
            }
        } catch (\Exception $exception) {
            return back()->with('error', 'Error');
        }
    }

    public function confirmPaid($orderCode)
    {
        try {
            $result = $this->orderService->confirmPaid($orderCode);

            if ($result) {
                return back()->with('success', 'Updated successfully');
            } else {
                return back()->with('error', 'Updated unsuccessfully');
            }
        } catch (\Exception $exception) {
            return back()->with('error', 'Error');
        }
    }
}
