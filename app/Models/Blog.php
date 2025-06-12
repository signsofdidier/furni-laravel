<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = ['title', 'slug', 'excerpt', 'content', 'published_at', 'user_id'];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // blog heeft een user
    public function user() {
        return $this->belongsTo(User::class);
    }

    // blog heeft meerdere categories
    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class);
    }


}
