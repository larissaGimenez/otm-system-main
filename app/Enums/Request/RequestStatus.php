<?php

namespace App\Enums\Request;

enum RequestStatus: string
{
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case RESOLVED = 'resolved';
    case CLOSED = 'closed';
    case CANCELED = 'canceled';

    public static function labels(): array
    {
        return [
            self::OPEN->value => 'Aberto',
            self::IN_PROGRESS->value => 'Em andamento',
            self::RESOLVED->value => 'Resolvido',
            self::CLOSED->value => 'Fechado',
            self::CANCELED->value => 'Cancelado',
        ];
    }
}
