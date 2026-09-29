<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewsEditorialNote extends Model
{
    protected $fillable = ['collected_news_id', 'user_id', 'note'];

    public function news(): BelongsTo
    {
        return $this->belongsTo(CollectedNews::class, 'collected_news_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
