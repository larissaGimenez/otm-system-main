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

    public static function labels(): array
    {
        return [
            self::NOTEBOOK->value => 'Notebook',
            self::DESKTOP->value  => 'Desktop',
            self::PRINTER->value  => 'Impressora',
            self::ROUTER->value   => 'Roteador',
            self::CAMERA->value  => 'Câmera',
            self::OTHER->value    => 'Outro',
            self::FECHADURA->value => 'Fechadura',
        ];
    }
}
