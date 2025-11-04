<?php

namespace App\Enums\Pdv;

enum FeePaymentMethod: string
{
    case BOLETO = 'boleto';
    case CREDIT_CARD = 'credit_card';
    case DEBIT_CARD = 'debit_card';
    case PIX = 'pix';
    case OTHER = 'other';

    public function getLabel(): string
    {
        return match ($this) {
            self::BOLETO => 'Boleto',
            self::CREDIT_CARD => 'Cartão de Crédito',
            self::DEBIT_CARD => 'Cartão de Débito',
            self::PIX => 'PIX',
            self::OTHER => 'Outro',
        };
    }
}