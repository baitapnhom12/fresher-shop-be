<?php

namespace App\Services;

use App\Enums\HomeDefine;
use App\Enums\OrderDefine;
use App\Enums\PaymentDefine;
use App\Enums\UserRole;
use App\Models\FavoriteProduct;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;

class HomeService
{
    public function getDashBoardData()
    {
        try {
            $users = User::with('images:id,user_id,path,main')->where('role', UserRole::User)->orderByDesc('created_at')
                ->get(['id', 'name', 'created_at']);

            $products = Product::with('images:id,product_id,path,main', 'brand:id,name', 'concentration:id,name', 'quantities')
                ->orderByDesc('created_at')->get(['id', 'name', 'brand_id', 'concentration_id', 'quantity_sold']);

            $lastestOrders = Order::with('orderProducts:id,order_id,product_id',
                'orderProducts.product:id,name')->orderByDesc('created_at')->limit(HomeDefine::LimitData)->get([
                    'id',
                    'sku',
                    'status',
                    'total',
                ]);

            $orders = Order::with('payment:id,order_id,amount,account_number,provider,status')
                ->where('status', OrderDefine::Delivered)
                ->whereHas('payment', function ($query) {
                    $query->where('status', PaymentDefine::Paided);
                })->get(['id', 'status', 'total', 'payment_id']);

            $revenueTotal = 0;
            foreach ($orders as $orderTotal) {
                $revenueTotal += $orderTotal->total;
            }

            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
            $lastYear = $currentYear - 1;
            $sevenDaysAgo = Carbon::now()->subDays(6);
            $fourteenDaysAgo = Carbon::now()->subDays(13);

            $ordersThisYear = Order::with('payment:id,order_id,amount,account_number,provider,status')
                ->where('status', OrderDefine::Delivered)
                ->whereHas('payment', function ($query) {
                    $query->where('status', PaymentDefine::Paided);
                })
                ->whereYear('updated_at', $currentYear)
                ->get(['id', 'status', 'total', 'payment_id', 'updated_at']);

            $ordersLastYear = Order::with('payment:id,order_id,amount,account_number,provider,status')
                ->where('status', OrderDefine::Delivered)
                ->whereHas('payment', function ($query) {
                    $query->where('status', PaymentDefine::Paided);
                })
                ->whereYear('updated_at', $lastYear)
                ->get(['id', 'status', 'total', 'payment_id', 'updated_at']);

            $totalOrdersThisYear = $ordersThisYear->groupBy(function ($order) {
                return Carbon::parse($order->updated_at)->format('M');
            })->map->sum('total');

            $revenueThisYear = 0;
            foreach ($totalOrdersThisYear as $thisYear) {
                $revenueThisYear += $thisYear;
            }

            $totalOrdersLastYear = $ordersLastYear->groupBy(function ($order) {
                return Carbon::parse($order->updated_at)->format('M');
            })->map->sum('total');

            $totalThisMonthThisYear = Order::with('payment:id,order_id,amount,account_number,provider,status')
                ->where('status', OrderDefine::Delivered)
                ->whereHas('payment', function ($query) {
                    $query->where('status', PaymentDefine::Paided);
                })->whereYear('updated_at', $currentYear)
                ->whereMonth('updated_at', $currentMonth)
                ->sum('total');

            $totalThisMonthLastYear = Order::with('payment:id,order_id,amount,account_number,provider,status')
                ->where('status', OrderDefine::Delivered)
                ->whereHas('payment', function ($query) {
                    $query->where('status', PaymentDefine::Paided);
                })->whereYear('updated_at', $lastYear)
                ->whereMonth('updated_at', $currentMonth)
                ->sum('total');

            $percentage = 0;
            if ($totalThisMonthLastYear !== 0) {
                $percentage = ($totalThisMonthThisYear - $totalThisMonthLastYear) / $totalThisMonthLastYear * 100;
            } else {
                $percentage = 100;
            }

            $revenueWithYear = [
                'jan' => $totalOrdersThisYear['Jan'] ?? 0,
                'feb' => $totalOrdersThisYear['Feb'] ?? 0,
                'mar' => $totalOrdersThisYear['Mar'] ?? 0,
                'apr' => $totalOrdersThisYear['Apr'] ?? 0,
                'may' => $totalOrdersThisYear['May'] ?? 0,
                'jun' => $totalOrdersThisYear['Jun'] ?? 0,
                'jul' => $totalOrdersThisYear['Jul'] ?? 0,
                'aug' => $totalOrdersThisYear['Aug'] ?? 0,
                'sep' => $totalOrdersThisYear['Sep'] ?? 0,
                'oct' => $totalOrdersThisYear['Oct'] ?? 0,
                'nov' => $totalOrdersThisYear['Nov'] ?? 0,
                'dec' => $totalOrdersThisYear['Dec'] ?? 0,
                'janLastYear' => $totalOrdersLastYear['Jan'] ?? 0,
                'febLastYear' => $totalOrdersLastYear['Feb'] ?? 0,
                'marLastYear' => $totalOrdersLastYear['Mar'] ?? 0,
                'aprLastYear' => $totalOrdersLastYear['Apr'] ?? 0,
                'mayLastYear' => $totalOrdersLastYear['May'] ?? 0,
                'junLastYear' => $totalOrdersLastYear['Jun'] ?? 0,
                'julLastYear' => $totalOrdersLastYear['Jul'] ?? 0,
                'augLastYear' => $totalOrdersLastYear['Aug'] ?? 0,
                'sepLastYear' => $totalOrdersLastYear['Sep'] ?? 0,
                'octLastYear' => $totalOrdersLastYear['Oct'] ?? 0,
                'novLastYear' => $totalOrdersLastYear['Nov'] ?? 0,
                'decLastYear' => $totalOrdersLastYear['Dec'] ?? 0,
                'percentageMouth' => round($percentage, 2),
            ];

            $ordersSevenDaysAgo = Order::with('payment:id,order_id,amount,account_number,provider,status')
                ->where('status', OrderDefine::Delivered)
                ->whereHas('payment', function ($query) {
                    $query->where('status', PaymentDefine::Paided);
                })
                ->where('updated_at', '>=', $sevenDaysAgo)
                ->get(['id', 'status', 'total', 'payment_id', 'updated_at']);

            $groupedOrders = $ordersSevenDaysAgo->groupBy(function ($order) {
                return Carbon::parse($order->updated_at)->format('Y-m-d');
            });

            $sevenDayData = [];
            $sevenDayRevenue = 0;

            foreach ($groupedOrders as $date => $dateOrders) {
                $revenue = $dateOrders->sum('total');
                $sevenDayRevenue += $revenue;
                $totalOrders = $dateOrders->count();

                $sevenDayData[$date] = [
                    'revenue' => $revenue ?? 0,
                    'totalOrder' => $totalOrders ?? 0,
                ];
            }

            $totalFourteenDaysAgo = Order::with('payment:id,order_id,amount,account_number,provider,status')
                ->where('status', OrderDefine::Delivered)
                ->whereHas('payment', function ($query) {
                    $query->where('status', PaymentDefine::Paided);
                })
                ->whereDate('updated_at', '>=', $fourteenDaysAgo)
                ->whereDate('updated_at', '<=', $sevenDaysAgo)
                ->sum('total');

            $percentageForteenAgo = 0;
            if ($totalFourteenDaysAgo !== 0) {
                $percentageForteenAgo = ($sevenDayRevenue - $totalFourteenDaysAgo) / $totalFourteenDaysAgo * 100;
            } else {
                $percentageForteenAgo = 100;
            }

            $reviews = collect(Review::all('id', 'user_id', 'rating'));
            $numberOfRating = $reviews->count();
            $totalRating = $reviews->sum('rating');
            $numberOfReviews = $reviews->unique('user_id')->count();
            $averageRating = $numberOfRating > 0 ? $totalRating / $numberOfRating : 0;
            $numberOfFavoritePr = FavoriteProduct::all('id')->count();

            return response()->json([
                'latestUsers' => collect($users)->take(HomeDefine::LimitData),
                'totalUsers' => count($users),
                'latestProducts' => collect($products)->take(HomeDefine::LimitData),
                'totalProducts' => count($products),
                'latestOrders' => $lastestOrders,
                'totalOrders' => count($orders),
                'revenue' => $revenueTotal,
                'bestSeller' => collect($products)->sortByDesc('quantity_sold')->take(HomeDefine::LimitData),
                'revenueWithYear' => $revenueWithYear,
                'sevenDayData' => $sevenDayData,
                'revenueSevenDay' => $sevenDayRevenue,
                'percentageForteenAgo' => round($percentageForteenAgo, 2),
                'revenueThisYear' => $revenueThisYear,
                'numberOfRating' => $numberOfRating,
                'averageRating' => round($averageRating, 2),
                'numberOfReviews' => $numberOfReviews,
                'numberOfFavoritePr' => $numberOfFavoritePr,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
