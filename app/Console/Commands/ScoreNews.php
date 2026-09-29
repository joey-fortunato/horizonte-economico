<?php

namespace App\Console\Commands;

use App\Models\CollectedNews;
use App\Services\News\RelevanceScorer;
use Illuminate\Console\Command;

class ScoreNews extends Command
{
    protected $signature = 'news:score {--all : Repontuar todas, incluindo já pontuadas}';

    protected $description = 'Calcula a relevância editorial das notícias recolhidas';

    public function handle(RelevanceScorer $scorer): int
    {
        $query = CollectedNews::query();
        if (! $this->option('all')) {
            $query->where('relevance_score', 0);
        }

        $count = 0;
        $query->chunkById(200, function ($chunk) use ($scorer, &$count) {
            foreach ($chunk as $news) {
                $r = $scorer->score($news->title, $news->description, $news->published_at);
                $news->forceFill([
                    'relevance_score' => $r['score'],
                    'relevance_terms' => $r['terms'],
                ])->saveQuietly();
                $count++;
            }
        });

        $this->info("{$count} notícias pontuadas.");

        return self::SUCCESS;
    }
}
