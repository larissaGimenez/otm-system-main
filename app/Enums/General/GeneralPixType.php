<?php

namespace App\Enums\General;

enum GeneralPixType: string
{
    case CPF = 'cpf';   // cpf
    case CNPJ = 'cnpj'; // cnpj
    case RANDOM_KEY  = 'random_key';    // random_key
    case EMAIL = 'email';    // email
    case PHONE = 'phone';    // phone

    public static function labels(): array
    {
        return [
            self::CPF->value  => 'CPF',
            self::CNPJ->value => 'CNPJ',
            self::RANDOM_KEY->value   => 'Chave Aleatória',
            self::EMAIL->value   => 'Email',
            self::PHONE->value   => 'Telefone',
        ];
    }
}
