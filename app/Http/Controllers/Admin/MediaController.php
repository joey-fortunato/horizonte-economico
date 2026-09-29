<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Media;
use Illuminate\Http\Request;
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

        $result = app(\App\Services\ImageService::class)->store($request->file('file'), 'media');

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

    public function destroy(Media $media)
    {
        $this->authorize('delete', $media);

        if (Article::where('cover_media_id', $media->id)->exists()) {
            return back()->withErrors(['media' => 'Imagem em uso por um artigo. Remova a associação primeiro.']);
        }

        \Illuminate\Support\Facades\Storage::disk($media->disk ?? 'public')->delete($media->path);
        $media->delete();

        return back()->with('flash', 'Imagem eliminada.');
    }
}
