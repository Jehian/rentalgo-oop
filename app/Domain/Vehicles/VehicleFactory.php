<?php

namespace App\Domain\Vehicles;

use App\Enums\VehicleStatus;
use App\Enums\VehicleType;
use App\Exceptions\UnsupportedVehicleTypeException;
use App\Models\Vehicle as VehicleModel;

final class VehicleFactory
{
    public static function fromModel(VehicleModel $model): Vehicle
    {
        try {
            $type = $model->type;
        } catch (\ValueError) {
            throw UnsupportedVehicleTypeException::forType((string) $model->getRawOriginal('type'));
        }

        if (! $type instanceof VehicleType) {
            throw UnsupportedVehicleTypeException::forType((string) $model->getRawOriginal('type'));
        }

        try {
            $status = $model->status;
        } catch (\ValueError) {
            $status = VehicleStatus::Available;
        }

        return self::make($type, [
            'plate_number' => $model->plate_number,
            'brand' => $model->brand,
            'model' => $model->model,
            'daily_rate' => (float) $model->daily_rate,
            'status' => $status,
            'seat_count' => $model->seat_count,
            'transmission' => $model->transmission,
            'engine_cc' => $model->engine_cc,
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(VehicleType $type, array $data): Vehicle
    {
        $status = $data['status'] ?? VehicleStatus::Available;

        if (is_string($status)) {
            $status = VehicleStatus::tryFrom($status) ?? VehicleStatus::Available;
        }

        if (! $status instanceof VehicleStatus) {
            $status = VehicleStatus::Available;
        }

        $common = [
            (string) ($data['plate_number'] ?? ''),
            (string) ($data['brand'] ?? ''),
            (string) ($data['model'] ?? ''),
            (float) ($data['daily_rate'] ?? 0),
            $status,
        ];

        return match ($type) {
            VehicleType::Car => new Car(
                ...$common,
                seatCount: (int) ($data['seat_count'] ?? 0),
                transmission: (string) ($data['transmission'] ?? ''),
            ),
            VehicleType::Motorcycle => new Motorcycle(
                ...$common,
                engineCc: (int) ($data['engine_cc'] ?? 0),
            ),
            default => throw UnsupportedVehicleTypeException::forType($type->value),
        };
    }
}
