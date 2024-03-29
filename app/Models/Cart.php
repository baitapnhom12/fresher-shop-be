<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';

    public $timestamps = false;

    protected $fillable = ['user_id', 'product_id', 'size_id', 'quantity'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'cart_id', 'id');
    }
}
