<?php

namespace App\Enums\General;

enum GeneralDemographicData: string
{
    case NUMBER_OF_APARTMENTS = 'number_of_apartments';   // apartments
    case NUMBER_OF_RESIDENTS = 'number_of_residents';   // residents


    public static function labels(): array
    {
        return [
            self::NUMBER_OF_APARTMENTS->value  => 'Número de Apartamentos',
            self::NUMBER_OF_RESIDENTS->value => 'Número de Residentes',
        ];
    }
}
