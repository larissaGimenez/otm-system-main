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


    public function getLabel(): string
    {
        return match ($this) {
            self::ITAU  => 'Itaú',
            self::BRADESCO => 'Bradesco',
            self::BANCO_DO_BRASIL   => 'Banco do Brasil',
            self::SANTANDER   => 'Santander',
            self::NUBANK   => 'Nubank',
            self::INTER  => 'Inter',
            self::MERCADO_PAGO   => 'Mercado Pago',
        };
    }
}
