<?php

namespace App\Enums\Condominium;

enum CondominiumContactType: string
{
    case SYNDIC = 'syndic';   // síndico(a)
    case JANITOR = 'janitor'; // zelador(a)
    case OTHER  = 'other';    // outro(a)

    public static function labels(): array
    {
        return [
            self::SYNDIC->value  => 'Síndico(a)',
            self::JANITOR->value => 'Zelador(a)',
            self::OTHER->value   => 'Outro(a)',
        ];
    }
}
