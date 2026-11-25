<?php

namespace Tests\Unit\Domain;

use App\Domain\Vehicles\Car;
use App\Enums\VehicleStatus;
use App\Enums\VehicleType;
use InvalidArgumentException;
use Tests\TestCase;

class CarTest extends TestCase
{
    public function test_calculates_daily_rate_plus_insurance(): void
    {
        $car = new Car('B 1234 ABC', 'Toyota', 'Avanza', 300000, VehicleStatus::Available, 7, 'Matic');

        $this->assertSame(990000.0, $car->calculateRentalCost(3));
        $this->assertSame(VehicleType::Car, $car->getType());
        $this->assertTrue($car->isAvailable());
    }

    public function test_rejects_invalid_duration(): void
    {
        $car = new Car('B 1234 ABC', 'Toyota', 'Avanza', 300000, VehicleStatus::Available, 7, 'Matic');

        $this->expectException(InvalidArgumentException::class);

        $car->calculateRentalCost(0);
    }

    public function test_reports_unavailable_status(): void
    {
        $car = new Car('B 1234 ABC', 'Toyota', 'Avanza', 300000, VehicleStatus::Rented, 7, 'Matic');

        $this->assertFalse($car->isAvailable());
    }
}
