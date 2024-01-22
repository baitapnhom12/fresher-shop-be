<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    protected $table = 'sizes';

    protected $fillable = ['name'];

    public function quantities()
    {
        return $this->hasMany(Quantity::class, 'size_id', 'id');
    }

    public function getName($id)
    {
        return $this->find($id)->name;
    }
}
