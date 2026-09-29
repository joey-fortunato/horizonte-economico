<?php

namespace App\Services\News;

use App\Enums\NewsEditorialStatus;
use App\Enums\NewsFetchStatus;
use App\Models\CollectedNews;
use App\Models\NewsFetchRun;
use App\Models\NewsSource;
use Illuminate\Database\UniqueConstraintViolationException;

class NewsCollector
{
    public function __construct(
        private FeedFetcher $fetcher,
        private UrlNormalizer $normalizer,
        private RelevanceScorer $scorer,
    ) {}

    /** Recolhe uma fonte, isolando erros, e regista a execução. */
    public function collect(NewsSource $source): NewsFetchRun
    {
        $run = NewsFetchRun::create([
            'news_source_id' => $source->id,
            'status' => NewsFetchStatus::Running->value,
            'started_at' => now(),
        ]);

        try {
            $items = $this->fetcher->fetch($source);
            $created = 0;

            foreach ($items as $item) {
                if ($this->persist($source, $item)) {
                    $created++;
                }
            }

            $run->update([
                'status' => NewsFetchStatus::Completed->value,
                'finished_at' => now(),
                'items_found' => count($items),
                'items_created' => $created,
            ]);

            $source->update([
                'last_fetched_at' => now(),
                'last_status' => NewsFetchStatus::Completed->value,
            ]);
        } catch (\Throwable $e) {
            $run->update([
                'status' => NewsFetchStatus::Failed->value,
                'finished_at' => now(),
                'error_message' => mb_substr($e->getMessage(), 0, 1000),
            ]);
            $source->update(['last_status' => NewsFetchStatus::Failed->value]);
        }

        return $run->fresh();
    }

    /** Guarda um item se não for duplicado. Devolve true se criou. */
    private function persist(NewsSource $source, array $item): bool
    {
        $normalized = $this->normalizer->normalize($item['url']);

        $exists = CollectedNews::query()
            ->when($item['external_id'], fn ($q) => $q->where('external_id', $item['external_id']))
            ->when(! $item['external_id'], fn ($q) => $q->where('normalized_url', $normalized))
            ->exists();

        if ($exists) {
            return false;
        }

        $relevance = $this->scorer->score($item['title'], $item['description'], $item['published_at']);

        try {
            CollectedNews::create([
                'news_source_id' => $source->id,
                'relevance_score' => $relevance['score'],
                'relevance_terms' => $relevance['terms'],
                'title' => mb_substr($item['title'], 0, 255),
                'url' => mb_substr($item['url'], 0, 1024),
                'normalized_url' => mb_substr($normalized, 0, 1024),
                'external_id' => $item['external_id'] ? mb_substr($item['external_id'], 0, 1024) : null,
                'source_name' => $item['source_name'],
                'description' => $item['description'],
                'published_at' => $item['published_at'],
                'fetched_at' => now(),
                'editorial_status' => NewsEditorialStatus::Pending->value,
            ]);

            return true;
        } catch (UniqueConstraintViolationException) {
            // Corrida: outro processo já inseriu o mesmo normalized_url.
            return false;
        }
    }
}
