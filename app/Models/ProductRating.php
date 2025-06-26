<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
    ];

    // een review rating hoort bij een product
    public function product(){
        return $this->belongsTo(Product::class);
    }

    // een review rating hoort bij een user
    public function user(){
        return $this->belongsTo(User::class);
    }
}
