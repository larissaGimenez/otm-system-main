<?php

namespace App\Enums\Equipment;

enum EquipmentType: string
{
    case NOTEBOOK = 'notebook';
    case DESKTOP  = 'desktop';
    case PRINTER  = 'printer';
    case ROUTER   = 'router';
    case CAMERA   = 'camera';
    case OTHER    = 'other';
    case FECHADURA = 'fechadura';

    public function getLabel(): string
    {
        return match ($this) {
            self::NOTEBOOK => 'Notebook',
            self::DESKTOP  => 'Desktop',
            self::PRINTER  => 'Impressora',
            self::ROUTER   => 'Roteador',
            self::CAMERA   => 'Câmera',
            self::OTHER    => 'Outro',
            self::FECHADURA => 'Fechadura',
        };
    }
}