<?php

namespace App\Enums\Client;

enum ClientType: string
{
    case COMMERCIAL = 'commercial';   // comercial
    case RESIDENTIAL = 'residential'; // residencial
    case SHORTSTAY  = 'shortstay';    // shortstay

    public function getLabel(): string
    {
        return match ($this) {
            self::COMMERCIAL  => 'Comercial',
            self::RESIDENTIAL => 'Residencial',
            self::SHORTSTAY   => 'Short Stay',
        };
    }
}
