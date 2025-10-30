<?php

namespace App\Enums\Request;

enum RequestType: string
{
    case ACCESS      = 'access';     // Acesso
    case INCIDENT    = 'incident';   // Incidente
    case IMPROVEMENT = 'improvement';// Melhoria
    case SUPORTE     = 'support';    // Suporte

    public function getLabel(): string
    {
        return match ($this) {
            self::ACCESS      => 'Acesso',
            self::INCIDENT    => 'Incidente',
            self::IMPROVEMENT => 'Melhoria',
            self::SUPORTE     => 'Suporte Técnico',
        };
    }
}