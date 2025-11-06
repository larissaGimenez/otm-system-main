<?php

namespace App\Enums\Contact;

enum ContactType: string
{
    // Casos do Enum
    case SINDICO = 'sindico';
    case SUB_SINDICO = 'sub_sindico';
    case ZELADOR = 'zelador';
    case OTHER = 'other';

    /**
     * Retorna a etiqueta legível para usar em selects e views.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::SINDICO     => 'Síndico',
            self::SUB_SINDICO => 'Sub-Síndico',
            self::ZELADOR     => 'Zelador',
            self::OTHER       => 'Outro',
        };
    }
}