<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'slug', 'image', 'is_active'];

    // Zorgt ervoor dat deleted_at als datum gezien wordt (soft deletes)
    protected $dates = ['deleted_at'];


    // Een brand heeft meerdere producten
    public function products(){
        return $this->hasMany(Product::class);
    }
}
