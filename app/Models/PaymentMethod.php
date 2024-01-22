<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'payment_methods';

    protected $fillable = ['user_id', 'type', 'provider', 'account_number'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
