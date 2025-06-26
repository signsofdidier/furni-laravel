<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'user_id',
        'sub_total',
        'grand_total',
        'tax_amount',
        'payment_method',
        'payment_status',
        'status',
        'currency',
        'shipping_amount',
        'shipping_method',
        'notes',
    ];

    // Zorgt ervoor dat deleted_at als datum gezien wordt (soft deletes)
    protected $dates = ['deleted_at'];

    // Elke order hoort bij één adres
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    // Elke order hoort bij één user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Elke order heeft meerdere items
    public function items(){
        return $this->hasMany(OrderItem::class);
    }

}
