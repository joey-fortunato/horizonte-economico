<?php

namespace App\Enums;

enum NewsFetchStatus: string
{
    case Pending = 'pending';
    case Running = 'running';
    case Completed = 'completed';
    case CompletedWithErrors = 'completed_with_errors';
    case Failed = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pendente',
            self::Running => 'Em execução',
            self::Completed => 'Concluída',
            self::CompletedWithErrors => 'Concluída com erros',
            self::Failed => 'Falhou',
        };
    }
}
