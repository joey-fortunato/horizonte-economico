<?php

namespace App\Services;

use App\Enums\ArticleStatus;
use App\Enums\NewsEditorialStatus;
use App\Models\Article;
use App\Models\CollectedNews;
use App\Models\User;
use Illuminate\Support\Str;

class ArticleService
{
    /**
     * Cria um rascunho de artigo a partir de uma notícia recolhida.
     * Preserva a origem (pivot + campo sources) e nunca publica.
     */
    public function createDraftFromNews(CollectedNews $news, User $author): Article
    {
        $article = Article::create([
            'author_id' => $author->id,
            'title' => $news->title,
            'slug' => Str::slug(Str::limit($news->title, 80, '')).'-'.Str::lower(Str::random(5)),
            'excerpt' => $news->description ? Str::limit($news->description, 480) : null,
            'body' => null,
            'status' => ArticleStatus::Draft->value,
            'published_at' => null,
            'reading_minutes' => 1,
            'sources' => [[
                'title' => $news->title,
                'org' => $news->source_name,
                'url' => $news->url,
            ]],
        ]);

        // Associação estruturada notícia ↔ artigo
        $article->collectedNews()->syncWithoutDetaching([$news->id]);

        // A notícia passa a "Em redacção"
        $news->update(['editorial_status' => NewsEditorialStatus::Drafting->value]);

        return $article;
    }
}
