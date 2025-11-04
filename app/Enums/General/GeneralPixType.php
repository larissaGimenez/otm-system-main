<?php

namespace App\Enums\General;

enum GeneralPixType: string
{
    case CPF = 'cpf';   // cpf
    case CNPJ = 'cnpj'; // cnpj
    case RANDOM_KEY  = 'random_key';    // random_key
    case EMAIL = 'email';    // email
    case PHONE = 'phone';    // phone

    public function getLabel(): string
    {
        return match ($this) {
            self::CPF  => 'CPF',
            self::CNPJ => 'CNPJ',
            self::RANDOM_KEY   => 'Chave Aleatória',
            self::EMAIL   => 'Email',
            self::PHONE   => 'Telefone',
        };
    }
}
