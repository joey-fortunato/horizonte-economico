<?php

namespace App\Models;

use App\Enums\NewsFetchStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewsFetchRun extends Model
{
    protected $fillable = [
        'news_source_id', 'status', 'started_at', 'finished_at',
        'items_found', 'items_created', 'error_message',
    ];

    protected $casts = [
        'status' => NewsFetchStatus::class,
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function source(): BelongsTo
    {
        return $this->belongsTo(NewsSource::class, 'news_source_id');
    }
}
