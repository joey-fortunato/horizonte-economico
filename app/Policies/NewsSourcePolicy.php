<?php

namespace App\Policies;

use App\Models\NewsSource;
use App\Models\User;

class NewsSourcePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canAccessBackoffice();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, NewsSource $source): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, NewsSource $source): bool
    {
        return $user->isAdmin();
    }

    /** Executar recolha manual. */
    public function collect(User $user): bool
    {
        return $user->isAdmin();
    }
}
