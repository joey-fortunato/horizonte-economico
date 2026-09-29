<?php

namespace App\Observers;

use App\Models\Article;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Cache;

class ArticleObserver
{
    public function created(Article $article): void
    {
        $this->flushHomeCache();
        $this->log($article, 'created');
    }

    public function saved(Article $article): void
    {
        $this->flushHomeCache();
    }

    private function flushHomeCache(): void
    {
        Cache::forget('home.pool');
        Cache::forget('home.mostread');
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
        $this->flushHomeCache();
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
