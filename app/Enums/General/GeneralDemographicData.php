<?php

namespace App\Enums\General;

enum GeneralDemographicData: string
{
    case NUMBER_OF_APARTMENTS = 'number_of_apartments';   // apartments
    case NUMBER_OF_RESIDENTS = 'number_of_residents';   // residents


    public function getLabel(): string
    {
        return match ($this) {
            self::NUMBER_OF_APARTMENTS => 'Número de Apartamentos',
            self::NUMBER_OF_RESIDENTS => 'Número de Residentes',
        };
    }
}
