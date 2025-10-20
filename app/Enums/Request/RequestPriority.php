<?php

namespace App\Enums\Request;

enum RequestPriority: string
{
    case LOW = 'low';
    case NORMAL = 'normal';
    case HIGH = 'high';
    case URGENT = 'urgent';

    public static function labels(): array
    {
        return [
            self::LOW->value => 'Baixa',
            self::NORMAL->value => 'Normal',
            self::HIGH->value => 'Alta',
            self::URGENT->value => 'Urgente',
        ];
    }

    public static function colors(): array
    {
        return [
            self::LOW->value => 'secondary',   // cinza
            self::NORMAL->value => 'primary',  // azul
            self::HIGH->value => 'warning',    // amarelo
            self::URGENT->value => 'danger',   // vermelho
        ];
    }
}
