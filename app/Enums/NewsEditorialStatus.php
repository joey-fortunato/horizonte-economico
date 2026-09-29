<?php

namespace App\Enums;

enum NewsEditorialStatus: string
{
    case Pending = 'pending';
    case Selected = 'selected';
    case Rejected = 'rejected';
    case Drafting = 'drafting';
    case Done = 'done';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Por analisar',
            self::Selected => 'Seleccionada',
            self::Rejected => 'Rejeitada',
            self::Drafting => 'Em redacção',
            self::Done => 'Concluída',
        };
    }
}
