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
        // haal alle blogs op en sorteer volgens de datum
        $blogs = Blog::with(['categories', 'user'])->orderByDesc('created_at')->paginate(6);

        return view('livewire.pages.blog.index', [
            'blogs' => $blogs,
        ]);
    }
}
