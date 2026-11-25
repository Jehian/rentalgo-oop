<?php

namespace Tests\Unit\Domain;

use App\Domain\Vehicles\Car;
use App\Domain\Vehicles\Motorcycle;
use App\Domain\Vehicles\VehicleFactory as DomainVehicleFactory;
use App\Enums\VehicleStatus;
use App\Enums\VehicleType;
use App\Exceptions\UnsupportedVehicleTypeException;
use App\Models\Vehicle;
use Tests\TestCase;

class VehicleFactoryTest extends TestCase
{
    public function test_builds_car_from_array(): void
    {
        $vehicle = DomainVehicleFactory::make(VehicleType::Car, [
            'plate_number' => 'B 1111 AA',
            'brand' => 'Toyota',
            'model' => 'Avanza',
            'daily_rate' => 300000,
            'status' => 'available',
            'seat_count' => 7,
            'transmission' => 'Matic',
        ]);

        $this->assertInstanceOf(Car::class, $vehicle);
        $this->assertSame(VehicleStatus::Available, $vehicle->getStatus());
    }

    public function test_builds_motorcycle_from_array(): void
    {
        $vehicle = DomainVehicleFactory::make(VehicleType::Motorcycle, [
            'plate_number' => 'B 2222 BB',
            'brand' => 'Honda',
            'model' => 'Beat',
            'daily_rate' => 100000,
            'status' => VehicleStatus::Available,
            'engine_cc' => 125,
        ]);

        $this->assertInstanceOf(Motorcycle::class, $vehicle);
    }

    public function test_builds_domain_vehicle_from_eloquent_model(): void
    {
        $model = Vehicle::factory()->make([
            'plate_number' => 'B 3333 CC',
            'type' => VehicleType::Car,
            'status' => VehicleStatus::Available,
        ]);

        $this->assertInstanceOf(Car::class, DomainVehicleFactory::fromModel($model));
    }

    public function test_rejects_unsupported_vehicle_type(): void
    {
        $model = new Vehicle;
        $model->setRawAttributes([
            'plate_number' => 'B 9999 ZZ',
            'brand' => 'Unknown',
            'model' => 'Truck',
            'type' => 'truck',
            'status' => VehicleStatus::Available->value,
        ], true);

        $this->expectException(UnsupportedVehicleTypeException::class);

        DomainVehicleFactory::fromModel($model);
    }
}
