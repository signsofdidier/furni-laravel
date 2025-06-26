<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'user_id',
        'first_name',
        'last_name',
        'phone',
        'email',
        'street_address',
        'city',
        'state',
        'zip_code'
    ];

    // Een adres kan aan meerdere orders hangen
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Een adres hoort bij een user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Zet voor en achternaam samen als 1 attribute
    public function getFullNameAttribute(){
        return "{$this->first_name} {$this->last_name}";
    }


}
