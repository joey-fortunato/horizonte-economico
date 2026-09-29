<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ArticleStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Models\Category;
use App\Models\Media;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Article::class);

        $status = $request->query('status');

        $articles = Article::with(['author', 'category'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($request->query('q'), fn ($q, $term) => $q->where('title', 'like', "%{$term}%"))
            // autores só veem os próprios
            ->when($request->user()->isAuthor(), fn ($q) => $q->where('author_id', $request->user()->id))
            ->latest('updated_at')
            ->paginate(12)
            ->withQueryString()
            ->through(fn (Article $a) => [
                'id' => $a->id,
                'title' => $a->title,
                'slug' => $a->slug,
                'category' => $a->category?->name,
                'color' => $a->category?->color,
                'author' => $a->author->name,
                'status' => $a->status->value,
                'statusLabel' => $a->status->label(),
                'updated' => $a->updated_at->diffForHumans(),
                'canEdit' => $request->user()->can('update', $a),
                'canPublish' => $request->user()->can('publish', $a),
                'canDelete' => $request->user()->can('delete', $a),
            ]);

        $counts = Article::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

        return Inertia::render('admin/articles/index', [
            'articles' => $articles,
            'filters' => ['status' => $status, 'q' => $request->query('q')],
            'counts' => $counts,
            'statuses' => collect(ArticleStatus::cases())->map(fn ($s) => ['value' => $s->value, 'label' => $s->label()]),
        ]);
    }

    public function create()
    {
        $this->authorize('create', Article::class);

        return Inertia::render('admin/articles/edit', $this->formData(new Article(['status' => ArticleStatus::Draft->value])));
    }

    public function store(ArticleRequest $request)
    {
        $this->authorize('create', Article::class);

        $publishing = in_array($request->status, [
            ArticleStatus::Published->value,
            ArticleStatus::Scheduled->value,
            ArticleStatus::Archived->value,
        ], true);
        if ($publishing && ! $request->user()->canPublish()) {
            abort(403, 'Sem permissão para publicar. Guarde como rascunho ou envie para revisão.');
        }

        $article = new Article();
        $this->fill($article, $request);
        $article->author_id = $request->user()->id;
        $article->save();
        $article->tags()->sync($request->input('tags', []));

        return redirect()->route('admin.articles.edit', $article)->with('flash', 'Artigo criado.');
    }

    public function edit(Article $article)
    {
        $this->authorize('update', $article);

        return Inertia::render('admin/articles/edit', $this->formData($article));
    }

    public function update(ArticleRequest $request, Article $article)
    {
        $this->authorize('update', $article);

        // Só quem pode publicar altera para published/scheduled/archived
        if (in_array($request->status, [ArticleStatus::Published->value, ArticleStatus::Scheduled->value, ArticleStatus::Archived->value], true)
            && ! $request->user()->can('publish', $article)) {
            abort(403, 'Sem permissão para alterar o estado de publicação.');
        }

        $this->fill($article, $request);
        $article->save();
        $article->tags()->sync($request->input('tags', []));

        return back()->with('flash', 'Artigo actualizado.');
    }

    public function destroy(Article $article)
    {
        $this->authorize('delete', $article);
        $article->delete();

        return redirect()->route('admin.articles.index')->with('flash', 'Artigo eliminado.');
    }

    private function fill(Article $article, ArticleRequest $request): void
    {
        $data = $request->validated();

        $article->title = $data['title'];
        $article->slug = ($data['slug'] ?? null) ?: Str::slug($data['title']).'-'.Str::lower(Str::random(5));
        $article->excerpt = $data['excerpt'] ?? null;
        $article->body = $data['body'] ?? null;
        $article->category_id = $data['category_id'] ?? null;
        $article->status = $data['status'];
        $article->published_at = $data['published_at'] ?? ($data['status'] === ArticleStatus::Published->value ? now() : null);
        $article->reading_minutes = $data['reading_minutes'] ?? max(1, (int) ceil(str_word_count(strip_tags($data['body'] ?? '')) / 200));
        $article->seo_title = $data['seo_title'] ?? null;
        $article->seo_description = $data['seo_description'] ?? null;
        $article->correction_note = $data['correction_note'] ?? null;

        if ($request->hasFile('cover')) {
            $result = app(\App\Services\ImageService::class)->store($request->file('cover'), 'covers');
            $media = Media::create([
                'disk' => 'public',
                'path' => $result['path'],
                'alt_text' => $data['title'],
                'mime_type' => $result['mime_type'],
                'size' => $result['size'],
                'width' => $result['width'],
                'height' => $result['height'],
                'variants' => $result['variants'],
                'uploaded_by' => $request->user()->id,
            ]);
            $article->cover_media_id = $media->id;
        }
    }

    private function formData(Article $article): array
    {
        $article->loadMissing(['tags', 'cover']);

        return [
            'article' => [
                'id' => $article->id,
                'title' => $article->title,
                'slug' => $article->slug,
                'excerpt' => $article->excerpt,
                'body' => $article->body,
                'category_id' => $article->category_id,
                'status' => $article->status instanceof ArticleStatus ? $article->status->value : ($article->status ?? 'draft'),
                'published_at' => $article->published_at?->format('Y-m-d\TH:i'),
                'seo_title' => $article->seo_title,
                'seo_description' => $article->seo_description,
                'correction_note' => $article->correction_note,
                'tags' => $article->tags->pluck('id'),
                'cover_url' => $article->cover?->url(),
            ],
            'categories' => Category::orderBy('name')->get(['id', 'name', 'color']),
            'allTags' => Tag::orderBy('name')->get(['id', 'name']),
            'statuses' => collect(ArticleStatus::cases())->map(fn ($s) => ['value' => $s->value, 'label' => $s->label()]),
            'canPublish' => request()->user()->canPublish(),
        ];
    }
}
