<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $results = Article::published()->with(['category', 'author', 'cover']);

        if ($q !== '') {
            $like = '%'.$q.'%';
            $pg = DB::getDriverName() === 'pgsql';
            $results->where(function ($query) use ($like, $pg) {
                if ($pg) {
                    // Insensível a acentos e maiúsculas via unaccent
                    $query->whereRaw('unaccent(title) ILIKE unaccent(?)', [$like])
                        ->orWhereRaw('unaccent(coalesce(excerpt,\'\')) ILIKE unaccent(?)', [$like])
                        ->orWhereRaw('unaccent(coalesce(body,\'\')) ILIKE unaccent(?)', [$like]);
                } else {
                    $query->where('title', 'like', $like)
                        ->orWhere('excerpt', 'like', $like)
                        ->orWhere('body', 'like', $like);
                }
            });
        }

        $results = $results->latest('published_at')->paginate(6)->withQueryString();

        return view('site.pesquisa', [
            'q' => $q,
            'results' => $results,
        ]);
    }
}
