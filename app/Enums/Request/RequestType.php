<?php

namespace App\Enums\Request;

enum RequestType: string
{
    case ACCESS      = 'access';     
    case INCIDENT    = 'incident';  
    case IMPROVEMENT = 'improvement';
    case SUPORTE     = 'support';  
    case MANUTENCAO_PDV = 'manutencao_pdv';

    public function getLabel(): string
    {
        return match ($this) {
            self::ACCESS      => 'Acesso',
            self::INCIDENT    => 'Incidente',
            self::IMPROVEMENT => 'Melhoria',
            self::SUPORTE     => 'Suporte Técnico',
            self::MANUTENCAO_PDV => 'Manutenção de PDV',
        };
    }
}