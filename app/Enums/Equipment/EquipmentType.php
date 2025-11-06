<?php

namespace App\Enums\Equipment;

enum EquipmentType: string
{

    case NOTEBOOK = 'notebook';
    case DESKTOP  = 'desktop';
    case PRINTER  = 'printer';
    case ROUTER   = 'router';
    case CAMERA   = 'camera';
    case LOCK = 'lock';
    case TV = 'tv';
    case REFRIGERATOR = 'refrigerator';
    case OTHER = 'other';

    public function getLabel(): string
    {
        return match ($this) {
            self::NOTEBOOK     => 'Notebook',
            self::DESKTOP      => 'Desktop',
            self::PRINTER      => 'Impressora',
            self::ROUTER       => 'Roteador',
            self::CAMERA       => 'Câmera',
            self::LOCK         => 'Fechadura / Trava',
            self::TV           => 'Televisor (TV)',
            self::REFRIGERATOR => 'Refrigerador / Geladeira',
            self::OTHER        => 'Outro',
        };
    }
}