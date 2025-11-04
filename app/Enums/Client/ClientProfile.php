<?php

namespace App\Enums\Client;

enum ClientProfile: string
{
    case A = 'A';   
    case B = 'B';
    case C  = 'C';   

    public static function labels(): array
    {
        return [
            self::A->value  => 'A',
            self::B->value => 'B',
            self::C->value   => 'C',
        ];
    }
}
