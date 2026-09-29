<?php

namespace App\Http\Controllers\Admin;

use App\Enums\NewsEditorialStatus;
use App\Http\Controllers\Controller;
use App\Jobs\CollectNewsSourceJob;
use App\Models\CollectedNews;
use App\Models\NewsEditorialNote;
use App\Models\NewsFetchRun;
use App\Models\NewsSource;
use App\Services\ArticleService;
use App\Services\News\NewsCollector;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', CollectedNews::class);

        $news = CollectedNews::with('source')
            ->when($request->query('status'), fn ($q, $s) => $q->where('editorial_status', $s))
            ->when($request->boolean('unanalyzed'), fn ($q) => $q->where('editorial_status', NewsEditorialStatus::Pending->value))
            ->when($request->query('source'), fn ($q, $id) => $q->where('news_source_id', $id))
            ->when($request->query('q'), fn ($q, $term) => $q->where('title', 'like', "%{$term}%"))
            ->when($request->query('from'), fn ($q, $d) => $q->whereDate('published_at', '>=', $d))
            ->when($request->query('to'), fn ($q, $d) => $q->whereDate('published_at', '<=', $d))
            ->orderByRaw('published_at is null') // não-nulas primeiro (portável pgsql/sqlite)
            ->orderByDesc('published_at')
            ->orderByDesc('fetched_at')
            ->paginate(20)
            ->withQueryString()
            ->through(fn (CollectedNews $n) => [
                'id' => $n->id,
                'title' => $n->title,
                'url' => $n->url,
                'source' => $n->source?->name,
                'source_name' => $n->source_name,
                'published_at' => $n->published_at?->translatedFormat('j M Y'),
                'fetched_at' => $n->fetched_at->diffForHumans(),
                'status' => $n->editorial_status->value,
                'statusLabel' => $n->editorial_status->label(),
            ]);

        $counts = CollectedNews::selectRaw('editorial_status, count(*) as total')
            ->groupBy('editorial_status')->pluck('total', 'editorial_status');

        return Inertia::render('admin/news/index', [
            'news' => $news,
            'filters' => $request->only(['status', 'source', 'q', 'from', 'to', 'unanalyzed']),
            'counts' => $counts,
            'statuses' => collect(NewsEditorialStatus::cases())->map(fn ($s) => ['value' => $s->value, 'label' => $s->label()]),
            'sources' => NewsSource::orderBy('name')->get(['id', 'name']),
            'canDecide' => $request->user()->can('decide', new CollectedNews()),
            'canCollect' => $request->user()->can('collect', NewsSource::class),
        ]);
    }

    public function show(Request $request, CollectedNews $news)
    {
        $this->authorize('view', $news);

        $news->load(['source', 'notes.user', 'articles:id,title,slug,status']);

        return Inertia::render('admin/news/show', [
            'item' => [
                'id' => $news->id,
                'title' => $news->title,
                'url' => $news->url,
                'source' => $news->source?->name,
                'source_name' => $news->source_name,
                'description' => $news->description,
                'published_at' => $news->published_at?->translatedFormat('j \d\e F \d\e Y, H:i'),
                'fetched_at' => $news->fetched_at->translatedFormat('j \d\e F \d\e Y, H:i'),
                'status' => $news->editorial_status->value,
                'statusLabel' => $news->editorial_status->label(),
                'notes' => $news->notes->map(fn (NewsEditorialNote $note) => [
                    'id' => $note->id,
                    'note' => $note->note,
                    'author' => $note->user?->name ?? '—',
                    'at' => $note->created_at->diffForHumans(),
                ]),
                'articles' => $news->articles->map(fn ($a) => [
                    'title' => $a->title,
                    'slug' => $a->slug,
                    'status' => $a->status->value,
                ]),
            ],
            'canDecide' => $request->user()->can('decide', $news),
            'canConvert' => $request->user()->can('convert', $news),
        ]);
    }

    public function decide(Request $request, CollectedNews $news)
    {
        $this->authorize('decide', $news);
        $data = $request->validate([
            'decision' => ['required', 'in:select,reject,pending'],
        ]);

        $news->update(['editorial_status' => match ($data['decision']) {
            'select' => NewsEditorialStatus::Selected->value,
            'reject' => NewsEditorialStatus::Rejected->value,
            default => NewsEditorialStatus::Pending->value,
        }]);

        return back()->with('flash', 'Decisão registada.');
    }

    public function note(Request $request, CollectedNews $news)
    {
        $this->authorize('decide', $news);
        $data = $request->validate(['note' => ['required', 'string', 'max:2000']]);

        $news->notes()->create([
            'user_id' => $request->user()->id,
            'note' => $data['note'],
        ]);

        return back()->with('flash', 'Nota adicionada.');
    }

    public function convert(Request $request, CollectedNews $news, ArticleService $articles)
    {
        $this->authorize('convert', $news);

        if ($news->editorial_status === NewsEditorialStatus::Rejected) {
            return back()->withErrors(['convert' => 'Uma notícia rejeitada não pode ser convertida sem alterar o estado.']);
        }

        $article = $articles->createDraftFromNews($news, $request->user());

        return redirect()->route('admin.articles.edit', $article)
            ->with('flash', 'Rascunho criado a partir da notícia.');
    }

    public function collect(Request $request, NewsCollector $collector)
    {
        $this->authorize('collect', NewsSource::class);

        $sources = NewsSource::active()->get();
        foreach ($sources as $source) {
            // Assíncrono: não bloqueia o painel; processado pela fila (worker ou cron do queue:work).
            CollectNewsSourceJob::dispatch($source->id);
        }

        return back()->with('flash', 'Recolha iniciada em segundo plano para '.$sources->count().' fonte(s). A lista actualiza em instantes.');
    }

    public function runs()
    {
        $this->authorize('viewAny', CollectedNews::class);

        $runs = NewsFetchRun::with('source')->latest('started_at')->paginate(20)
            ->through(fn (NewsFetchRun $r) => [
                'id' => $r->id,
                'source' => $r->source?->name ?? '—',
                'status' => $r->status->value,
                'statusLabel' => $r->status->label(),
                'started_at' => $r->started_at?->translatedFormat('j M Y, H:i'),
                'items_found' => $r->items_found,
                'items_created' => $r->items_created,
                'error' => $r->error_message,
            ]);

        return Inertia::render('admin/news/runs', ['runs' => $runs]);
    }
}
