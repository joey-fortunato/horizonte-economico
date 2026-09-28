<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\User;

class AuthorController extends Controller
{
    public function show(string $slug)
    {
        $author = User::where('slug', $slug)->firstOrFail();

        $articles = $author->articles()->published()
            ->with(['category', 'cover'])
            ->latest('published_at')
            ->paginate(8);

        return view('site.autor', [
            'author' => $author,
            'articles' => $articles,
            'total' => $author->articles()->published()->count(),
        ]);
    }
}
