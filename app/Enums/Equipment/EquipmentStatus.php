<?php

namespace App\Enums\Equipment;

enum EquipmentStatus: string
{
    case AVAILABLE = 'available';   // Disponível
    case IN_USE    = 'in_use';      // Em uso
    case MAINTENANCE = 'maintenance'; // Manutenção
    case RETIRED   = 'retired';     // Baixado

    public static function labels(): array
    {
        return [
            self::AVAILABLE->value   => 'Disponível',
            self::IN_USE->value      => 'Em uso',
            self::MAINTENANCE->value => 'Manutenção',
            self::RETIRED->value     => 'Baixado',
        ];
    }
}
