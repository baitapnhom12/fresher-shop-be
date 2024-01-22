<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedBack extends Model
{
    use HasFactory;

    protected $table = 'feedbacks';

    protected $fillable = ['name', 'email', 'message', 'reply'];
}
