<?php

namespace App\Enums\Client;

enum ClientProfile: string
{
    case A = 'A';   
    case B = 'B';
    case C  = 'C';   

    public function getLabel(): string
    {
        return match ($this) {
            self::A => 'A',
            self::B => 'B',
            self::C => 'C',
        };
    }
}
