<?php

namespace App\Domain\Vehicles;

use App\Enums\VehicleStatus;
use App\Enums\VehicleType;
use InvalidArgumentException;

class Motorcycle extends Vehicle
{
    public function __construct(
        string $plateNumber,
        string $brand,
        string $model,
        float $dailyRate,
        VehicleStatus $status,
        private int $engineCc,
    ) {
        parent::__construct($plateNumber, $brand, $model, $dailyRate, $status);

        if ($this->engineCc < 1) {
            throw new InvalidArgumentException('Kapasitas mesin motor minimal 1 cc.');
        }
    }

    public function getType(): VehicleType
    {
        return VehicleType::Motorcycle;
    }

    public function calculateRentalCost(int $days): float
    {
        $this->validateDays($days);

        $baseCost = $this->getDailyRate() * $days;

        if ($days >= (int) config('rental.motorcycle_weekly_min_days', 7)) {
            $discount = $baseCost * (float) config('rental.motorcycle_weekly_discount_rate', 0.10);
            $baseCost -= $discount;
        }

        return $this->roundMoney($baseCost);
    }

    public function getEngineCc(): int
    {
        return $this->engineCc;
    }
}
