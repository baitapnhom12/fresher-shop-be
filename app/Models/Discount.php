<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $table = 'discounts';

    protected $fillable = ['name', 'percent', 'active'];

    public function discountProducts()
    {
        return $this->hasMany(DiscountProduct::class, 'discount_id', 'id');
    }
}
