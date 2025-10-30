<?php

namespace App\Enums\Pdv;

enum PdvStatus: string
{
    case ACTIVE   = 'active';
    case INACTIVE = 'inactive';
    case CLOSED   = 'closed';

    /**
     * Retorna o label (texto) para este caso específico.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::ACTIVE   => 'Ativo',
            self::INACTIVE => 'Inativo',
            self::CLOSED   => 'Encerrado',
        };
    }

    /**
     * Retorna uma classe de cor Bootstrap para o status.
     */
    public function getColorClass(): string
    {
        return match ($this) {
            self::ACTIVE   => 'success', // verde
            self::INACTIVE => 'warning', // amarelo
            self::CLOSED   => 'danger',  // vermelho
        };
    }
}