<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ArticleStatus;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class StatsController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Article::class);

        // Leituras por dia (últimos 30 dias) a partir de article_views
        $viewsPerDay = collect(range(29, 0))->map(function ($daysAgo) {
            $day = Carbon::today()->subDays($daysAgo);

            return [
                'label' => $day->translatedFormat('j/n'),
                'count' => DB::table('article_views')->whereDate('viewed_at', $day)->count(),
            ];
        })->values();

        $topArticles = Article::published()->with(['category', 'author'])
            ->orderByDesc('views_count')->take(10)->get()
            ->map(fn (Article $a) => [
                'title' => $a->title,
                'views' => $a->views_count,
                'category' => $a->category?->name,
                'color' => $a->category?->color,
                'author' => $a->author->name,
            ]);

        $byCategory = Category::query()
            ->withCount(['articles as published_count' => fn ($q) => $q->published()])
            ->withSum(['articles as views_sum' => fn ($q) => $q->published()], 'views_count')
            ->orderByDesc('views_sum')
            ->get()
            ->map(fn (Category $c) => [
                'name' => $c->name,
                'color' => $c->color,
                'articles' => $c->published_count,
                'views' => (int) $c->views_sum,
            ]);

        $byAuthor = User::query()
            ->withCount(['articles as published_count' => fn ($q) => $q->published()])
            ->withSum(['articles as views_sum' => fn ($q) => $q->published()], 'views_count')
            ->orderByDesc('views_sum')
            ->get()
            ->filter(fn (User $u) => $u->published_count > 0)
            ->map(fn (User $u) => [
                'name' => $u->name,
                'title' => $u->title,
                'articles' => $u->published_count,
                'views' => (int) $u->views_sum,
            ])
            ->values();

        return Inertia::render('admin/stats/index', [
            'metrics' => [
                'published' => Article::where('status', ArticleStatus::Published->value)->count(),
                'views' => (int) Article::sum('views_count'),
                'views30d' => (int) $viewsPerDay->sum('count'),
                'subscribers' => NewsletterSubscriber::count(),
            ],
            'viewsPerDay' => $viewsPerDay,
            'topArticles' => $topArticles,
            'byCategory' => $byCategory,
            'byAuthor' => $byAuthor,
        ]);
    }
}
