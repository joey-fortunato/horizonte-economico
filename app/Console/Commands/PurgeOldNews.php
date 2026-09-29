<?php

namespace App\Console\Commands;

use App\Enums\NewsEditorialStatus;
use App\Models\CollectedNews;
use App\Models\NewsFetchRun;
use Illuminate\Console\Command;

class PurgeOldNews extends Command
{
    protected $signature = 'news:purge';

    protected $description = 'Remove notícias rejeitadas antigas e histórico de recolhas fora da retenção';

    public function handle(): int
    {
        $rejectedDays = (int) config('news.retention_rejected_days', 90);
        $runsDays = (int) config('news.retention_runs_days', 30);

        $rejected = CollectedNews::where('editorial_status', NewsEditorialStatus::Rejected->value)
            ->where('created_at', '<', now()->subDays($rejectedDays))
            ->delete();

        $runs = NewsFetchRun::where('created_at', '<', now()->subDays($runsDays))->delete();

        $this->info("Purga: {$rejected} notícias rejeitadas e {$runs} execuções removidas.");

        return self::SUCCESS;
    }
}
