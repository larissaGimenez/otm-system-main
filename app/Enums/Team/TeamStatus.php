<?php

namespace App\Enums\Team;

enum TeamStatus: string
{
    case ACTIVE   = 'active';
    case INACTIVE = 'inactive';
    case ARCHIVED = 'archived';

    public static function labels(): array
    {
        return [
            self::ACTIVE->value   => 'Ativa',
            self::INACTIVE->value => 'Inativa',
            self::ARCHIVED->value => 'Arquivada',
        ];
    }
}
