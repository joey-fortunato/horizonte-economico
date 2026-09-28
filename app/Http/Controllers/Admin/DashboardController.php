<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ArticleStatus;
use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $counts = Article::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // Publicações por dia (últimos 7 dias) — métrica real a partir de published_at
        $perDay = collect(range(6, 0))->map(function ($daysAgo) {
            $day = Carbon::today()->subDays($daysAgo);
            $count = Article::whereDate('published_at', $day)->count();

            return ['label' => ucfirst($day->translatedFormat('D')), 'count' => $count];
        })->values();

        $mostRead = Article::published()->with('category')
            ->orderByDesc('views_count')
            ->take(4)
            ->get(['id', 'title', 'slug', 'views_count', 'category_id']);

        $recent = Article::with(['author', 'category'])
            ->latest('updated_at')
            ->take(5)
            ->get(['id', 'title', 'slug', 'status', 'author_id', 'category_id', 'updated_at'])
            ->map(fn (Article $a) => [
                'title' => $a->title,
                'author' => $a->author->name,
                'status' => $a->status->value,
                'statusLabel' => $a->status->label(),
                'updated' => $a->updated_at->diffForHumans(),
            ]);

        return Inertia::render('dashboard', [
            'metrics' => [
                'published' => (int) ($counts[ArticleStatus::Published->value] ?? 0),
                'draft' => (int) ($counts[ArticleStatus::Draft->value] ?? 0),
                'scheduled' => (int) ($counts[ArticleStatus::Scheduled->value] ?? 0),
                'review' => (int) ($counts[ArticleStatus::Review->value] ?? 0),
                'views' => (int) Article::sum('views_count'),
            ],
            'perDay' => $perDay,
            'mostRead' => $mostRead->map(fn ($a) => [
                'title' => $a->title,
                'views' => $a->views_count,
                'category' => $a->category?->name,
                'color' => $a->category?->color ?? '#123b30',
            ]),
            'recent' => $recent,
        ]);
    }
}
