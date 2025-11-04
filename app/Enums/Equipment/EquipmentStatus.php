<?php

namespace App\Enums\Equipment;

enum EquipmentStatus: string
{
    case AVAILABLE = 'available';   // Disponível
    case IN_USE    = 'in_use';      // Em uso
    case MAINTENANCE = 'maintenance'; // Manutenção
    case RETIRED   = 'retired';     // Baixado

    public function getLabel(): string
    {
        return match ($this) {
            self::AVAILABLE   => 'Disponível',
            self::IN_USE      => 'Em uso',
            self::MAINTENANCE => 'Manutenção',
            self::RETIRED     => 'Baixado',
        };
    }

    public function getColorClass(): string
    {
        return match ($this) {
            self::AVAILABLE   => 'success',
            self::IN_USE      => 'primary',
            self::MAINTENANCE => 'warning',
            self::RETIRED     => 'secondary',
        };
    }
}