<?php

namespace App\Http\Controllers\Api;

use App\Enums\DiscountDefine;
use App\Http\Controllers\Controller;
use App\Http\Requests\Carts\AddToCartRequest;
use App\Http\Requests\Carts\UpdateCartRequest;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Quantity;
use App\Models\Size;
use Carbon\Carbon;

class CartController extends Controller
{
    public function list()
    {
        try {
            $carts = Cart::with(
                'product:id,name,slug',
                'product.images:id,product_id,path,main',
                'product.discountProducts',
                'product.discountProducts.discount:id,name,percent,active',
            )->where('user_id', auth()->user()->id)->get();
            $size = new Size;

            $carts = $carts->map(function ($cart) use ($size) {
                $price = Quantity::where([
                    'product_id' => $cart['product_id'],
                    'size_id' => $cart['size_id'],
                ])->first('price');

                $discount = $cart['product']['discountProducts']->map(fn ($item) => [
                    'id' => $item['id'],
                    'discountId' => $item['discount_id'],
                    'name' => $item['discount']['name'],
                    'percent' => $item['discount']['percent'],
                    'active' => $item['discount']['active'],
                    'promotionTerm' => $item['promotion_term'],
                    'usageCount' => $item['usage_count'],
                ]);

                $discount = collect($discount)->filter(function ($discount) {
                    return $discount['active'] === DiscountDefine::Active && $discount['promotionTerm'] > Carbon::now();
                })->sortByDesc('percent')->first();

                return [
                    'id' => $cart['id'],
                    'sizeId' => $cart['size_id'],
                    'sizeName' => $size->getName($cart['size_id']),
                    'quantity' => $cart['quantity'],
                    'productId' => $cart['product']['id'],
                    'productName' => $cart['product']['name'],
                    'slug' => $cart['product']['slug'],
                    'price' => $price->price ?? null,
                    'images' => collect($cart['product']['images'])->map(fn ($image) => [
                        'id' => $image['id'],
                        'path' => $image['path'],
                        'main' => $image['main'] ? true : false,
                    ]),
                    'discountId' => $discount['discountId'] ?? null,
                    'discountPercent' => $discount['percent'] ?? null,
                    'discountExpiredAt' => $discount['promotionTerm'] ?? null,
                    'discountUsageCount' => $discount['usageCount'] ?? null,
                ];
            });

            return response()->json($carts);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function addToCart(AddToCartRequest $request)
    {
        try {
            $product = Product::find($request->productId, ['id']);
            if (!$product) {
                return response()->json(['message' => 'Not found'], 404);
            }

            $size = Size::find($request->sizeId, ['id']);
            if (!$size) {
                return response()->json(['message' => 'Not found'], 404);
            }

            $cart = Cart::where([
                'user_id' => auth()->user()->id,
                'product_id' => $request->productId,
                'size_id' => $request->sizeId,
            ])->first();

            if ($cart) {
                $result = $cart->update([
                    'quantity' => $cart->quantity + $request->quantity,
                ]);

                if ($result) {
                    return response()->json(['message' => 'success'], 200);
                }
            }

            $result = Cart::create([
                'user_id' => auth()->user()->id,
                'product_id' => $request->productId,
                'size_id' => $request->sizeId,
                'quantity' => $request->quantity,
            ]);

            if ($result) {
                return response()->json(['message' => 'success'], 201);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function removeFromCart($id)
    {
        try {
            $cart = Cart::find($id, ['id']);
            if (!$cart) {
                return response()->json(['message' => 'Not found'], 404);
            }

            $cart->delete();
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateCart(UpdateCartRequest $request, $id)
    {
        try {
            $cart = Cart::find($id);
            if (!$cart) {
                return response()->json(['message' => 'Not found'], 404);
            }

            $quantity = Quantity::where([
                'product_id' => $request->productId,
                'size_id' => $request->sizeId,
            ])->first(['id', 'quantity']);

            if ($request->quantity > $quantity->quantity) {
                return response()->json(['message' => 'Product quantity is not enough'], 400);
            }

            $cart->update([
                'size_id' => $request->sizeId,
                'quantity' => $request->quantity,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function removeCart()
    {
        try {
            Cart::where('user_id', auth()->user()->id)->delete();
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
