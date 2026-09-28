<?php

namespace App\Policies;

use App\Enums\ArticleStatus;
use App\Models\Article;
use App\Models\User;

class ArticlePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canAccessBackoffice();
    }

    public function view(User $user, Article $article): bool
    {
        return $user->canAccessBackoffice();
    }

    public function create(User $user): bool
    {
        return $user->canAccessBackoffice();
    }

    public function update(User $user, Article $article): bool
    {
        // Admin e editor editam qualquer artigo.
        if ($user->canPublish()) {
            return true;
        }

        // Autor edita apenas os próprios e enquanto não estiver publicado.
        return $user->isAuthor()
            && $article->author_id === $user->id
            && $article->status !== ArticleStatus::Published;
    }

    /** Publicar, agendar ou arquivar — apenas admin/editor. */
    public function publish(User $user, Article $article): bool
    {
        return $user->canPublish();
    }

    public function delete(User $user, Article $article): bool
    {
        return $user->isAdmin()
            || ($user->isAuthor() && $article->author_id === $user->id && $article->status === ArticleStatus::Draft);
    }
}
