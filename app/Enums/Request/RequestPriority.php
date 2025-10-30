<?php

namespace App\Enums\Request;

enum RequestPriority: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case URGENT = 'urgent';

    public function getLabel(): string
    {
        return match ($this) {
            self::LOW => 'Baixa',
            self::MEDIUM => 'Média',
            self::HIGH => 'Alta',
            self::URGENT => 'Urgente',
        };
    }

    public function colors(): string
    {
        return match ($this) {
            self::LOW => 'secondary',   // cinza
            self::MEDIUM => 'primary',  // azul
            self::HIGH => 'warning',    // amarelo
            self::URGENT => 'danger',   // vermelho
        };
    }
}
