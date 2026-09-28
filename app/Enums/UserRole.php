<?php

namespace App\Enums;

enum UserRole: string
{
    case Administrator = 'administrador';
    case Editor = 'editor';
    case Author = 'author';

    public function label(): string
    {
        return match ($this) {
            self::Administrator => 'Administrador',
            self::Editor => 'Editor',
            self::Author => 'Autor',
        };
    }

    /** Perfis com acesso ao backoffice. */
    public static function backoffice(): array
    {
        return [self::Administrator, self::Editor, self::Author];
    }
}
