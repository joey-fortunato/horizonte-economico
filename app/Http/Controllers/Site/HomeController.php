<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Article;

class HomeController extends Controller
{
    public function index()
    {
        $pool = Article::published()
            ->with(['category', 'author', 'cover'])
            ->latest('published_at')
            ->take(14)
            ->get();

        $mostRead = Article::published()
            ->with('category')
            ->orderByDesc('views_count')
            ->take(5)
            ->get();

        return view('site.home', [
            'lead' => $pool->get(0),
            'secondary' => $pool->slice(1, 3)->values(),
            'latestLead' => $pool->slice(4, 2)->values(),
            'latestGrid' => $pool->slice(6, 3)->values(),
            'opinionLead' => $pool->get(9),
            'opinionList' => $pool->slice(10, 3)->values(),
            'mostRead' => $mostRead,
        ]);
    }
}
