<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Color extends Model


{
    use SoftDeletes;

    protected $fillable = ['product_id', 'name', 'hex'];

    // Zorgt ervoor dat deleted_at als datum gezien wordt (soft deletes)
    protected $dates = ['deleted_at'];

    // Een color hoort bij meerdere producten
    public function products(){
        return $this->belongsToMany(Product::class);
    }

    // Een kleur kan gelinkt zijn aan meerdere producten via product_color_stock.
    public function productColorStocks()
    {
        return $this->hasMany(ProductColorStock::class);
    }


}
