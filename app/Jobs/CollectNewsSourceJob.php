<?php

namespace App\Jobs;

use App\Models\NewsSource;
use App\Services\News\NewsCollector;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CollectNewsSourceJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $sourceId) {}

    public function handle(NewsCollector $collector): void
    {
        $source = NewsSource::active()->find($this->sourceId);
        if ($source) {
            $collector->collect($source);
        }
    }
}
