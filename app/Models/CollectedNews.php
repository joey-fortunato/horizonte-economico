<?php

namespace App\Models;

use App\Enums\NewsEditorialStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CollectedNews extends Model
{
    protected $table = 'collected_news';

    protected $fillable = [
        'news_source_id', 'title', 'url', 'normalized_url', 'external_id',
        'source_name', 'description', 'published_at', 'fetched_at', 'editorial_status',
        'relevance_score', 'relevance_terms',
    ];

    protected $casts = [
        'editorial_status' => NewsEditorialStatus::class,
        'published_at' => 'datetime',
        'fetched_at' => 'datetime',
        'relevance_terms' => 'array',
    ];

    public function source(): BelongsTo
    {
        return $this->belongsTo(NewsSource::class, 'news_source_id');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(NewsEditorialNote::class);
    }

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_news_sources');
    }

    public function scopeStatus($query, string $status)
    {
        return $query->where('editorial_status', $status);
    }

    public function scopeHighlights($query)
    {
        return $query->where('relevance_score', '>=', (int) config('editorial.highlight_threshold', 8));
    }
}
