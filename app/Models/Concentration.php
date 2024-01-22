<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concentration extends Model
{
    use HasFactory;

    protected $table = 'concentrations';

    protected $fillable = ['name', 'slug'];

    public function products()
    {
        return $this->hasMany(Product::class, 'concentration_id', 'id');
    }
}
