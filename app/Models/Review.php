<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'title',
        'body',
    ];

    // een review hoort bij een user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // een review hoort bij een product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

