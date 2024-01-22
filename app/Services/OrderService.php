<?php

namespace App\Services;

use App\Enums\CouponDefine;
use App\Enums\DiscountDefine;
use App\Enums\ImageDefine;
use App\Enums\OrderDefine;
use App\Enums\PaymentDefine;
use App\Enums\PaymentMethodDefine;
use App\Enums\QuantityDefine;
use App\Enums\UserRole;
use App\Models\Coupon;
use App\Models\DiscountProduct;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Quantity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    private $model;

    public function __construct(Order $model)
    {
        $this->model = $model;
    }

    public function checkout($request)
    {
        try {
            DB::beginTransaction();
            $order = null;
            if ($request->paymentMethod == PaymentMethodDefine::BankTransfer && $request->provider && $request->accountNumber) {
                $payment = Payment::create([
                    'provider' => $request->provider,
                    'account_number' => $request->accountNumber,
                    'amount' => $request->total,
                    'status' => PaymentDefine::Unpaided,
                ]);

                $order = $payment->orders()->create([
                    'sku' => Str::sku($request->receiver),
                    'status' => OrderDefine::Pending,
                    'payment_method' => $request->paymentMethod,
                    'receiver' => $request->receiver,
                    'phone' => $request->phone,
                    'shipping_address' => $request->shippingAddress,
                    'order_date' => now(),
                    'user_id' => auth()->user()->id,
                    'shipping_fee' => $request->shippingFee,
                    'discount' => $request->discount,
                    'total' => $request->total,
                    'subtotal' => $request->subTotal,
                ]);
            } else {
                $payment = Payment::create([
                    'provider' => PaymentDefine::MethodCOD,
                    'account_number' => PaymentDefine::MethodCOD,
                    'amount' => $request->total,
                    'status' => PaymentDefine::Unpaided,
                ]);

                $order = $payment->orders()->create([
                    'sku' => Str::sku($request->receiver),
                    'status' => OrderDefine::Pending,
                    'payment_method' => PaymentMethodDefine::COD,
                    'receiver' => $request->receiver,
                    'phone' => $request->phone,
                    'shipping_address' => $request->shippingAddress,
                    'order_date' => now(),
                    'user_id' => auth()->user()->id,
                    'shipping_fee' => $request->shippingFee,
                    'discount' => $request->discount,
                    'total' => $request->total,
                    'subtotal' => $request->subTotal,
                ]);
            }

            $orderProducts = $request->products;

            if (!empty($orderProducts)) {
                $orderPrData = [];
                foreach ($orderProducts as $orderProduct) {
                    if ($orderProduct['discountId']) {
                        $discountPr = DiscountProduct::where([
                            'product_id' => $orderProduct['productId'],
                            'discount_id' => $orderProduct['discountId'],
                        ])->where('usage_count', '>', DiscountDefine::EndOfUsesage)
                            ->where('promotion_term', '>', now())->first();
                        if (!$discountPr) {
                            return response()->json('This discount has expired', 400);
                        }
                        $usageCount = ($discountPr->usage_count - $orderProduct['quantity']) > DiscountDefine::EndOfUsesage ? $discountPr->usage_count - $orderProduct['quantity'] : DiscountDefine::EndOfUsesage;
                        $discountPr->update(['usage_count' => $usageCount]);
                    }

                    $quantityPr = Quantity::where([
                        'product_id' => $orderProduct['productId'],
                        'size_id' => $orderProduct['sizeId'],
                    ])->where('quantity', '>', QuantityDefine::SoldOut)->first();
                    if (!$quantityPr) {
                        return response()->json('This product is out of stock', 400);
                    }
                    $quantity = ($quantityPr->quantity - $orderProduct['quantity']) > QuantityDefine::SoldOut ? $quantityPr->quantity - $orderProduct['quantity'] : QuantityDefine::SoldOut;
                    $quantityPr->update(['quantity' => $quantity]);

                    $orderPrData[] = [
                        'product_id' => $orderProduct['productId'],
                        'quantity' => $orderProduct['quantity'],
                        'price' => $orderProduct['price'],
                        'size' => $orderProduct['sizeName'],
                    ];
                }

                $order->orderProducts()->createMany($orderPrData);
            }

            if ($request->couponCode) {
                $coupon = Coupon::where('sku', $request->couponCode)->where('usage_count', '>', CouponDefine::EndOfUsesage)
                    ->where('expired_at', '>', now())->first();
                if (!$coupon) {
                    return response()->json('This coupon has expired', 400);
                }
                $couponUsageCount = ($coupon->usage_count - 1) > CouponDefine::EndOfUsesage ? $coupon->usage_count - 1 : CouponDefine::EndOfUsesage;
                $coupon->update(['usage_count' => $couponUsageCount]);
            }

            DB::commit();

            if ($order) {
                return response()->json([
                    'orderCode' => $order->sku,
                    'status' => $order->status,
                    'orderDate' => $order->order_date,
                    'paymentMethod' => $order->payment_method,
                    'paymentStatus' => $order->payment->status,
                    'total' => $order->total,
                ], 201);
            }
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function orderDetail($orderCode)
    {
        try {
            $order = null;
            if (auth()->user()->role == UserRole::User) {
                $order = $this->model->with(
                    'orderProducts:id,order_id,product_id,quantity,price,size',
                    'orderProducts.product:id,name,slug',
                    'orderProducts.product.images:id,product_id,path,main',
                    'payment:id,order_id,amount,account_number,provider,status',
                    'user:id,email'
                )
                    ->where([
                        'user_id' => auth()->user()->id,
                        'sku' => $orderCode,
                    ])->first([
                        'id',
                        'sku',
                        'status',
                        'shipping_fee',
                        'discount',
                        'user_id',
                        'payment_id',
                        'total',
                        'payment_method',
                        'receiver',
                        'order_date',
                        'phone',
                        'shipping_address',
                        'subtotal',
                    ])->toArray();
            }

            if (auth()->user()->role == UserRole::Administrator) {
                $order = $this->model->with(
                    'orderProducts:id,order_id,product_id,quantity,price,size',
                    'orderProducts.product:id,name,slug',
                    'orderProducts.product.images:id,product_id,path,main',
                    'payment:id,order_id,amount,account_number,provider,status',
                    'user:id,email'
                )
                    ->where([
                        'sku' => $orderCode,
                    ])->first([
                        'id',
                        'sku',
                        'status',
                        'shipping_fee',
                        'discount',
                        'user_id',
                        'payment_id',
                        'total',
                        'payment_method',
                        'receiver',
                        'order_date',
                        'phone',
                        'shipping_address',
                        'subtotal',
                    ])->toArray();
            }

            if (!$order) {
                return response()->json('Order not found', 400);
            }

            $orderDetail = collect($order);
            $orderProduct = collect($orderDetail['order_products'])->map(function ($orderPr) {
                $image = collect($orderPr['product']['images'])->filter(fn ($img) => $img['main'] == ImageDefine::ImageMain)->first();

                return [
                    'id' => $orderPr['id'],
                    'productId' => $orderPr['product_id'],
                    'name' => $orderPr['product']['name'],
                    'slug' => $orderPr['product']['slug'],
                    'size' => $orderPr['size'],
                    'price' => $orderPr['price'],
                    'quantity' => $orderPr['quantity'],
                    'image' => $image['path'] ?? null,
                ];
            });

            $payment = $orderDetail['payment_id'] ? [
                'id' => $orderDetail['payment']['id'],
                'provider' => $orderDetail['payment']['provider'],
                'accountNumber' => $orderDetail['payment']['account_number'],
                'amount' => $orderDetail['payment']['amount'],
                'status' => $orderDetail['payment']['status'],
            ] : null;

            return response()->json([
                'id' => $orderDetail['id'],
                'code' => $orderDetail['sku'],
                'status' => $orderDetail['status'],
                'shippingFee' => $orderDetail['shipping_fee'],
                'discount' => $orderDetail['discount'],
                'paymentMethod' => $orderDetail['payment_method'],
                'total' => $orderDetail['total'],
                'orderDate' => $orderDetail['order_date'],
                'products' => $orderProduct,
                'receiver' => $orderDetail['receiver'],
                'phone' => $orderDetail['phone'],
                'shippingAddress' => $orderDetail['shipping_address'],
                'payment' => $payment ?? PaymentDefine::Unpaided,
                'accountOrder' => $order['user']['email'],
                'subTotal' => $orderDetail['subtotal'],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function listOrders()
    {
        try {
            $orders = null;
            if (auth()->user()->role == UserRole::User) {
                $orders = $this->model->with(
                    'orderProducts:id,order_id,product_id,quantity,price,size',
                    'orderProducts.product:id,name,slug',
                    'orderProducts.product.images:id,product_id,path,main',
                    'payment:id,order_id,amount,account_number,provider,status',
                    'user:id,email'
                )
                    ->where('user_id', auth()->user()->id)
                    ->orderByDesc('created_at')
                    ->get([
                        'id',
                        'sku',
                        'status',
                        'shipping_fee',
                        'discount',
                        'user_id',
                        'total',
                        'payment_method',
                        'payment_id',
                        'order_date',
                        'receiver',
                        'user_id',
                    ])->toArray();
            }

            if (auth()->user()->role == UserRole::Administrator) {
                $orders = $this->model->with(
                    'orderProducts:id,order_id,product_id,quantity,price,size',
                    'orderProducts.product:id,name,slug',
                    'orderProducts.product.images:id,product_id,path,main',
                    'payment:id,order_id,amount,account_number,provider,status',
                    'user:id,email'
                )->orderByDesc('created_at')
                    ->get([
                        'id',
                        'sku',
                        'status',
                        'shipping_fee',
                        'discount',
                        'user_id',
                        'total',
                        'payment_method',
                        'payment_id',
                        'order_date',
                        'receiver',
                        'user_id',
                    ])->toArray();
            }

            $orders = collect($orders)->map(function ($order) {
                $payment = $order['payment_id'] ?
                    $order['payment']['status']
                    : null;

                $payment = [
                    'id' => $order['payment']['id'] ?? null,
                    'provider' => $order['payment']['provider'] ?? null,
                    'accountNumber' => $order['payment']['account_number'] ?? null,
                    'amount' => $order['payment']['amount'] ?? null,
                    'status' => $order['payment']['status'] ?? null,
                ];

                $orderProduct = collect($order['order_products'])->map(function ($orderPr) {
                    $image = collect($orderPr['product']['images'])->filter(fn ($img) => $img['main'] == ImageDefine::ImageMain)->first();

                    return [
                        'id' => $orderPr['id'],
                        'productId' => $orderPr['product_id'],
                        'name' => $orderPr['product']['name'],
                        'slug' => $orderPr['product']['slug'],
                        'size' => $orderPr['size'],
                        'price' => $orderPr['price'],
                        'quantity' => $orderPr['quantity'],
                        'image' => $image['path'] ?? null,
                    ];
                });

                return [
                    'id' => $order['id'],
                    'code' => $order['sku'],
                    'status' => $order['status'],
                    'shippingFee' => $order['shipping_fee'],
                    'discount' => $order['discount'],
                    'paymentMethod' => $order['payment_method'],
                    'total' => $order['total'],
                    'orderDate' => $order['order_date'],
                    'products' => $orderProduct,
                    'payment' => $payment ?? 0,
                    'receiver' => $order['receiver'],
                    'accountOrder' => $order['user']['email'],
                ];
            });

            return response()->json($orders);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function paymentOrder($orderCode)
    {
        try {
            $order = $this->model->where([
                'user_id' => auth()->user()->id,
                'sku' => $orderCode,
            ])->first([
                'id',
                'sku',
                'status',
                'payment_id',
            ]);

            if (!$order) {
                return response()->json('Order not found', 400);
            }

            if ($order->payment_id) {
                DB::beginTransaction();
                $order->update(['status' => OrderDefine::Approved]);
                $order->payment()->update(['status' => PaymentDefine::Paided]);

                DB::commit();

                return response()->json('Payment order successfully', 200);
            }

            return response()->json(400);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function approveOrder($orderCode)
    {
        try {
            $order = $this->model->where('sku', $orderCode)->first([
                'id',
                'sku',
                'status',
            ]);
            if (!$order) {
                return response()->json('Order not found', 400);
            }

            $order->update(['status' => 1]);

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function confirmPaid($orderCode)
    {
        try {
            $order = $this->model->with(
                'payment:id,status',
                'orderProducts:id,order_id,product_id,quantity',
                'orderProducts.product:id,quantity_sold'
            )->where([
                'sku' => $orderCode,
                'status' => OrderDefine::Approved,
            ])
                ->first([
                    'id',
                    'sku',
                    'status',
                    'payment_id',
                    'payment_method',
                ]);

            if (!$order) {
                return response()->json('Order not found', 400);
            }

            DB::beginTransaction();
            foreach ($order->orderProducts as $orderProduct) {
                $product = $orderProduct->product;
                $product->quantity_sold += $orderProduct->quantity;
                $product->save();
            }

            if ($order->payment_method == PaymentMethodDefine::COD && $order->payment && $order->payment->status == PaymentDefine::Unpaided) {
                $order->update(['status' => OrderDefine::Delivered]);
                $order->payment()->update(['status' => PaymentDefine::Paided]);
            }

            if ($order->payment_method == PaymentMethodDefine::BankTransfer && $order->payment && $order->payment->status == PaymentDefine::Paided) {
                $order->update(['status' => OrderDefine::Delivered]);
            }

            DB::commit();

            return true;
        } catch (\Throwable $e) {
            DB::rollBack();

            return false;
        }
    }

    public function checkPurchased($productId)
    {
        try {
            $product = Product::find($productId, ['id']);
            if (!$product) {
                return response()->json('Product not found', 400);
            }

            $order = Order::where('user_id', auth()->user()->id)
                ->whereHas('payment', function ($query) {
                    $query->where('status', PaymentDefine::Paided);
                })->whereHas('orderProducts', function ($query) use ($productId) {
                    $query->where('product_id', $productId);
                })->orderByDesc('created_at')
                ->first('id');

            if (!$order) {
                return response()->json('You must purchase this product to be able to rate it', 400);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
