<?php

namespace App\Enums\Request;

enum RequestType: string
{
    case ACCESS    = 'access';     // Acesso
    case INCIDENT  = 'incident';   // Incidente
    case IMPROVEMENT = 'improvement'; // Melhoria
    case SUPORTE = 'support'; // Suporte

    public static function labels(): array
    {
        return [
            self::ACCESS->value      => 'Acesso',
            self::INCIDENT->value    => 'Incidente',
            self::IMPROVEMENT->value => 'Melhoria',
            self::SUPORTE->value     => 'Suporte Técnico',
        ];
    }
}
