<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function show(string $slug)
    {
        $category = Category::active()->where('slug', $slug)->firstOrFail();

        $featured = $category->articles()->published()
            ->with(['author', 'cover'])
            ->latest('published_at')
            ->first();

        $articles = $category->articles()->published()
            ->with(['author', 'cover'])
            ->when($featured, fn ($q) => $q->where('id', '!=', $featured->id))
            ->latest('published_at')
            ->paginate(9);

        return view('site.categoria', [
            'category' => $category,
            'slug' => $category->slug,
            'activeSection' => $category->slug,
            'featured' => $featured,
            'articles' => $articles,
            'total' => $category->articles()->published()->count(),
        ]);
    }
}
