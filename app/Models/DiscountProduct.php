<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountProduct extends Model
{
    use HasFactory;

    protected $table = 'discount_products';

    protected $fillable = ['product_id', 'discount_id', 'promotion_term', 'usage_count'];

    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_id');
    }
}
