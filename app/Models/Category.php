<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'slug', 'image', 'is_active'];

    // Zorgt ervoor dat deleted_at als datum gezien wordt (soft deletes)
    protected $dates = ['deleted_at'];

    // Een category heeft meerdere producten
    public function products(){
        return $this->hasMany(Product::class);
    }

    // Meerdere blogs kunnen aan een categorie hangen (pivot tabel blog_category)
    public function blogs()
    {
        return $this->belongsToMany(Blog::class, 'blog_category');
    }
}
