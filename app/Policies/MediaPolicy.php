<?php

namespace App\Policies;

use App\Models\Media;
use App\Models\User;

class MediaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canAccessBackoffice();
    }

    public function create(User $user): bool
    {
        return $user->canAccessBackoffice();
    }

    public function delete(User $user, Media $media): bool
    {
        // Admin/editor, ou quem carregou o ficheiro.
        return $user->canPublish() || $media->uploaded_by === $user->id;
    }
}
