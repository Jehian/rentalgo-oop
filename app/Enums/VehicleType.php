<?php

namespace App\Enums;

enum VehicleType: string
{
    case Car = 'car';
    case Motorcycle = 'motorcycle';

    public function label(): string
    {
        return match ($this) {
            self::Car => 'Mobil',
            self::Motorcycle => 'Motor',
        };
    }
}
