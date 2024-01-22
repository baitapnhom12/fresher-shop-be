<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = ['order_id', 'amount', 'provider', 'status', 'account_number'];

    public function orders()
    {
        return $this->hasMany(Order::class, 'payment_id', 'id');
    }
}
