<?php

namespace App\Enums\Clients;

enum ClientType: string
{
    case COMMERCIAL = 'commercial';   // comercial
    case RESIDENTIAL = 'residential'; // residencial
    case SHORTSTAY  = 'shortstay';    // shortstay

    public static function labels(): array
    {
        return [
            self::COMMERCIAL->value  => 'Comercial',
            self::RESIDENTIAL->value => 'Residencial',
            self::SHORTSTAY->value   => 'Short Stay',
        ];
    }
}
