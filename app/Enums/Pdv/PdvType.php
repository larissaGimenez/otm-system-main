<?php

namespace App\Enums\Pdv;

enum PdvType: string
{
    case STORE   = 'store';    // loja física
    case KIOSK   = 'kiosk';    // quiosque
    case STAND   = 'stand';    // estande/balcão
    case ONLINE  = 'online';   // canal online (se aplicável)
    case OTHER   = 'other';    // outro

    public static function labels(): array
    {
        return [
            self::STORE->value  => 'Loja',
            self::KIOSK->value  => 'Quiosque',
            self::STAND->value  => 'Estande',
            self::ONLINE->value => 'Online',
            self::OTHER->value  => 'Outro',
        ];
    }
}
