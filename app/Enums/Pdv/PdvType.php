<?php

namespace App\Enums\Pdv;

enum PdvType: string
{
    // 1. Casos atualizados
    case COMERCIAL   = 'comercial';
    case RESIDENCIAL = 'residencial';
    case SHORTSTAY   = 'shortstay';

    public function getLabel(): string
    {
        return match ($this) {
            self::COMERCIAL   => 'Comercial',
            self::RESIDENCIAL => 'Residencial',
            self::SHORTSTAY   => 'Short-stay',
        };
    }
}