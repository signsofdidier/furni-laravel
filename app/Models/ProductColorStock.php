<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductColorStock extends Model
{
    protected $fillable = ['product_id', 'color_id', 'stock'];

    // products heeft meerdere stock items
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // colors heeft meerdere stock items
    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }
}

