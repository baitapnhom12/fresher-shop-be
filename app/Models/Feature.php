<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;

    protected $table = 'features';

    protected $fillable = ['feature'];

    public function productFeatures()
    {
        return $this->hasMany(ProductFeature::class, 'feature_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_features', 'feature_id', 'product_id');
    }
}
