<?php

namespace App\Policies;

use App\Models\CollectedNews;
use App\Models\User;

class CollectedNewsPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canAccessBackoffice();
    }

    public function view(User $user, CollectedNews $news): bool
    {
        return $user->canAccessBackoffice();
    }

    /** Decidir (seleccionar/rejeitar) e anotar — editor/admin. */
    public function decide(User $user, CollectedNews $news): bool
    {
        return $user->canPublish();
    }

    /** Converter em rascunho — qualquer perfil do backoffice. */
    public function convert(User $user, CollectedNews $news): bool
    {
        return $user->canAccessBackoffice();
    }
}
