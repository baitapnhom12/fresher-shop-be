<?php

namespace App\Services;

use App\Enums\ImageDefine;
use App\Enums\ProductDefine;
use App\Enums\UserRole;
use App\Events\SendNewProduct;
use App\Http\Resources\Products\ProductResource;
use App\Http\Resources\Products\ProductsPaginationResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Concentration;
use App\Models\DiscountProduct;
use App\Models\Image;
use App\Models\Product;
use App\Models\Quantity;
use App\Models\Subscriber;
use App\Models\User;
use App\Traits\ImageTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductService
{
    use ImageTrait;

    public function list($request)
    {
        try {
            $perPage = (int) $request->perPage;
            $selects = [
                'id',
                'name',
                'slug',
                'brand_id',
                'concentration_id',
                'description',
                'categories',
                'created_at',
                'updated_at',
                'quantity_sold',
                'status',
            ];
            $orderBy = ProductDefine::sortBy[(int) $request->sortBy] ?? 'created_at';
            $query = Product::withCount('reviews as reviews_count')
                ->withAvg('reviews as average_rating', 'rating')
                ->withMin('quantities as min_price', 'price')
                ->withSum('quantities as total', 'quantity')
                ->with(
                    'brand:id,name',
                    'brand.images:id,brand_id,path,main',
                    'concentration:id,name',
                    'discountProducts',
                    'discountProducts.discount:id,name,percent,active',
                    'quantities:id,product_id,size_id,quantity,price',
                    'quantities.size:id,name',
                    'images:id,product_id,path,main',
                    'reviews:id,product_id,user_id,rating,comment,created_at',
                    'reviews.images:id,review_id,path',
                    'reviews.user:id,name,email',
                    'reviews.user.images:id,user_id,main,path'
                )
                ->searchCategory($request)
                ->searchSize($request)
                ->searchBrand($request)
                ->searchPriceFrom($request)
                ->searchPriceTo($request)
                ->searchKeySearch($request)
                ->searchIsSale($request)
                ->searchConcentration($request)
                ->searchSlug($request)
                ->newQuery();
            if ((int) $request->sortBy === ProductDefine::SortByPriceAsc) {
                $query->orderBy(ProductDefine::sortBy[5]);
            } else {
                $query->orderByDesc($orderBy);
            }

            if (!auth()->user() || auth()->user()->role == UserRole::User) {
                $query->where('status', ProductDefine::OpenSales)->has('quantities');
            }

            if ($perPage) {
                $products = $query->paginate($perPage, $selects)->toArray();

                $uniqueCategoryIds = array_unique(array_reduce($products['data'], function ($carry, $product) {
                    $categoryIds = json_decode($product['categories'], true);
                    if (is_array($categoryIds)) {
                        return array_merge($carry, $categoryIds);
                    }

                    return $carry;
                }, []));
                $categories = Category::whereIn('id', $uniqueCategoryIds)->get(['id', 'name']) ?? [];

                $products['data'] = collect($products['data'])->map(function ($product) use ($categories) {
                    $categoryIds = json_decode($product['categories'], true);
                    $productCategories = [];

                    if (!empty($categoryIds)) {
                        foreach ($categories as $category) {
                            if (in_array($category['id'], $categoryIds)) {
                                $productCategories[] = [
                                    'id' => $category['id'],
                                    'name' => $category['name'],
                                ];
                            }
                        }
                    }

                    return [
                        'id' => $product['id'],
                        'name' => $product['name'],
                        'slug' => $product['slug'],
                        'images' => $product['images'],
                        'brand' => $product['brand'],
                        'concentration' => $product['concentration'],
                        'categories' => $productCategories,
                        'discounts' => $product['discount_products'],
                        'quantities' => $product['quantities'],
                        'quantity' => (int) $product['total'],
                        'description' => $product['description'],
                        'createdAt' => $product['created_at'],
                        'updatedAt' => $product['updated_at'],
                        'quantitySold' => $product['quantity_sold'],
                        'reviews' => $product['reviews'],
                        'status' => $product['status'],
                        'averageRating' => $product['average_rating'],
                        'reviewsCount' => (int) $product['reviews_count'],
                    ];
                });

                return response()->json(new ProductsPaginationResource($products));
            } else {
                $products = $query->get($selects)->toArray();
                $uniqueCategoryIds = array_unique(array_reduce($products, function ($carry, $product) {
                    $categoryIds = json_decode($product['categories'], true);
                    if (is_array($categoryIds)) {
                        return array_merge($carry, $categoryIds);
                    }

                    return $carry;
                }, []));
                $categories = Category::whereIn('id', $uniqueCategoryIds)->get(['id', 'name']) ?? [];

                $products = collect($products)->map(function ($product) use ($categories) {
                    $categoryIds = json_decode($product['categories'], true);
                    $productCategories = [];

                    if (!empty($categoryIds)) {
                        foreach ($categories as $category) {
                            if (in_array($category['id'], $categoryIds)) {
                                $productCategories[] = [
                                    'id' => $category['id'],
                                    'name' => $category['name'],
                                ];
                            }
                        }
                    }

                    return new ProductResource([
                        'id' => $product['id'],
                        'name' => $product['name'],
                        'slug' => $product['slug'],
                        'images' => $product['images'],
                        'brand' => $product['brand'],
                        'concentration' => $product['concentration'],
                        'categories' => $productCategories,
                        'discounts' => $product['discount_products'],
                        'quantities' => $product['quantities'],
                        'quantity' => (int) $product['total'],
                        'description' => $product['description'],
                        'createdAt' => $product['created_at'],
                        'updatedAt' => $product['updated_at'],
                        'quantitySold' => $product['quantity_sold'],
                        'reviews' => $product['reviews'],
                        'status' => $product['status'],
                        'averageRating' => $product['average_rating'],
                        'reviewsCount' => (int) $product['reviews_count'],
                    ]);
                });

                return response()->json($products);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function storeProduct($request)
    {
        try {
            $brand = Brand::where('id', $request->brandId)->first('id');
            !$brand &&
                throw new \Exception('Brand not found', 404);

            if ($request->concentrationId) {
                $concentration = Concentration::where('id', $request->concentrationId)->first('id');
                !$concentration &&
                    throw new \Exception('Concentration not found', 404);
            }
            $categoryIds = $request->categoryIds ? array_unique(array_map('intval', $request->categoryIds)) : null;

            DB::beginTransaction();
            $product = Product::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'brand_id' => $request->brandId,
                'concentration_id' => $request->concentrationId,
                'categories' => !empty($categoryIds) ? json_encode($categoryIds) : null,
                'description' => $request->description,
                'status' => $request->status,
            ]);

            $featureIds = $request->featureIds;
            $featurePrData = [];
            if (!empty($featureIds)) {
                foreach ($featureIds as $feature) {
                    $featurePrData[] = ['feature_id' => $feature];
                }
                $product->productFeatures()->createMany($featurePrData);
            }

            $images = $request->file('images');

            if (!empty($images)) {
                $path = 'products/';

                foreach ($images as $img) {
                    $fileData = $this->uploads($img, $path);
                    $imageData[] = [
                        'path' => $fileData['filePath'],
                        'main' => ImageDefine::ImageNotMain,
                    ];
                }
                $imageData[0]['main'] = ImageDefine::ImageMain;
                $product->images()->createMany($imageData);
            }

            $quantities = [];
            $rqSizeId = array_filter($request->sizeId, function ($value) {
                return $value !== null;
            });
            $rqQuantity = array_filter($request->quantity, function ($value) {
                return $value !== null;
            });
            $rqPrice = array_filter($request->price, function ($value) {
                return $value !== null;
            });
            $discounts = [];
            $rqDiscountId = array_filter($request->discountId, function ($value) {
                return $value !== null;
            });
            $rqUsageCount = array_filter($request->usageCount, function ($value) {
                return $value !== null;
            });
            $rqPromotionTerm = array_filter($request->promotionTerm, function ($value) {
                return $value !== null;
            });

            if (!empty($rqSizeId) && !empty($rqQuantity) && !empty($rqPrice)) {
                $quantities = array_map(function ($sizeId, $quantity, $price) {
                    return [
                        'sizeId' => (int) $sizeId,
                        'quantity' => (int) $quantity,
                        'price' => (float) $price,
                    ];
                }, $rqSizeId, $rqQuantity, $rqPrice);
            }

            if (!empty($rqDiscountId) && !empty($rqUsageCount) && !empty($rqPromotionTerm)) {
                $discounts = array_map(function ($discountId, $usageCount, $promotionTerm) {
                    return [
                        'discountId' => (int) $discountId,
                        'usageCount' => (float) $usageCount,
                        'promotionTerm' => $promotionTerm,
                    ];
                }, $rqDiscountId, $rqUsageCount, $rqPromotionTerm);
            }

            if (!empty($discounts)) {
                foreach ($discounts as $discount) {
                    $discountData[] = [
                        'discount_id' => $discount['discountId'],
                        'promotion_term' => Carbon::parse($discount['promotionTerm']),
                        'usage_count' => $discount['usageCount'],
                    ];
                }
                $product->discountProducts()->createMany($discountData);
            }

            if (!empty($quantities)) {
                $quantitiesData = [];
                foreach ($quantities as $quantity) {
                    $quantitiesData[] = [
                        'size_id' => $quantity['sizeId'],
                        'quantity' => $quantity['quantity'],
                        'price' => $quantity['price'],
                    ];
                }
                $product->quantities()->createMany($quantitiesData);
            }

            if ($product->status == ProductDefine::OpenSales) {
                $subscribers = Subscriber::all('email')->pluck('email')->toArray();
                $users = User::where('role', UserRole::User)->pluck('email')->toArray();
                $combinedEmails = array_unique(array_merge($subscribers, $users));
                event(new SendNewProduct([
                    'users' => $combinedEmails,
                    'productName' => $product->name,
                ]));
            }
            DB::commit();

            return true;
        } catch (\Throwable $e) {
            DB::rollBack();

            return false;
        }
    }

    public function detail($id)
    {
        try {
            $query = Product::withCount('reviews as reviews_count')
                ->withAvg('reviews as average_rating', 'rating')
                ->withSum('quantities as total', 'quantity')
                ->with(
                    'brand:id,name',
                    'brand.images:id,brand_id,main,path',
                    'concentration:id,name',
                    'discountProducts',
                    'discountProducts.discount:id,name,percent,active',
                    'quantities:id,product_id,size_id,quantity,price',
                    'quantities.size:id,name',
                    'images:id,product_id,path,main',
                    'reviews:id,product_id,user_id,rating,comment,created_at',
                    'reviews.images:id,review_id,path',
                    'reviews.user:id,name,email',
                    'reviews.user.images:id,user_id,main,path',
                    'features:id,feature'
                )->newQuery();

            if (!auth()->user() || auth()->user()->role == UserRole::User) {
                $query->where('status', ProductDefine::OpenSales);
            }

            $product = $query->find($id)->toArray();
            if (!$product) {
                return response()->json(['error' => 'Not found'], 404);
            }

            $categoryIds = array_unique(json_decode($product['categories'], true));
            $categories = [];
            if (!empty($categoryIds)) {
                $categories = Category::whereIn('id', $categoryIds)->get(['id', 'name']);
                $categories = collect($categories)->map(function ($category) {
                    return [
                        'id' => $category['id'],
                        'name' => $category['name'],
                    ];
                });
            }

            return response()->json(new ProductResource([
                'id' => $product['id'],
                'name' => $product['name'],
                'slug' => $product['slug'],
                'images' => $product['images'],
                'brand' => $product['brand'],
                'concentration' => $product['concentration'],
                'categories' => $categories,
                'discounts' => $product['discount_products'],
                'quantities' => $product['quantities'],
                'quantity' => (int) $product['total'],
                'description' => $product['description'],
                'createdAt' => $product['created_at'],
                'updatedAt' => $product['updated_at'],
                'quantitySold' => $product['quantity_sold'],
                'reviews' => $product['reviews'],
                'status' => $product['status'],
                'features' => $product['features'],
                'averageRating' => $product['average_rating'],
                'reviewsCount' => (int) $product['reviews_count'],
            ]));
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete(string $id)
    {
        try {
            DB::beginTransaction();
            $product = Product::find($id);

            if (!$product) {
                throw new \Exception('Product not found', 404);
            }

            $imagePathsDelete = $product->images->pluck('path')->toArray();
            if (!empty($imagePathsDelete)) {
                $this->deleteFile($imagePathsDelete);
            }

            $product->discountProducts()->delete();
            $product->images()->delete();
            $product->quantities()->delete();
            $product->carts()->delete();
            $product->favoriteProducts()->delete();
            $product->orderProducts()->delete();
            $product->reviews()->delete();
            $product->productFeature()->delete();
            $result = $product->delete();

            DB::commit();
            if ($result) {
                return true;
            }

            return false;
        } catch (\Throwable $e) {
            DB::rollBack();

            return false;
        }
    }

    public function updateProduct($request, $id)
    {
        try {
            DB::beginTransaction();
            $product = Product::find($id);
            if (!$product) {
                throw new \Exception('product not found', 404);
            }
            $categoryIds = !empty($request->categoryIds) ? array_unique(array_map('intval', $request->categoryIds)) : null;

            $product->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'brand_id' => $request->brandId,
                'concentration_id' => $request->concentrationId,
                'categories' => !empty($categoryIds) ? json_encode($categoryIds) : null,
                'description' => $request->description,
                'status' => $request->status,
            ]);

            $featureIds = !empty($request->featureIds) ? array_unique(array_map('intval', $request->featureIds)) : null;
            if (!empty($featureIds)) {
                $product->features()->sync($featureIds);
            }

            $images = $request->file('images');

            if (!empty($images)) {
                $path = 'products/';

                foreach ($images as $image) {
                    $fileData = $this->uploads($image, $path);
                    $imageData[] = [
                        'path' => $fileData['filePath'],
                        'main' => ImageDefine::ImageNotMain,
                    ];
                }
                $product->images()->createMany($imageData);
            }

            $imageUpdate = $request->imageUpdate;
            $imageDelete = json_decode($request->imageDelete);
            if ($imageUpdate) {
                Image::where('product_id', $id)->update(['main' => ImageDefine::ImageNotMain]);
                Image::where('id', $imageUpdate)->update(['main' => ImageDefine::ImageMain]);
            }

            if (!empty($imageDelete)) {
                $imageDel = Image::where('product_id', $id)->whereIn('id', $imageDelete)->select('id', 'path')->get();
                $imageDelPath = $imageDel->pluck('path')->toArray();
                $this->deleteFile($imageDelPath);
                Image::whereIn('id', $imageDelete)->delete();
            }

            $quantities = [];
            $rqSizeId = $request->sizeId ? array_filter($request->sizeId, function ($value) {
                return $value !== null;
            }) : null;
            $rqQuantity = $request->quantity ? array_filter($request->quantity, function ($value) {
                return $value !== null;
            }) : null;
            $rqPrice = $request->price ? array_filter($request->price, function ($value) {
                return $value !== null;
            }) : null;

            $discounts = [];
            $rqDiscountId = $request->discountId ? array_filter($request->discountId, function ($value) {
                return $value !== null;
            }) : null;
            $rqUsageCount = $request->usageCount ? array_filter($request->usageCount, function ($value) {
                return $value !== null;
            }) : $request->usageCount;
            $rqPromotionTerm = $request->promotionTerm ? array_filter($request->promotionTerm, function ($value) {
                return $value !== null;
            }) : null;

            $sizePrDelete = json_decode($request->sizeDelete, true);
            $discountPrDelete = json_decode($request->discountDelete, true);
            if (!empty($sizePrDelete)) {
                Quantity::where('product_id', $id)->whereIn('size_id', $sizePrDelete)->delete();
            }
            if (!empty($discountPrDelete)) {
                DiscountProduct::where('product_id', $id)->whereIn('discount_id', $discountPrDelete)->delete();
            }

            if (!empty($rqSizeId) && !empty($rqQuantity) && !empty($rqPrice)) {
                $quantities = array_map(function ($sizeId, $quantity, $price) {
                    return [
                        'sizeId' => (int) $sizeId,
                        'quantity' => (int) $quantity,
                        'price' => (float) $price,
                    ];
                }, $rqSizeId, $rqQuantity, $rqPrice);
            }

            if (!empty($rqDiscountId) && !empty($rqUsageCount) && !empty($rqPromotionTerm)) {
                $discounts = array_map(function ($discountId, $usageCount, $promotionTerm) {
                    return [
                        'discountId' => (int) $discountId,
                        'usageCount' => (float) $usageCount,
                        'promotionTerm' => $promotionTerm,
                    ];
                }, $rqDiscountId, $rqUsageCount, $rqPromotionTerm);
            }
            if (!empty($discounts)) {
                foreach ($discounts as $discount) {
                    $discountData = [];
                    $discountUpdate = DiscountProduct::where([
                        'product_id' => (int) $id,
                        'discount_id' => $discount['discountId']]
                    )->first();
                    if ($discountUpdate) {
                        $discountUpdate->update([
                            'discount_id' => $discount['discountId'],
                            'promotion_term' => Carbon::parse($discount['promotionTerm']),
                            'usage_count' => $discount['usageCount'],
                        ]);
                    } else {
                        $discountData[] = [
                            'discount_id' => $discount['discountId'],
                            'promotion_term' => Carbon::parse($discount['promotionTerm']),
                            'usage_count' => $discount['usageCount'],
                        ];
                    }
                }
                $product->discountProducts()->createMany($discountData);
            }

            if (!empty($quantities)) {
                $quantitiesData = [];
                foreach ($quantities as $quantity) {
                    $quantitiesUpdate = Quantity::where(['product_id' => $id, 'size_id' => $quantity['sizeId']])->first();
                    if ($quantitiesUpdate) {
                        $quantitiesUpdate->update([
                            'size_id' => $quantity['sizeId'],
                            'quantity' => $quantity['quantity'],
                            'price' => $quantity['price'],
                        ]);
                    } else {
                        $quantitiesData[] = [
                            'size_id' => $quantity['sizeId'],
                            'quantity' => $quantity['quantity'],
                            'price' => $quantity['price'],
                        ];
                    }
                }
                $product->quantities()->createMany($quantitiesData);
            }

            DB::commit();

            return true;
        } catch (\Throwable $e) {
            DB::rollBack();

            return false;
        }
    }

    public function relateProduct($request, $id)
    {
        $product = Product::with('brand:id,name', 'concentration:id,name')->find($id);

        $query = Product::withCount('reviews as reviews_count')
            ->withAvg('reviews as average_rating', 'rating')
            ->withSum('quantities as total', 'quantity')
            ->with(
                'brand:id,name',
                'brand.images:id,brand_id,path,main',
                'concentration:id,name',
                'discountProducts',
                'discountProducts.discount:id,name,percent,active',
                'quantities:id,product_id,size_id,quantity,price',
                'quantities.size:id,name',
                'images:id,product_id,path,main',
                'reviews:id,product_id,user_id,rating,comment,created_at',
                'reviews.images:id,review_id,path',
                'reviews.user:id,name,email',
                'reviews.user.images:id,user_id,main,path'
            )->newQuery();
        $limit = $request->limit ? (int) $request->limit : null;
        $concentration = $product->concentration->name;
        $brand = $product->brand->name;
        $categories = $product->categories ? array_unique(json_decode($product->categories, true)) : [];

        $query->where(function ($query) use ($id) {
            $query->where('status', ProductDefine::OpenSales)
                ->whereNotIn('id', [$id]);
        });

        if ($limit) {
            $query->limit($limit);
        }

        if (isset($concentration)) {
            $query->orWhereHas('concentration', function ($query) use ($concentration) {
                $query->where('name', "$concentration");
            });
        }

        if (isset($brand)) {
            $query->orWhereHas('brand', function ($query) use ($brand) {
                $query->where('name', "$brand");
            });
        }

        if (!empty($categories)) {
            foreach ($categories as $category) {
                $query->orWhereJsonContains('categories', (int) $category);
            }
        }

        $productRelated = $query->orderByDesc('created_at')->get()->toArray();

        $productRelated = collect($productRelated)->map(function ($product) {
            $categoryIds = json_decode($product['categories'], true);
            $categories = [];
            if (!empty($categoryIds)) {
                $categories = Category::whereIn('id', $categoryIds)->get(['id', 'name']);
                $categories = collect($categories)->map(function ($category) {
                    return [
                        'id' => $category['id'],
                        'name' => $category['name'],
                    ];
                });
            }

            return new ProductResource([
                'id' => $product['id'],
                'name' => $product['name'],
                'slug' => $product['slug'],
                'images' => $product['images'],
                'brand' => $product['brand'],
                'status' => $product['status'],
                'concentration' => $product['concentration'],
                'categories' => $categories,
                'discounts' => $product['discount_products'],
                'quantities' => $product['quantities'],
                'quantity' => (int) $product['total'],
                'description' => $product['description'],
                'createdAt' => $product['created_at'],
                'updatedAt' => $product['updated_at'],
                'quantitySold' => $product['quantity_sold'],
                'reviews' => $product['reviews'],
                'averageRating' => $product['average_rating'],
                'reviewsCount' => (int) $product['reviews_count'],
            ]);
        })->values()->all();

        return response()->json($productRelated, 200);
    }
}
