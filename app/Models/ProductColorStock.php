<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductColorStock extends Model
{
    protected $fillable = ['product_id', 'color_id', 'stock'];

    // elke stock hoort bij één product
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // elke stock hoort bij één kleur
    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }
}

