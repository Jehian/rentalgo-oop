<?php

namespace App\Contracts;

use App\Enums\VehicleType;

interface Rentable
{
    public function calculateRentalCost(int $days): float;

    public function isAvailable(): bool;

    public function getType(): VehicleType;
}
