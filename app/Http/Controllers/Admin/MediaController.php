<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Media;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class MediaController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Media::class);

        $media = Media::latest()->paginate(24)->through(fn (Media $m) => [
            'id' => $m->id,
            'url' => $m->url(),
            'alt_text' => $m->alt_text,
            'mime_type' => $m->mime_type,
            'size' => $m->size,
            'in_use' => Article::where('cover_media_id', $m->id)->exists(),
        ]);

        return Inertia::render('admin/media/index', ['media' => $media]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Media::class);

        $request->validate([
            'file' => ['required', 'image', 'max:5120'],
            'alt_text' => ['nullable', 'string', 'max:255'],
        ]);

        $result = app(ImageService::class)->store($request->file('file'), 'media');

        Media::create([
            'disk' => 'public',
            'path' => $result['path'],
            'alt_text' => $request->input('alt_text'),
            'mime_type' => $result['mime_type'],
            'size' => $result['size'],
            'width' => $result['width'],
            'height' => $result['height'],
            'variants' => $result['variants'],
            'uploaded_by' => $request->user()->id,
        ]);

        return back()->with('flash', 'Imagem carregada.');
    }

    /** Lista JSON para o seletor de imagens do editor. */
    public function list()
    {
        $this->authorize('viewAny', Media::class);

        return response()->json(
            Media::latest()->take(60)->get()->map(fn (Media $m) => [
                'id' => $m->id,
                'url' => $m->url(),
                'srcset' => $m->srcset(),
                'alt' => $m->alt_text,
            ])
        );
    }

    /** Upload rápido a partir do editor; devolve JSON. */
    public function upload(Request $request)
    {
        $this->authorize('create', Media::class);

        $request->validate([
            'file' => ['required', 'image', 'max:5120'],
            'alt_text' => ['nullable', 'string', 'max:255'],
        ]);

        $result = app(ImageService::class)->store($request->file('file'), 'media');

        $media = Media::create([
            'disk' => 'public',
            'path' => $result['path'],
            'alt_text' => $request->input('alt_text'),
            'mime_type' => $result['mime_type'],
            'size' => $result['size'],
            'width' => $result['width'],
            'height' => $result['height'],
            'variants' => $result['variants'],
            'uploaded_by' => $request->user()->id,
        ]);

        return response()->json([
            'id' => $media->id,
            'url' => $media->url(),
            'srcset' => $media->srcset(),
            'alt' => $media->alt_text,
        ]);
    }

    public function destroy(Media $media)
    {
        $this->authorize('delete', $media);

        if (Article::where('cover_media_id', $media->id)->exists()) {
            return back()->withErrors(['media' => 'Imagem em uso por um artigo. Remova a associação primeiro.']);
        }

        Storage::disk($media->disk ?? 'public')->delete($media->path);
        $media->delete();

        return back()->with('flash', 'Imagem eliminada.');
    }
}
