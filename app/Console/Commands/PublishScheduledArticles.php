<?php

namespace App\Console\Commands;

use App\Enums\ArticleStatus;
use App\Models\Article;
use Illuminate\Console\Command;

class PublishScheduledArticles extends Command
{
    protected $signature = 'articles:publish-scheduled';

    protected $description = 'Publica os artigos agendados cuja data de publicação já passou';

    public function handle(): int
    {
        $due = Article::scheduledDue()->get();

        foreach ($due as $article) {
            $article->update(['status' => ArticleStatus::Published->value]);
            $this->info("Publicado: {$article->title}");
        }

        $this->info($due->count().' artigo(s) publicado(s).');

        return self::SUCCESS;
    }
}
