<?php

namespace App\Models;

use App\Enums\ArticleStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Article extends Model
{
    protected $fillable = [
        'author_id', 'category_id', 'cover_media_id', 'share_media_id',
        'title', 'slug', 'excerpt', 'body', 'status', 'published_at',
        'reading_minutes', 'views_count', 'seo_title', 'seo_description',
        'sources', 'correction_note',
    ];

    protected $casts = [
        'status' => ArticleStatus::class,
        'published_at' => 'datetime',
        'sources' => 'array',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function cover(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'cover_media_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** Apenas artigos publicados e com data de publicação já passada. */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', ArticleStatus::Published->value)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeScheduledDue(Builder $query): Builder
    {
        return $query->where('status', ArticleStatus::Scheduled->value)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }
}
