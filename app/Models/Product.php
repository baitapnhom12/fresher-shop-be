<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'slug',
        'brand_id',
        'concentration_id',
        'description',
        'categories',
        'quantity_sold',
        'status',
    ];

    public function carts()
    {
        return $this->hasMany(Cart::class, 'product_id', 'id');
    }

    public function quantities()
    {
        return $this->hasMany(Quantity::class, 'product_id', 'id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function discountProducts()
    {
        return $this->hasMany(DiscountProduct::class, 'product_id', 'id');
    }

    public function favoriteProducts()
    {
        return $this->hasMany(FavoriteProduct::class, 'product_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'product_id', 'id');
    }

    public function concentration()
    {
        return $this->belongsTo(Concentration::class, 'concentration_id');
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'product_id', 'id');
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class, 'product_id', 'id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id', 'id');
    }

    public function productFeatures()
    {
        return $this->hasMany(ProductFeature::class, 'product_id');
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'product_features', 'product_id', 'feature_id');
    }

    public function scopeSearchSize($query, $request)
    {
        $sizeNames = $request->size;

        if (!empty($sizeNames)) {
            $query->where(function ($query) use ($sizeNames) {
                foreach ($sizeNames as $index => $sizeName) {
                    if ($index === 0) {
                        $query->whereHas('quantities', function ($query) use ($sizeName) {
                            $query->whereHas('size', function ($query) use ($sizeName) {
                                $query->where('name', 'LIKE', "%$sizeName%");
                            });
                        });
                    } else {
                        $query->orWhereHas('quantities', function ($query) use ($sizeName) {
                            $query->whereHas('size', function ($query) use ($sizeName) {
                                $query->where('name', 'LIKE', "%$sizeName%");
                            });
                        });
                    }
                }
            });
        }

        return $query;
    }

    public function scopeSearchBrand($query, $request)
    {
        $brandNames = $request->brand;

        if (!empty($brandNames)) {
            $query->where(function ($query) use ($brandNames) {
                foreach ($brandNames as $index => $brandName) {
                    if ($index === 0) {
                        $query->whereHas('brand', function ($query) use ($brandName) {
                            $query->where('name', 'LIKE', "%$brandName%");
                        });
                    } else {
                        $query->orWhereHas('brand', function ($query) use ($brandName) {
                            $query->where('name', 'LIKE', "%$brandName%");
                        });
                    }
                }
            });
        }

        return $query;
    }

    public function scopeSearchCategory($query, $request)
    {
        $categoryNames = $request->category;

        if (!empty($categoryNames)) {
            $query->where(function ($query) use ($categoryNames) {
                foreach ($categoryNames as $index => $categoryName) {
                    if ($index === 0) {
                        $categoryIds = Category::where('name', 'like', "%$categoryName%")->pluck('id')->toArray();
                        if (!empty($categoryIds)) {
                            foreach ($categoryIds as $categoryId) {
                                $query->orWhereJsonContains('categories', $categoryId);
                            }
                        } else {
                            $query->where('categories', $categoryName);
                        }
                    } else {
                        $categoryIds = Category::where('name', 'like', "%$categoryName%")->pluck('id')->toArray();
                        if (!empty($categoryIds)) {
                            foreach ($categoryIds as $categoryId) {
                                $query->orWhereJsonContains('categories', $categoryId);
                            }
                        } else {
                            $query->orWhere('categories', $categoryName);
                        }
                    }
                }
            });
        }

        return $query;
    }

    public function scopeSearchConcentration($query, $request)
    {
        $concentrations = $request->concentration;

        if (!empty($concentrations)) {
            $query->where(function ($query) use ($concentrations) {
                foreach ($concentrations as $index => $concentration) {
                    if ($index === 0) {
                        $query->whereHas('concentration', function ($query) use ($concentration) {
                            $query->where('name', 'LIKE', "%$concentration%");
                        });
                    } else {
                        $query->orWhereHas('concentration', function ($query) use ($concentration) {
                            $query->where('name', 'LIKE', "%$concentration%");
                        });
                    }
                }
            });
        }

        return $query;
    }

    public function scopeSearchPriceFrom($query, $request)
    {
        $from = $request->from;
        if (isset($from)) {
            $query->whereHas('quantities', function ($query) use ($from) {
                $query->where('price', '>=', "$from");
            });
        }

        return $query;
    }

    public function scopeSearchPriceTo($query, $request)
    {
        $to = $request->to;
        if (isset($to)) {
            $query->whereHas('quantities', function ($query) use ($to) {
                $query->where('price', '<=', "$to");
            });
        }

        return $query;
    }

    public function scopeSearchKeySearch($query, $request)
    {
        $keySearch = $request->keySearch;
        if (isset($keySearch)) {
            $query->where('name', 'like', "%$keySearch%")
                ->orWhereHas('concentration', function ($query) use ($keySearch) {
                    $query->where('name', 'like', "%$keySearch%");
                })
                ->orWhereHas('brand', function ($query) use ($keySearch) {
                    $query->where('name', 'like', "%$keySearch%");
                });
        }

        return $query;
    }

    public function scopeSearchIsSale($query, $request)
    {
        $isSale = ($request->isSale === 'true') ? true : false;
        $now = Carbon::now();
        if ($isSale) {
            $query->whereHas('discountProducts', function ($query) use ($now) {
                $query->where('promotion_term', '>', "$now")
                    ->where('usage_count', '>', 0)
                    ->whereHas('discount', function ($query) {
                        $query->where('active', 1);
                    });
            });
        }

        return $query;
    }

    public function scopeSearchSlug($query, $request)
    {
        $slug = $request->slug;
        if (isset($slug)) {
            $query->where('slug', $slug);
        }

        return $query;
    }
}
