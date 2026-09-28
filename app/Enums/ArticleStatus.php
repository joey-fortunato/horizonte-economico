<?php

namespace App\Enums;

enum ArticleStatus: string
{
    case Draft = 'draft';
    case Review = 'review';
    case Scheduled = 'scheduled';
    case Published = 'published';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Rascunho',
            self::Review => 'Em revisão',
            self::Scheduled => 'Agendado',
            self::Published => 'Publicado',
            self::Archived => 'Arquivado',
        };
    }
}
