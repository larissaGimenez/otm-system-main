<?php

namespace App\Enums\General;

enum GeneralBanks: string
{
    case ITAU = 'itau';   // itau
    case BRADESCO = 'bradesco'; // bradesco
    case BANCO_DO_BRASIL  = 'banco_do_brasil';    // banco_do_brasil
    case SANTANDER = 'santander';
    case NUBANK = 'nubank';
    case INTER = 'inter';
    case MERCADO_PAGO = 'mercado_pago';


    public static function labels(): array
    {
        return [
            self::ITAU->value  => 'Itaú',
            self::BRADESCO->value => 'Bradesco',
            self::BANCO_DO_BRASIL->value   => 'Banco do Brasil',
            self::SANTANDER->value   => 'Santander',
            self::NUBANK->value   => 'Nubank',  
            self::INTER->value   => 'Inter',
            self::MERCADO_PAGO->value   => 'Mercado Pago',
        ];
    }
}
