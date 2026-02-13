<?php

namespace App\Enums\Request;

enum RequestStatus: string
{
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case LONG_SOLUTION = 'long_solution';
    case COMPLETED = 'completed';
    case CANCELED = 'canceled';

    public function getLabel(): string
    {
        return match ($this) {
            self::OPEN => 'Em Aberto',
            self::IN_PROGRESS => 'Em Andamento',
            self::LONG_SOLUTION => 'Solução Longa',
            self::COMPLETED => 'Concluído',
            self::CANCELED => 'Cancelado',
        };
    }

    public function colors(): string
    {
        return match ($this) {
            self::OPEN => 'primary',           // Azul para 'Em Aberto'
            self::IN_PROGRESS => 'info',       // Ciano para 'Em Andamento'
            self::LONG_SOLUTION => 'warning',  // Amarelo para 'Solução Longa'
            self::COMPLETED => 'success',      // Verde para 'Concluído'
            self::CANCELED => 'danger',        // Vermelho para 'Cancelado'
        };
    }
}
