<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $results = Article::published()->with(['category', 'author', 'cover']);

        if ($q !== '') {
            $like = '%'.$q.'%';
            $results->where(function ($query) use ($like) {
                $query->where('title', 'ilike', $like)
                    ->orWhere('excerpt', 'ilike', $like)
                    ->orWhere('body', 'ilike', $like);
            });
        }

        $results = $results->latest('published_at')->paginate(6)->withQueryString();

        return view('site.pesquisa', [
            'q' => $q,
            'results' => $results,
        ]);
    }
}
