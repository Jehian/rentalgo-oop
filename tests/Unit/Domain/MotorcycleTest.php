<?php

namespace Tests\Unit\Domain;

use App\Domain\Vehicles\Motorcycle;
use App\Enums\VehicleStatus;
use App\Enums\VehicleType;
use InvalidArgumentException;
use Tests\TestCase;

class MotorcycleTest extends TestCase
{
    public function test_charges_normal_rate_below_discount_threshold(): void
    {
        $motorcycle = new Motorcycle('B 5678 XYZ', 'Honda', 'Beat', 100000, VehicleStatus::Available, 125);

        $this->assertSame(600000.0, $motorcycle->calculateRentalCost(6));
        $this->assertSame(VehicleType::Motorcycle, $motorcycle->getType());
    }

    public function test_applies_discount_for_seven_days_or_more(): void
    {
        $motorcycle = new Motorcycle('B 5678 XYZ', 'Honda', 'Beat', 100000, VehicleStatus::Available, 125);

        $this->assertSame(630000.0, $motorcycle->calculateRentalCost(7));
    }

    public function test_rejects_invalid_engine_capacity(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Motorcycle('B 5678 XYZ', 'Honda', 'Beat', 100000, VehicleStatus::Available, 0);
    }
}
