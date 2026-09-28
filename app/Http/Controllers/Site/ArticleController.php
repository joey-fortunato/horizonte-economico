<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Article;

class ArticleController extends Controller
{
    public function show(string $slug)
    {
        $article = Article::published()
            ->where('slug', $slug)
            ->with(['category', 'author.avatar', 'cover', 'tags'])
            ->firstOrFail();

        $article->increment('views_count');

        $related = Article::published()
            ->where('id', '!=', $article->id)
            ->when($article->category_id, fn ($q) => $q->where('category_id', $article->category_id))
            ->with('category')
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('site.artigo', [
            'article' => $article,
            'related' => $related,
            'activeSection' => $article->category?->slug,
            'stickyHeader' => false,
        ]);
    }
}
