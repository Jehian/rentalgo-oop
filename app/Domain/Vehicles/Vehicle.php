<?php

namespace App\Domain\Vehicles;

use App\Contracts\Rentable;
use App\Enums\VehicleStatus;
use InvalidArgumentException;

abstract class Vehicle implements Rentable
{
    public function __construct(
        protected string $plateNumber,
        protected string $brand,
        protected string $model,
        protected float $dailyRate,
        protected VehicleStatus $status,
    ) {
        if (trim($this->plateNumber) === '') {
            throw new InvalidArgumentException('Nomor polisi kendaraan wajib diisi.');
        }

        if (trim($this->brand) === '' || trim($this->model) === '') {
            throw new InvalidArgumentException('Merek dan model kendaraan wajib diisi.');
        }

        if ($this->dailyRate <= 0) {
            throw new InvalidArgumentException('Tarif harian kendaraan harus lebih dari 0.');
        }
    }

    abstract public function calculateRentalCost(int $days): float;

    public function isAvailable(): bool
    {
        return $this->status === VehicleStatus::Available;
    }

    public function getPlateNumber(): string
    {
        return $this->plateNumber;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getDailyRate(): float
    {
        return $this->dailyRate;
    }

    public function getStatus(): VehicleStatus
    {
        return $this->status;
    }

    public function getDisplayName(): string
    {
        return "{$this->brand} {$this->model} ({$this->plateNumber})";
    }

    protected function validateDays(int $days): void
    {
        if ($days < 1) {
            throw new InvalidArgumentException('Durasi rental minimal 1 hari.');
        }
    }

    protected function roundMoney(float $amount): float
    {
        return round($amount, 2);
    }
}
