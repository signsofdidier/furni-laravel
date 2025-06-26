<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_amount',
        'total_amount',
        'color_id'
    ];

    // Een OrderItem hoort bij één order
    public function order(){
        return $this->belongsTo(Order::class);
    }

    // Een OrderItem hoort bij één product
    public function product(){
        return $this->belongsTo(Product::class);
    }

    // Een orderitem hoort bij één kleur (optioneel).
    public function color(){
        return $this->belongsTo(Color::class);
    }
}
