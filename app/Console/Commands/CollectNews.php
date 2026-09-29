<?php

namespace App\Console\Commands;

use App\Models\NewsSource;
use App\Services\News\NewsCollector;
use Illuminate\Console\Command;

class CollectNews extends Command
{
    protected $signature = 'news:collect {--source= : ID de uma fonte específica}';

    protected $description = 'Recolhe notícias das fontes activas (RSS / Google News RSS)';

    public function handle(NewsCollector $collector): int
    {
        $query = NewsSource::query()->active();
        if ($id = $this->option('source')) {
            $query->whereKey($id);
        }

        $sources = $query->get();
        if ($sources->isEmpty()) {
            $this->info('Sem fontes activas para recolher.');

            return self::SUCCESS;
        }

        foreach ($sources as $source) {
            $run = $collector->collect($source);
            $this->line(sprintf(
                '[%s] %s — %d encontrados, %d novos',
                $run->status->value,
                $source->name,
                $run->items_found,
                $run->items_created,
            ));
        }

        return self::SUCCESS;
    }
}
