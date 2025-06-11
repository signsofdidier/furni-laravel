<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'images',
        'is_active',
        'is_featured',
        'in_stock',
        'on_sale',
        'category_id',
        'brand_id',
        'shipping_cost',
    ];

    protected $dates = ['deleted_at'];

    // hierdoor worden de images omgezet naar een array uit de JSON van images
    protected $casts = [
        'images' => 'array',
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    public function orderItems(){
        return $this->hasMany(OrderItem::class);
    }

    public function colors(){
        return $this->belongsToMany(Color::class);
    }

    /* PREVIEW EN RATING RELATIONS */

    // reviews heeft meerdere reviews
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Gemiddelde score via product relatie
    public function averageRating(): float
    {
        return round($this->reviews()->avg('rating') ?? 0);
    }

    // Totaal aantal reviews
    public function totalReviews(): int
    {
        return $this->reviews()->count();
    }




}
