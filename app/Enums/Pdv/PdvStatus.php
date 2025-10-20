<?php

namespace App\Enums\Pdv;

enum PdvStatus: string
{
    case ACTIVE   = 'active';
    case INACTIVE = 'inactive';
    case CLOSED   = 'closed';

    public static function labels(): array
    {
        return [
            self::ACTIVE->value   => 'Ativo',
            self::INACTIVE->value => 'Inativo',
            self::CLOSED->value   => 'Encerrado',
        ];
    }
}
