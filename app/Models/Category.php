<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'categories';

    protected $fillable = ['name', 'parent_id', 'slug'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('children');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'category_id', 'id');
    }
}
