<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Color extends Model


{
    use SoftDeletes;

    protected $fillable = ['product_id', 'name', 'hex'];

    protected $dates = ['deleted_at'];


    public function products(){
        return $this->belongsToMany(Product::class);
    }

}
