<?php

namespace App\Enums\Request;

enum RequestStatus: string
{
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case RESOLVED = 'resolved';
    case CLOSED = 'closed';
    case CANCELED = 'canceled';

    public function getLabel(): string
    {
        return match ($this) {
            self::OPEN => 'Aberto',
            self::IN_PROGRESS => 'Em andamento',
            self::RESOLVED => 'Resolvido',
            self::CLOSED => 'Fechado',
            self::CANCELED => 'Cancelado',
        };
    }

    public function colors(): string
    {
        return match ($this) {
            self::OPEN => 'primary',        // Azul para 'Aberto'
            self::IN_PROGRESS => 'warning',    // Amarelo para 'Em andamento'
            self::RESOLVED => 'success',    // Verde para 'Resolvido'
            self::CLOSED => 'secondary',    // Cinza para 'Fechado'
            self::CANCELED => 'danger',     // Vermelho para 'Cancelado'
        };
    }
}
