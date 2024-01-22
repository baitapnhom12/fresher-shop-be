<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $table = 'images';

    protected $fillable = [
        'user_id',
        'product_id',
        'category_id',
        'brand_id',
        'banner_id',
        'review_id',
        'path',
        'main',
        'style',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function banner()
    {
        return $this->belongsTo(Banner::class, 'banner_id');
    }

    public function review()
    {
        return $this->belongsTo(Review::class, 'review_id');
    }
}
