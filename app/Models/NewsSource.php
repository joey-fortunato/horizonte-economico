<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NewsSource extends Model
{
    protected $fillable = [
        'name', 'url', 'type', 'is_active', 'config', 'last_fetched_at', 'last_status',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'config' => 'array',
        'last_fetched_at' => 'datetime',
    ];

    public function news(): HasMany
    {
        return $this->hasMany(CollectedNews::class);
    }

    public function runs(): HasMany
    {
        return $this->hasMany(NewsFetchRun::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
