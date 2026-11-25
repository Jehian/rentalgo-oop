<?php

namespace App\Domain\Vehicles;

use App\Enums\VehicleStatus;
use App\Enums\VehicleType;
use InvalidArgumentException;

class Car extends Vehicle
{
    public function __construct(
        string $plateNumber,
        string $brand,
        string $model,
        float $dailyRate,
        VehicleStatus $status,
        private int $seatCount,
        private string $transmission,
    ) {
        parent::__construct($plateNumber, $brand, $model, $dailyRate, $status);

        if ($this->seatCount < 1) {
            throw new InvalidArgumentException('Jumlah kursi mobil minimal 1.');
        }

        if (trim($this->transmission) === '') {
            throw new InvalidArgumentException('Transmisi mobil wajib diisi.');
        }
    }

    public function getType(): VehicleType
    {
        return VehicleType::Car;
    }

    public function calculateRentalCost(int $days): float
    {
        $this->validateDays($days);

        $baseCost = $this->getDailyRate() * $days;
        $insurance = $baseCost * (float) config('rental.car_insurance_rate', 0.10);

        return $this->roundMoney($baseCost + $insurance);
    }

    public function getSeatCount(): int
    {
        return $this->seatCount;
    }

    public function getTransmission(): string
    {
        return $this->transmission;
    }
}
