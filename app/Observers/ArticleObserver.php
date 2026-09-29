<?php

namespace App\Observers;

use App\Models\Article;
use App\Models\AuditLog;

class ArticleObserver
{
    public function created(Article $article): void
    {
        $this->log($article, 'created');
    }

    public function updated(Article $article): void
    {
        if ($article->wasChanged('status')) {
            $this->log($article, 'status_changed', [
                'from' => $article->getOriginal('status'),
                'to' => $article->status instanceof \App\Enums\ArticleStatus ? $article->status->value : $article->status,
            ]);
        }
    }

    public function deleted(Article $article): void
    {
        $this->log($article, 'deleted', ['title' => $article->title]);
    }

    private function log(Article $article, string $action, ?array $changes = null): void
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'auditable_type' => Article::class,
            'auditable_id' => $article->id,
            'action' => $action,
            'changes' => $changes,
            'created_at' => now(),
        ]);
    }
}
