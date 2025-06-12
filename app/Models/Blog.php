<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'slug', 'excerpt', 'content', 'user_id', 'image', 'blockquote', 'blockquote_author', ];

    // blog heeft een user
    public function user() {
        return $this->belongsTo(User::class);
    }

    // blog heeft meerdere categories
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'blog_category');
    }


}
