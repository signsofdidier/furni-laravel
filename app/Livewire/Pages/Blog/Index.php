<?php

namespace App\Livewire\Pages\Blog;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Blog;

class Index extends Component
{
    use WithPagination;

    public function render()
    {
        $blogs = Blog::whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->paginate(6);

        return view('livewire.pages.blog.index', [
            'blogs' => $blogs,
        ]);
    }
}
